<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'customer_id',
        'invoice_code',
        'invoice_no',
        'date',
        'total_amount',
        'tax_percent',
        'total_tax',
        'send_email',
        'total_paid_amount',
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
        return $this->hasMany(InvoiceItem::class, 'invoice_id', 'id');
    }

    public function payment()
    {
        return $this->hasMany(InvoicePayment::class, 'invoice_id', 'id');
    }
}
