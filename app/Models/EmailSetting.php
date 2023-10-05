<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'host',
        'port',
        'user_name',
        'password',
        'encryption',
        'mail_from_address',
        'mail_from_name'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
