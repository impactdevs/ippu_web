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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->integer('user_id');
            $table->string('amount');
            $table->string('balance');
            $table->integer('membership_id')->nullable();
            $table->integer('cpd_id')->nullable();
            $table->integer('event_id')->nullable();
            $table->integer('received_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
