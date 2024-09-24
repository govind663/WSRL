<?php

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
        Schema::create('qr_codes', function (Blueprint $table) {
            $table->id();

            $table->string('unique_number')->unique();
            $table->foreignIdFor(User::class)->nullable()->constrained()->index();
            $table->integer('quantity');
            $table->text('internal_qr_code')->nullable();
            $table->text('external_qr_code')->nullable();

            // Add columns for the Counts of the internal and external QR codes
            $table->integer('internal_qr_code_count')->default(0);
            $table->integer('external_qr_code_count')->default(0);

            // Add columns for the status of the internal and external QR codes
            $table->enum('internal_qr_code_status', ['active', 'printed', 'scanned'])->default('active');
            $table->enum('external_qr_code_status', ['active', 'printed', 'scanned'])->default('active');

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
        Schema::dropIfExists('qr_codes');
    }
};
