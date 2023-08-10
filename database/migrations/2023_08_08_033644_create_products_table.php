<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void{
        Schema::create( 'products', function ( Blueprint $table ) {
            $table->id();
            $table->string( 'name' );
            $table->string( 'unit' );
            $table->string( 'price' );
            $table->string( 'sku' );
            $table->string( 'stock' );
            $table->string( 'image' )->nullable();
            $table->foreignId( 'brand_id' )->constrained();
            $table->foreignId( 'category_id' )->constrained();
            $table->foreignId( 'user_id' )->constrained();
            $table->foreignId( 'shop_id' )->constrained();
            $table->timestamp( 'created_at' )->useCurrent();
            $table->timestamp( 'updated_at' )->useCurrent()->useCurrentOnUpdate();
        } );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void{
        Schema::dropIfExists( 'products' );
    }
};
