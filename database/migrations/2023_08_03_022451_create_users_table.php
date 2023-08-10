<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void{
        Schema::create( 'users', function ( Blueprint $table ) {
            $table->id();
            $table->foreignId( 'shop_id' )->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->string( 'name' );
            $table->string( 'email' )->unique();
            $table->string( 'mobile' )->unique()->nullable();
            $table->string( 'role' )->default( 'user' );
            $table->string( 'otp' )->default( 0 );
            $table->string( 'image' )->nullable();
            $table->string( 'password' );
            $table->timestamp( 'created_at' )->useCurrent();
            $table->timestamp( 'updated_at' )->useCurrent()->useCurrentOnUpdate();
        } );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void{
        Schema::dropIfExists( 'users' );
    }
};
