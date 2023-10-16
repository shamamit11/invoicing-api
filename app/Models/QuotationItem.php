<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'quotation_id',
        'description',
        'qty',
        'amount'
    ];

    public function user()
    {
        return $this->belongsTo(Quotation::class, 'quotation_id', 'id');
    }
}
