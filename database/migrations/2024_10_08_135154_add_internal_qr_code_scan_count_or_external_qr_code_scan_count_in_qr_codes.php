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
            // add internal_qr_code_scan_count, external_qr_code_scan_count after external_qr_code_count
            $table->integer('internal_qr_code_scan_count')->default(0)->after('external_qr_code_count');
            $table->integer('external_qr_code_scan_count')->default(0)->after('external_qr_code_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('qr_codes', function (Blueprint $table) {
            // drop column internal_qr_code_scan_count, external_qr_code_scan_count
            $table->dropColumn(['internal_qr_code_scan_count', 'external_qr_code_scan_count']);
        });
    }
};
