<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prefix extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'receipt_prefix',
        'receipt_start_no',
        'quote_prefix',
        'quote_start_no',
        'invoice_prefix',
        'invoice_start_no'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
