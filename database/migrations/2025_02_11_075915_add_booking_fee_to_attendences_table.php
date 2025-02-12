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
            //should be a big integer
            $table->bigInteger('booking_fee')->nullable(); // Adds a booking_fee column to the attendences table
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
