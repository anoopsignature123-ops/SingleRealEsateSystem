<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\CustomerBooking;
use App\Models\PlotSaleDetail;

class CancelBooking extends Model
{
    protected $fillable = [
        'customer_booking_id',
        'plot_sale_detail_id',
        'deduction_amount',
        'deduction_percentage',
        'refund_amount',
        'pay_mode',
        'pay_date',
        'bank_name',
        'account_number',
        'ifsc_code',
        'cheque_date',
    ];

    public function customerBooking()
    {
        return $this->belongsTo(CustomerBooking::class);
    }

    public function plotSaleDetail()
    {
        return $this->belongsTo(PlotSaleDetail::class);
    }

    
}