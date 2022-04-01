<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestAssets extends Model
{
    use HasFactory;

    protected $table = 'request_assets';

    protected $fillable = [
        'request_id', 
        'filename',
        'type'
    ];
}
