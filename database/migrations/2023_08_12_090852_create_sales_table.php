<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create( 'sales', function ( Blueprint $table ) {
            $table->id();

            $table->foreignId( 'user_id' )->constrained();
            $table->foreignId( 'shop_id' )->constrained();
            $table->foreignId( 'sale_invoice_id' )->constrained();
            $table->foreignId( 'product_id' )->constrained();

            $table->string( 'qty' );
            $table->string( 'price' );

            $table->timestamp( 'created_at' )->useCurrent();
            $table->timestamp( 'updated_at' )->useCurrent()->useCurrentOnUpdate();
        } );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists( 'sales' );
    }
};
