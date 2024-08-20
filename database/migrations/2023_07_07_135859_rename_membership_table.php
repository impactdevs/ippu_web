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
        Schema::table('memberships', function (Blueprint $table) {
            $table->dropColumn('approved_by');
            $table->dropColumn('denied_by');
            $table->dropColumn('approval_reason');
            $table->dropColumn('approval_date');
            $table->dropColumn('denial_date');
            $table->string('comment')->nullable()->after('expiry_date');
            $table->integer('processed_by')->nullable()->after('expiry_date');
            $table->string('processed_date')->nullable()->after('expiry_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('memberships', function (Blueprint $table) {
            //
        });
    }
};
