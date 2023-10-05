<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'org_name',
        'org_email',
        'org_phone',
        'org_website',
        'org_address',
        'org_address_1',
        'org_address_2',
        'org_city',
        'org_country',
        'org_license_no',
        'org_logo',
        'org_signature',
        'org_stamp',
        'org_trn_no',
        'org_terms_conditions',
        'org_currency',
        'tax_percent'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
