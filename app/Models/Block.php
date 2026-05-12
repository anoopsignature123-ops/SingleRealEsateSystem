<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Block extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'project_id',
        'block',
    ];

    public function project()
    {
        return $this->belongsTo(
            Project::class
        );
    }
}
