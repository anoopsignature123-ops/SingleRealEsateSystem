<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrokerBankDetail extends Model
{
    protected $fillable = ['broker_id', 'bank_name', 'account_number', 'ifsc_code', 'account_holder_name'];

    public function broker()
    {
        return $this->belongsTo(Broker::class);
    }
}