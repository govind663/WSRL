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
        Schema::table('distributors', function (Blueprint $table) {
            // add distributor_gstin, distributor_pos, other_address and division after address
            $table->string('distributor_gstin')->nullable()->after('address');
            $table->string('distributor_pos')->nullable()->after('distributor_gstin');
            $table->text('other_address')->nullable()->after('distributor_pos');
            $table->string('division')->nullable()->after('other_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('distributors', function (Blueprint $table) {
            // === drop column
            $table->dropColumn(['distributor_gstin', 'distributor_pos', 'other_address', 'division']);
        });
    }
};
