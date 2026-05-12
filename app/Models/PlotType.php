<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlotType extends Model
{
    use SoftDeletes;

    protected $fillable = [

        'plot_type_name',

        'date',

    ];
}
