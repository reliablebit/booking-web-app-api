<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id', 'listing_id', 'status', 'seat_number', 'booking_ref',
        'payment_intent_id', 'payment_status', 'confirmed_at', 'cancelled_at',
        'cancellation_reason', 'refund_amount', 'refund_status', 'fraud_score', 'fraud_flags'
    ];

    protected $casts = [
        'confirmed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'fraud_flags' => 'array',
        'refund_amount' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }

    public function ticket()
    {
        return $this->hasOne(Ticket::class);
    }
}
