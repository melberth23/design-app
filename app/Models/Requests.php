<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requests extends Model
{
    use HasFactory;

    protected $table = 'requests';

    protected $fillable = [
        'title',
        'design_type',
        'dimensions',
        'format',
        'description',
        'dimensions_additional_info',
        'brand_id',
        'user_id',
        'priority'
    ];

    public function comments()
    {
        return $this->hasMany(Comments::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
