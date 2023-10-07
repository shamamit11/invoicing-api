<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'customer_id',
        'quote_code',
        'quote_no',
        'date',
        'total_amount',
        'tax_percent',
        'total_tax',
        'send_email',
        'terms_conditions',
        'is_deleted'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function items()
    {
        return $this->hasMany(QuotationItem::class, 'quotation_id', 'id');
    }
}
