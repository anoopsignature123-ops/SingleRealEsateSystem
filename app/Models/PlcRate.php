<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlcRate extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'plot_type_id',
        'rate',
    ];

    public function plotType()
    {
        return $this->belongsTo(
            PlotType::class
        );
    }
}
