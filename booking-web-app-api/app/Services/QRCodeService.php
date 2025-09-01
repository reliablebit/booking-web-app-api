<?php

namespace App\Services;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class QRCodeService
{
    /**
     * Generate QR code for booking
     */
    public function generateBookingQR($booking)
    {
        $qrData = [
            'booking_ref' => $booking->booking_ref,
            'user_id' => $booking->user_id,
            'listing_id' => $booking->listing_id,
            'seat_number' => $booking->seat_number,
            'status' => $booking->status,
            'generated_at' => now()->toISOString()
        ];

        $qrContent = json_encode($qrData);

        // Generate QR code as SVG (doesn't require ImageMagick)
        $qrCode = QrCode::format('svg')
            ->size(300)
            ->errorCorrection('H')
            ->generate($qrContent);

        // Save QR code to storage
        $filename = 'qr-codes/booking-' . $booking->booking_ref . '.svg';
        Storage::disk('public')->put($filename, $qrCode);

        return [
            'qr_code_path' => $filename,
            'qr_code_url' => asset('storage/' . $filename),
            'qr_data' => $qrData
        ];
    }

    /**
     * Generate QR code for ticket
     */
    public function generateTicketQR($ticket)
    {
        $qrData = [
            'ticket_id' => $ticket->id,
            'booking_ref' => $ticket->booking->booking_ref,
            'ticket_number' => $ticket->ticket_number,
            'user_name' => $ticket->booking->user->name,
            'listing_title' => $ticket->booking->listing->title,
            'seat_number' => $ticket->booking->seat_number,
            'start_time' => $ticket->booking->listing->start_time,
            'generated_at' => now()->toISOString()
        ];

        $qrContent = json_encode($qrData);

        // Generate QR code
        $qrCode = QrCode::format('png')
            ->size(400)
            ->errorCorrection('H')
            ->generate($qrContent);

        // Save QR code to storage
        $filename = 'qr-codes/ticket-' . $ticket->ticket_number . '.png';
        Storage::disk('public')->put($filename, $qrCode);

        return [
            'qr_code_path' => $filename,
            'qr_code_url' => asset('storage/' . $filename),
            'qr_data' => $qrData
        ];
    }

    /**
     * Verify QR code data
     */
    public function verifyQR($qrContent)
    {
        try {
            $data = json_decode($qrContent, true);

            if (!$data) {
                return ['valid' => false, 'message' => 'Invalid QR code format'];
            }

            // Add verification logic here
            // Check if booking/ticket exists and is valid

            return [
                'valid' => true,
                'data' => $data,
                'message' => 'QR code verified successfully'
            ];
        } catch (\Exception $e) {
            return [
                'valid' => false,
                'message' => 'QR code verification failed: ' . $e->getMessage()
            ];
        }
    }
}
