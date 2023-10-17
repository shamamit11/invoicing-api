<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'from_account_id',
        'to_account_id',
        'description',
        'amount'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function accountFrom()
    {
        return $this->belongsTo(Account::class, 'from_account_id', 'id');
    }

    public function accountTo()
    {
        return $this->belongsTo(Account::class, 'to_account_id', 'id');
    }
}
