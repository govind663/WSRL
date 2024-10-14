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
        Schema::table('qr_code_scans', function (Blueprint $table) {
            // add internal_serial_no_qr_code, external_serial_no_qr_code after type
            $table->text('internal_serial_no_qr_code')->after('type')->nullable();
            $table->text('external_serial_no_qr_code')->after('internal_serial_no_qr_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('qr_code_scans', function (Blueprint $table) {
            // drop internal_serial_no_qr_code, external_serial_no_qr_code
            $table->dropColumn(['internal_serial_no_qr_code', 'external_serial_no_qr_code']);
        });
    }
};
