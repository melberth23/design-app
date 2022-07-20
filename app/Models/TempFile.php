<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_id',
        'module',
        'code',
        'file',
        'file_type'
    ];
}
