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
        Schema::table('events', function (Blueprint $table) {
            //
            $table->string('event_type')->default('Normal');
            $table->string('place')->nullable();
            $table->string('theme')->nullable();
            $table->date('annual_event_date')->nullable();
            $table->string('organizing_committee')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            //
             // Remove the columns
             $table->dropColumn('event_type');
             $table->dropColumn('place');
             $table->dropColumn('theme');
             $table->dropColumn('annual_event_date');
             $table->dropColumn('organizing_committee');
        });
    }
};
