<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewAttempt extends Model
{
    use HasFactory;

    protected $table = 'attempt_new_payments';

    protected $fillable = [
        'reference',
        'user_id',
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
