<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SaleInvoice extends Model {
    use HasFactory;
    protected $fillable = ['user_id', 'shop_id', 'customer_id', 'total', 'discount', 'tax'];
    public function customer(): BelongsTo {
        return $this->belongsTo( Customer::class );
    }
    public function sale_products(): HasMany {
        return $this->hasMany( Sale::class );
    }
}
