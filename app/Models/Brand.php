<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Brand extends Model {
    use HasFactory;
    protected $fillable = ['user_id', 'shop_id', 'name', 'image'];
    public function user(): BelongsTo {
        return $this->belongsTo( User::class );
    }
}
