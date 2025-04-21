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
            $table->unsignedBigInteger('order_id');
            $table->string('payment_gateway'); // Cổng thanh toán (PayPal, Momo, VNPay...)
            $table->string('transaction_id')->unique(); // Mã giao dịch
            $table->decimal('amount', 12, 2); // Số tiền thanh toán
            $table->enum('status', ['pending', 'success', 'failed'])->default('pending'); // Trạng thái thanh toán
            $table->timestamps();
        
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
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
