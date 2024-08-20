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
        Schema::create('cpds', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('topic');
            $table->text('content');
            $table->string('hours');
            $table->text('target_group');
            $table->string('location');
            $table->string('start_date');
            $table->string('end_date');
            $table->string('normal_rate');
            $table->string('members_rate');
            $table->string('resource');
            $table->enum('status',['Active','Inactive']);
            $table->enum('type', ['Free', 'Paid'])->nullable()->default('Paid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cpds');
    }
};
