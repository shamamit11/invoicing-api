<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavedItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'description',
        'amount',
        'status',
        'is_deleted'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
