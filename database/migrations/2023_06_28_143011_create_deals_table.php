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
        Schema::create('deals', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->double('price', 8, 2)->nullable();
            $table->integer('pipeline_id');
            $table->integer('stage_id');
            $table->string('sources')->nullable();
            $table->string('products')->nullable();
            $table->text('notes')->nullable();
            $table->string('labels')->nullable();
            $table->string('status')->nullable();
            $table->integer('order')->default(0);
            $table->integer('created_by');
            $table->integer('is_active')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deals');
    }
};
