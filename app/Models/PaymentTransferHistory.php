<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentTransferHistory extends Model
{
    protected $fillable = [
        'customer_payment_id',
        'old_customer_booking_id',
        'new_customer_booking_id',
        'old_plot_sale_detail_id',
        'new_plot_sale_detail_id',
        'old_booking_code',
        'new_booking_code',
        'old_customer_code',
        'new_customer_code',
        'old_customer_name',
        'new_customer_name',
        'transfer_amount',
        'transfer_date',
        'transfer_reason',
        'remark',
        'created_by',
    ];

    protected $casts = [
        'transfer_date' => 'date',
        'transfer_amount' => 'decimal:2',
    ];

    public function customerPayment()
    {
        return $this->belongsTo(CustomerPayment::class);
    }

    public function oldCustomerBooking()
    {
        return $this->belongsTo(CustomerBooking::class, 'old_customer_booking_id');
    }

    public function newCustomerBooking()
    {
        return $this->belongsTo(CustomerBooking::class, 'new_customer_booking_id');
    }

    public function oldPlotSaleDetail()
    {
        return $this->belongsTo(PlotSaleDetail::class, 'old_plot_sale_detail_id');
    }

    public function newPlotSaleDetail()
    {
        return $this->belongsTo(PlotSaleDetail::class, 'new_plot_sale_detail_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}