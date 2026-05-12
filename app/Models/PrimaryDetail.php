<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrimaryDetail extends Model
{
    protected $fillable = [
        'customer_booking_id',
        'name',
        'title',
        'relation_name',
        'dob',
        'gender',
        'permanent_address',
        'pin_code',
        'city',
        'state',
        'same_as_permanent_address',
        'fill_secondary_detail',
    ];

    public function customerBooking()
    {
        return $this->belongsTo(CustomerBooking::class);
    }

    public function correspondenceDetail()
    {
        return $this->hasOne(CorrespondenceDetail::class);
    }

    public function customerDocument()
    {
        return $this->hasOne(CustomerDocument::class);
    }
}
