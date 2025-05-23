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
        Schema::create('delivery_status_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_delivery_id');
            $table->string('status');
            $table->text('notes')->nullable();
            $table->string('image')->nullable(); // hình ảnh (nếu có)
            $table->unsignedBigInteger('updated_by');
            $table->foreign('order_delivery_id')->references('id')->on('order_deliveries')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_status_histories');
    }
};
