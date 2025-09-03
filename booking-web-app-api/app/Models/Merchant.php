<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use mysql_xdevapi\ExecutionStatus;

class Merchant extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'business_name',
        'category',
        'address',
        'status'
    ];
    // app/Models/Merchant.php
    public function user()
    {
        return $this->belongsTo(User::class);
    }
//    public function user()
//    {
//        return $this->belongsTo(User::class);
//    }

    public function listings()
    {
        return $this->hasMany(Listing::class);
    }
}
