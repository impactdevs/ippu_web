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
            $table->enum('status',['Inactive','Active','Suspended'])->after('user_type')->default('Inactive');
            $table->string('phone_no')->after('email')->nullable();
            $table->string('alt_phone_no')->after('phone_no')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('phone_no');
            $table->dropColumn('alt_phone_no');
        });
    }
};
