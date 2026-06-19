<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CorrespondenceDetail extends Model
{
    protected $fillable = [
        'primary_detail_id',
        'secondary_detail_id',
        'correspondence_address',
        'pin_code',
        'city',
        'state',
        'mobile_number',
        'email',
        'id_proof_type',
        'id_proof_number',
        'occupation',
        'nationality',
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
