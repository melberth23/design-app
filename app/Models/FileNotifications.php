<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileNotifications extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_id',
        'user_id'
    ];
}
