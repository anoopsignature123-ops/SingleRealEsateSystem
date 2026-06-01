<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Broker extends Model
{
    protected $fillable = ['name', 'address', 'city', 'state', 'pancard_number', 'aadhar_number', 'mobile_number'];

    public function bankDetail()
    {
        return $this->hasOne(BrokerBankDetail::class);
    }
}