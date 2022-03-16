<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{
    use HasFactory;

    protected $table = 'payments';

    protected $fillable = [
        'user_id',
        'reference',
        'business_recurring_plans_id',
        'plan',
        'cycle',
        'currency',
        'price',
        'status',
        'payment_methods',
        'payment_url'
    ];
}
