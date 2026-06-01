<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FarmerBankDetail extends Model
{
    protected $fillable = ['farmer_id', 'bank_name', 'account_number', 'ifsc_code', 'account_holder_name'];

    public function farmer()
    {
        return $this->belongsTo(Farmer::class);
    }
}