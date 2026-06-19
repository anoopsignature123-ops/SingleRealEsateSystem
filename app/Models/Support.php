<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Support extends Model
{
    protected $fillable = ['associate_id', 'customer_booking_id', 'query', 'description', 'reply', 'status'];

    public function associate()
    {
        return $this->belongsTo(Associate::class);
    }

    public function customerBooking()
    {
        return $this->belongsTo(CustomerBooking::class);
    }
}
