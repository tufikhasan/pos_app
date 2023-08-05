<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model {
    use HasFactory;
    protected $fillable = ['user_id', 'name', 'price', 'unit', 'image', 'brand_id', 'category_id'];

    public function brand(): BelongsTo {
        return $this->belongsTo( Brand::class );
    }
    public function category(): BelongsTo {
        return $this->belongsTo( Category::class );
    }
}
