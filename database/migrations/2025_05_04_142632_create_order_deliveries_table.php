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
        Schema::create('order_deliveries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('shipper_id')->nullable();
            $table->enum('delivery_status', [
                'pending', // chờ shipper nhận
                'accepted', // shipper đã nhận đơn
                'picked_up', // đã lấy hàng
                'in_transit', // đang giao hàng
                'delivered', // đã giao hàng
                'failed' // giao hàng thất bại
            ])->default('pending');
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('picked_up_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->text('delivery_notes')->nullable();
            $table->string('proof_of_delivery')->nullable(); // hình ảnh chứng minh đã giao hàng
            $table->string('customer_signature')->nullable(); // chữ ký khách hàng
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('shipper_id')->references('id')->on('shippers')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_deliveries');
    }
};
