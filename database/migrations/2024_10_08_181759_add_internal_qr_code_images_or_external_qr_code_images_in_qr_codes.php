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
        Schema::table('qr_codes', function (Blueprint $table) {
            // add internal_qr_code_images, external_qr_code_images after internal_qr_code_scan_count
            $table->text('internal_qr_code_images')->nullable()->after('internal_qr_code_scan_count');
            $table->text('external_qr_code_images')->nullable()->after('external_qr_code_scan_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('qr_codes', function (Blueprint $table) {
            // drop internal_qr_code_images, external_qr_code_images
            $table->dropColumn(['internal_qr_code_images', 'external_qr_code_images']);
        });
    }
};
