<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlotRate extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'project_id',
        'block_id',
        'plot_rate',
    ];

    public function project()
    {
        return $this->belongsTo(
            Project::class
        );
    }

    public function block()
    {
        return $this->belongsTo(
            Block::class
        );
    }
}
