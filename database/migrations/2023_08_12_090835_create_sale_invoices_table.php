<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create( 'sale_invoices', function ( Blueprint $table ) {
            $table->id();

            $table->foreignId( 'user_id' )->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId( 'shop_id' )->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId( 'customer_id' )->constrained()->cascadeOnUpdate()->restrictOnDelete();

            $table->string( 'total_qty' );
            $table->string( 'sub_total' );
            $table->string( 'tax' );
            $table->string( 'discount' )->default( 0 );
            $table->string( 'total' );

            $table->timestamp( 'created_at' )->useCurrent();
            $table->timestamp( 'updated_at' )->useCurrent()->useCurrentOnUpdate();
        } );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists( 'sale_invoices' );
    }
};
