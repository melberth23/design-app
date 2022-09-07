<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoices extends Model
{
    use HasFactory;

    protected $table = 'invoices';

    protected $fillable = [
        'user_id',
        'payment_id',
        'number',
        'date_invoice',
        'plan',
        'amount'
    ];

    public function payment()
    {
        return $this->belongsTo(Payments::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
