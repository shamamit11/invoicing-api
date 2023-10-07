<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'user_id',
        'name',
        'phone',
        'email',
        'address_1',
        'address_2',
        'city',
        'country',
        'trn_no',
        'status',
        'is_deleted'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
