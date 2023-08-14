<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sale extends Model {
    use HasFactory;
    protected $fillable = ['user_id', 'shop_id', 'sale_invoice_id', 'product_id', 'qty', 'name', 'price'];

    // public function sale_invoice(): BelongsTo {
    //     return $this->belongsTo( SaleInvoice::class );
    // }
}
