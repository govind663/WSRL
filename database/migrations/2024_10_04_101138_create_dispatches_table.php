<?php

use App\Models\Distributor;
use App\Models\Product;
use App\Models\User;
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
        Schema::create('dispatches', function (Blueprint $table) {
            $table->id();
            $table->string('dispatch_code')->unique()->nullable()->comment('Dispatch Code');

            // === associated
            $table->foreignIdFor(User::class)->nullable()->index()->comment('User who created the dispatch');
            $table->foreignIdFor(Distributor::class)->nullable()->index()->comment('Reference to Dispatch');
            $table->foreignIdFor(Product::class)->nullable()->index()->comment('Reference to Product');

            // === qr code maping
            $table->text('externally_qr_code_mapping')->nullable()->comment('Reference to External QR Code');

            // === dates
            $table->date('dispatched_at')->nullable();

            // === status in enum
            $table->enum('dispatch_status', ['pending', 'dispatched', 'canceled', 'completed'])->default('pending')->comment('Dispatch status');

            // === timestamps
            $table->integer('inserted_by')->nullable();
            $table->timestamp('inserted_at')->nullable();
            $table->integer('modified_by')->nullable();
            $table->timestamp('modified_at')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->timestamp('deleted_at')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dispatches');
    }
};
