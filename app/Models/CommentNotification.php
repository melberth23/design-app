<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentNotification extends Model
{
    use HasFactory;

    protected $table = 'comments_notification';

    protected $fillable = [
        'comment_id', 
        'user_id',
        'request_id'
    ];

    public function comment()
    {
        return $this->belongsTo(Comments::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function request()
    {
        return $this->belongsTo(Requests::class);
    }
}
