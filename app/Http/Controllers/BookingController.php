<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Listing;
use App\Models\Ticket;
use App\Models\BookingLock;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;

// Correct BaconQrCode v2 imports
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Writer;

class BookingController extends Controller
{
    // STEP A: Create a HOLD (lock) for a seat (or free seating)
    public function store(Request $request)
    {
        $request->validate([
            'listing_id'  => 'required|exists:listings,id',
            'seat_number' => 'nullable|string'
        ]);

        $userId = Auth::id();
        $ttlMinutes = 10; // hold expires in 10 minutes

        return DB::transaction(function () use ($request, $userId, $ttlMinutes) {
            $listing = Listing::lockForUpdate()->findOrFail($request->listing_id);

            // Clean up expired holds for this listing
            BookingLock::where('listing_id', $listing->id)
                ->where('status', 'held')
                ->where('expires_at', '<', now())
                ->update(['status' => 'released']);

            // Seat availability check
            if ($request->seat_number) {
                $seat = $request->seat_number;

                $seatHeld = BookingLock::where('listing_id', $listing->id)
                    ->where('seat_number', $seat)
                    ->where('status', 'held')
                    ->where('expires_at', '>', now())
                    ->exists();

                $seatConfirmed = Booking::where('listing_id', $listing->id)
                    ->where('seat_number', $seat)
                    ->where('status', 'confirmed')
                    ->exists();

                if ($seatHeld || $seatConfirmed) {
                    return response()->json(['error' => 'Seat already taken or on hold'], 409);
                }
            } else {
                // Free seating: ensure capacity allows a new hold
                $confirmedCount = Booking::where('listing_id', $listing->id)
                    ->where('status', 'confirmed')->count();
                $heldCount = BookingLock::where('listing_id', $listing->id)
                    ->where('status', 'held')
                    ->where('expires_at', '>', now())
                    ->count();
                if (($confirmedCount + $heldCount) >= $listing->total_seats) {
                    return response()->json(['error' => 'No seats available to hold'], 409);
                }
            }

            // Create HOLD
            $lock = BookingLock::create([
                'listing_id'  => $listing->id,
                'user_id'     => $userId,
                'seat_number' => $request->seat_number,
                'expires_at'  => now()->addMinutes($ttlMinutes),
                'status'      => 'held'
            ]);

            // Create PENDING booking linked to the hold
            $booking = Booking::create([
                'user_id'     => $userId,
                'listing_id'  => $listing->id,
                'status'      => 'pending',
                'seat_number' => $request->seat_number,
                'booking_ref' => strtoupper(Str::random(10)),
            ]);

            return response()->json([
                'message'       => 'Seat held. Complete payment to confirm.',
                'hold_expires'  => $lock->expires_at,
                'booking_id'    => $booking->id,
                'booking_ref'   => $booking->booking_ref
            ], 201);
        });
    }

    // STEP B: Confirm booking
    public function confirm($id)
    {
        return DB::transaction(function () use ($id) {
            $booking = Booking::lockForUpdate()->with('listing')->findOrFail($id);

            if ($booking->user_id !== Auth::id()) {
                return response()->json(['error' => 'Forbidden'], 403);
            }
            if ($booking->status === 'confirmed') {
                return response()->json(['message' => 'Already confirmed', 'booking' => $booking->load('ticket')]);
            }
            if ($booking->status === 'cancelled') {
                return response()->json(['error' => 'Booking was cancelled'], 422);
            }

            // Ensure HOLD is still valid
            $validHold = BookingLock::where('listing_id', $booking->listing_id)
                ->where('user_id', $booking->user_id)
                ->where(function ($q) use ($booking) {
                    if ($booking->seat_number) $q->where('seat_number', $booking->seat_number);
                })
                ->where('status', 'held')
                ->where('expires_at', '>', now())
                ->exists();

            if (!$validHold) {
                $listing = $booking->listing()->lockForUpdate()->first();
                $confirmedCount = Booking::where('listing_id', $listing->id)
                    ->where('status', 'confirmed')->count();
                if ($confirmedCount >= $listing->total_seats) {
                    return response()->json(['error' => 'No seats available'], 409);
                }
            }

            // Confirm booking
            $booking->status = 'confirmed';
            $booking->save();

            // Release the hold
            BookingLock::where('listing_id', $booking->listing_id)
                ->where('user_id', $booking->user_id)
                ->where(function ($q) use ($booking) {
                    if ($booking->seat_number) $q->where('seat_number', $booking->seat_number);
                })
                ->where('status', 'held')
                ->update(['status' => 'released']);


            // Generate QR code if not exists
            $ticket = $booking->ticket;
            if (!$ticket) {
                if (!Storage::disk('public')->exists('qrcodes')) {
                    Storage::disk('public')->makeDirectory('qrcodes');
                }

                $qrData = "BOOKING_REF:{$booking->booking_ref}";
                $fileRel = "qrcodes/{$booking->id}.svg";
                $fullPath = Storage::disk('public')->path($fileRel);

                $renderer = new ImageRenderer(
                    new RendererStyle(300),
                    new SvgImageBackEnd()
                );
                $writer = new Writer($renderer);
                $writer->writeFile($qrData, $fullPath);

                $ticket = Ticket::create([
                    'booking_id'   => $booking->id,
                    'qr_code_path' => "storage/{$fileRel}",
                    'issued_at'    => now()
                ]);
            }

            return response()->json([
                'message' => 'Booking confirmed',
                'booking' => $booking->load('ticket','listing')
            ]);
        });
    }

    // STEP C: Cancel booking
    public function cancel($id)
    {
        return DB::transaction(function () use ($id) {
            $booking = Booking::lockForUpdate()->findOrFail($id);

            if ($booking->user_id !== Auth::id()) {
                return response()->json(['error' => 'Forbidden'], 403);
            }
            if ($booking->status === 'cancelled') {
                return response()->json(['message' => 'Already cancelled']);
            }

            $booking->status = 'cancelled';
            $booking->save();

            BookingLock::where('listing_id', $booking->listing_id)
                ->where('user_id', $booking->user_id)
                ->where(function ($q) use ($booking) {
                    if ($booking->seat_number) $q->where('seat_number', $booking->seat_number);
                })
                ->where('status', 'held')
                ->update(['status' => 'released']);

            return response()->json(['message' => 'Booking cancelled']);
        });
    }

    // STEP E: Download QR image
    public function ticketDownload($id)
    {
        $booking = Booking::with('ticket')->where('user_id', Auth::id())->findOrFail($id);
        if (!$booking->ticket || !$booking->ticket->qr_code_path) {
            return response()->json(['error' => 'Ticket not available'], 404);
        }

        $publicRelative = str_replace('storage/', '', $booking->ticket->qr_code_path);
        $fullPath = Storage::disk('public')->path($publicRelative);

        if (!file_exists($fullPath)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        return Response::download($fullPath, "ticket-{$booking->booking_ref}.svg");
    }
    public function show($id)
    {
        $booking = Booking::with('listing','ticket')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return response()->json($booking);
    }

}
