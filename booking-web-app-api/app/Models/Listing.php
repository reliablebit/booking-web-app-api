<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    protected $fillable = [
        'merchant_id', 'title', 'type', 'price', 'total_seats', 'available_seats', 'start_time', 'location'
    ];

    // Note: available_seats is stored but real availability is calculated dynamically
    // in AvailabilityController based on confirmed bookings and active holds

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

// app/Models/Listing.php
    public function locks() { return $this->hasMany(BookingLock::class); }

}
