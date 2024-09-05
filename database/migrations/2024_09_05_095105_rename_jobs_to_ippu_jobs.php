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
        // Rename the 'jobs' table to 'ippu_jobs'
        Schema::rename('jobs', 'ippu_jobs');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rename the 'ippu_jobs' table back to 'jobs'
        Schema::rename('ippu_jobs', 'jobs');
    }
};
