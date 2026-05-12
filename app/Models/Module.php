<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = ['name', 'slug', 'parent_id', 'route_name', 'icon', 'sort_order', 'active_routes'];

    public function children()
    {
        return $this->hasMany(Module::class, 'parent_id')->orderBy('sort_order');
    }

    public function parent()
    {
        return $this->belongsTo(Module::class, 'parent_id');
    }
}
