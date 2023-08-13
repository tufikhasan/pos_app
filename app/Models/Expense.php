<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model {
    use HasFactory;
    protected $fillable = ['user_id', 'shop_id', 'expense_category_id', 'amount', 'description'];

    public function user(): BelongsTo {
        return $this->belongsTo( User::class );
    }
    public function expense_category(): BelongsTo {
        return $this->belongsTo( ExpenseCategory::class );
    }
}
