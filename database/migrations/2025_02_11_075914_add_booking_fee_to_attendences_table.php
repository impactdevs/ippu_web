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
        Schema::table('attendences', function (Blueprint $table) {
            $table->decimal('booking_fee', 8, 2)->nullable(); // Adds the booking_fee column (8 digits, 2 decimal points)
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendences', function (Blueprint $table) {
            $table->dropColumn('booking_fee'); // Drops the booking_fee column if the migration is rolled back
        });
    }
};
