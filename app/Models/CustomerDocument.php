<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerDocument extends Model
{
    protected $fillable = [
        'primary_detail_id',
        'secondary_detail_id',
        'dl',
        'aadhar',
        'voter_id',
        'other',
        'dl_file',
        'aadhar_file',
        'voter_id_file',
        'other_file',
        'profile_picture',
    ];

    public function primaryDetail()
    {
        return $this->belongsTo(PrimaryDetail::class);
    }

    public function secondaryDetail()
    {
        return $this->belongsTo(SecondaryDetail::class);
    }
}