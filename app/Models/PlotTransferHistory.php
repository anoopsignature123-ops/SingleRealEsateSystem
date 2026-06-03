<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlotTransferHistory extends Model
{
    protected $fillable = [
        'plot_sale_detail_id',
        'old_booking_id',
        'new_booking_id',
        'old_customer_code',
        'new_customer_code',
        'old_customer_name',
        'new_customer_name',
        'transfer_charge',
        'transfer_date',
        'transfer_reason',
        'remark',
        'created_by',
    ];

    protected $casts = [
        'transfer_date' => 'date',
        'transfer_charge' => 'decimal:2',
    ];

    public function plotSaleDetail()
    {
        return $this->belongsTo(PlotSaleDetail::class);
    }

    public function oldBooking()
    {
        return $this->belongsTo(CustomerBooking::class, 'old_booking_id');
    }

    public function newBooking()
    {
        return $this->belongsTo(CustomerBooking::class, 'new_booking_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}