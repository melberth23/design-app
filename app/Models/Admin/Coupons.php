<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupons extends Model
{
    use HasFactory;

    protected $table = 'coupons';

    protected $fillable = [
        'code',
        'discount_type',
        'discount_amount',
        'description',
        'status'
    ];
}
