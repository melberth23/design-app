<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reviews extends Model
{
    use HasFactory;

    protected $table = 'reviews';

    protected $fillable = [
        'user_id',
        'rate_designer',
        'com_designer',
        'experience_to_designer',
        'work_again_option',
        'rate_platform',
        'experience_platform',
        'suggestion',
        'recommend_option'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function request()
    {
        return $this->belongsTo(Requests::class);
    }
}
