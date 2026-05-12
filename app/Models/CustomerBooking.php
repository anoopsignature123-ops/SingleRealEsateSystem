<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerBooking extends Model
{
    use SoftDeletes;

    protected $fillable = ['associate_id', 'customer_type', 'customer_code', 'customer_name', 'associate_code', 'associate_name', 'current_step', 'status'];

    public function associate()
    {
        return $this->belongsTo(Associate::class);
    }

    public function primaryDetail()
    {
        return $this->hasOne(PrimaryDetail::class);
    }

    public function secondaryDetail()
    {
        return $this->hasOne(SecondaryDetail::class);
    }

    public function nomineeDetail()
    {
        return $this->hasOne(NomineeDetail::class);
    }

    public function plotSaleDetail()
    {
        return $this->hasOne(PlotSaleDetail::class);
    }

    public function payment()
    {
        return $this->hasOne(CustomerPayment::class);
    }

    public function primaryDocument()
    {
        return $this->hasOneThrough(
            CustomerDocument::class,
            PrimaryDetail::class,
            'customer_booking_id', // primary_details.customer_booking_id
            'primary_detail_id',   // customer_documents.primary_detail_id
            'id',                  // customer_bookings.id
            'id'                   // primary_details.id
        );
    }

    public function secondaryDocument()
    {
        return $this->hasOneThrough(
            CustomerDocument::class,
            SecondaryDetail::class,
            'customer_booking_id',
            'secondary_detail_id',
            'id',
            'id'
        );
    }
}
