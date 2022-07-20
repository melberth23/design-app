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
        'custom_dimension',
        'format',
        'adobe_format',
        'description',
        'dimensions_additional_info',
        'brand_id',
        'user_id',
        'include_text',
        'included_text_description',
        'reference_link',
        'status',
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

    public function designtype()
    {
        return $this->belongsTo(Admin\RequestTypes::class, 'design_type', 'id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
