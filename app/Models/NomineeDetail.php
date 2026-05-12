<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NomineeDetail extends Model
{
    protected $fillable = [
        'customer_booking_id',
        'name',
        'ralation',
    ];

    public function customerBooking()
    {
        return $this->belongsTo(CustomerBooking::class);
    }
}
