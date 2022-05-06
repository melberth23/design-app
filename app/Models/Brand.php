<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $table = 'brands';

    protected $fillable = [
        'name',
        'target_audience',
        'description',
        'industry',
        'services_provider',
        'website',
        'other_inspirations',
        'facebook',
        'linkedin',
        'instagram',
        'twitter',
        'youtube',
        'tiktok',
        'user_id',
        'status'
    ];

    public function assets(){
        $this->hasMany(BrandAssets::class, 'brand_id');
    }
}
