<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrandAssets extends Model
{
    use HasFactory;

    protected $table = 'brand_asset';

    protected $fillable = [
        'brand_id', 
        'filename',
    ];

    public function brand() {
        return $this->belongsTo('App\Brand');
    }
}
