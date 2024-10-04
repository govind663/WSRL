<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // remove total_quantity, available_quantity, price
            $table->dropColumn(['total_quantity', 'available_quantity', 'price']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // add total_quantity, available_quantity, price
            $table->integer('total_quantity')->nullable();
            $table->integer('available_quantity')->nullable();
            $table->integer('price')->nullable();
        });
    }
};
