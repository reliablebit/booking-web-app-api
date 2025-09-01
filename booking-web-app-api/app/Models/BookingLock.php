<?php

// app/Models/BookingLock.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingLock extends Model
{
    protected $fillable = [
        'listing_id','user_id','seat_number','expires_at','status'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function listing() { return $this->belongsTo(Listing::class); }
    public function user()    { return $this->belongsTo(User::class); }
}
