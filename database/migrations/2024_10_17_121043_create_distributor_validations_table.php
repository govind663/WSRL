<?php

use App\Models\Distributor;
use App\Models\Product;
use App\Models\QrCode;
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
        Schema::create('distributor_validations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Distributor::class)->nullable()->comment('Reference to the distributor');
            $table->foreignIdFor(Product::class)->nullable()->comment('Reference to the product or item being validated');
            $table->foreignIdFor(QrCode::class)->nullable()->index()->comment('Reference to the QR Code');
            $table->integer('quantity_validated')->default(0)->comment('Amount validated by the distributor');
            $table->timestamp('validation_date')->nullable();
            $table->integer('inserted_by')->nullable();
            $table->timestamp('inserted_dt')->nullable();
            $table->integer('modified_by')->nullable();
            $table->timestamp('modified_dt')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distributor_validations');
    }
};
