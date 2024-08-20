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
        Schema::create('memberships', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->timestamp('approval_date')->nullable();
            $table->timestamp('denial_date')->nullable();
            $table->timestamp('expiry_date')->nullable();
            $table->string('payment_amount')->nullable();
            $table->string('status');
            $table->string('approval_reason')->nullable();
            $table->integer('approved_by')->nullable();
            $table->integer('denied_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('memberships');
    }
};
