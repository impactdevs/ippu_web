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
        Schema::table('users', function (Blueprint $table) {
            $table->string('dob')->nullable()->after('email');
            $table->string('gender')->nullable()->after('email');
            $table->string('address')->nullable()->after('email');
            $table->string('membership_number')->nullable()->after('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('dob');
            $table->dropColumn('gender');
            $table->dropColumn('address');
            $table->dropColumn('membership_number');
        });
    }
};
