<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Farmer extends Model
{
    protected $fillable = [
        'broker_id', 'name', 'caste', 'mobile_number', 'city', 'state', 'pancard_number', 'aadhar_number', 'address'
    ];

    public function broker()
    {
        return $this->belongsTo(Broker::class);
    }

    public function bankDetail()
    {
        return $this->hasOne(FarmerBankDetail::class);
    }
}