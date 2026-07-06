<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnquiryType extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function enquiries()
    {
        return $this->hasMany(Enquiry::class, 'enquiry_types_id');
    }
}
