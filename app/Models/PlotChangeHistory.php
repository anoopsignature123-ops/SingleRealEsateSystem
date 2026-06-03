<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlotChangeHistory extends Model
{
    protected $fillable = [

        'customer_booking_id',
        'plot_sale_detail_id',

        'old_project_id',
        'old_block_id',
        'old_plot_detail_id',

        'new_project_id',
        'new_block_id',
        'new_plot_detail_id',

        'old_plot_rate',
        'old_plot_area',
        'old_plot_cost',
        'old_plc_amount',
        'old_total_plot_cost',

        'new_plot_rate',
        'new_plot_area',
        'new_plot_cost',
        'new_plc_amount',
        'new_total_plot_cost',

        'total_paid_amount',
        'old_due_amount',
        'new_due_amount',
        'difference_amount',

        'change_date',
        'change_reason',
        'remark',

        'changed_by',
    ];

    protected $casts = [
        'change_date' => 'date',

        'old_plot_rate' => 'decimal:2',
        'old_plot_area' => 'decimal:2',
        'old_plot_cost' => 'decimal:2',
        'old_plc_amount' => 'decimal:2',
        'old_total_plot_cost' => 'decimal:2',

        'new_plot_rate' => 'decimal:2',
        'new_plot_area' => 'decimal:2',
        'new_plot_cost' => 'decimal:2',
        'new_plc_amount' => 'decimal:2',
        'new_total_plot_cost' => 'decimal:2',

        'total_paid_amount' => 'decimal:2',
        'old_due_amount' => 'decimal:2',
        'new_due_amount' => 'decimal:2',
        'difference_amount' => 'decimal:2',
    ];

    public function customerBooking()
    {
        return $this->belongsTo(
            CustomerBooking::class,
            'customer_booking_id'
        );
    }

    public function plotSaleDetail()
    {
        return $this->belongsTo(
            PlotSaleDetail::class,
            'plot_sale_detail_id'
        );
    }

    public function oldProject()
    {
        return $this->belongsTo(
            Project::class,
            'old_project_id'
        );
    }

    public function oldBlock()
    {
        return $this->belongsTo(
            Block::class,
            'old_block_id'
        );
    }

    public function oldPlot()
    {
        return $this->belongsTo(
            PlotDetail::class,
            'old_plot_detail_id'
        );
    }

    public function newProject()
    {
        return $this->belongsTo(
            Project::class,
            'new_project_id'
        );
    }

    public function newBlock()
    {
        return $this->belongsTo(
            Block::class,
            'new_block_id'
        );
    }

    public function newPlot()
    {
        return $this->belongsTo(
            PlotDetail::class,
            'new_plot_detail_id'
        );
    }

    public function changedBy()
    {
        return $this->belongsTo(
            User::class,
            'changed_by'
        );
    }
}