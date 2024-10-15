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
        Schema::create('invoice_payments', function (Blueprint $table) {
            $table->id();
            $table->integer('invoice_id');
            $table->date('date');
            $table->decimal('amount', 16)->default(0);
            $table->integer('account_id')->default(0);
            $table->integer('payment_method')->default(0);
            $table->string('receipt')->nullable();
            $table->string('payment_type')->default('Manually');
            $table->string('txn_id')->nullable();
            $table->string('currency')->nullable();
            $table->string('order_id')->nullable();
            $table->string('reference')->nullable();
            $table->string('add_receipt')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_payments');
    }
};
