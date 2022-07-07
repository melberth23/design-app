<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dimensions extends Model
{
    use HasFactory;

    protected $table = 'request_type_dimensions';

    protected $fillable = [
        'request_type_id',
        'label',
        'value'
    ];
}
