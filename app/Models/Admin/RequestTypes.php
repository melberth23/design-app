<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestTypes extends Model
{
    use HasFactory;

    protected $table = 'request_types';

    protected $fillable = [
        'name',
        'description',
        'status'
    ];

    public function requests()
    {
        return $this->hasMany(Requests::class);
    }
}
