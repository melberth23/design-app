<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{
    use HasFactory;

    protected $table = 'payments';

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

    public function scopeIsPaid($query) {
        return $query->where('status', 'active');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
