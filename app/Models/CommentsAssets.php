<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentsAssets extends Model
{
    use HasFactory;

    protected $table = 'comments_assets';

    protected $fillable = [
        'comments_id', 
        'filename',
        'type',
        'file_type'
    ];

    public function comments()
    {
        return $this->belongsTo(Comments::class);
    }
}
