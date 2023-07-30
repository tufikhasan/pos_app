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
            $table->string( 'price' );
            $table->string( 'unit' );
            $table->string( 'image' )->nullable();
            $table->foreignId( 'user_id' )->constrained();
            $table->foreignId( 'brand_id' )->constrained();
            $table->foreignId( 'category_id' )->constrained();
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
