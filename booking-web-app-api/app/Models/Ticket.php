<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'booking_id', 'qr_code_path', 'issued_at'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
