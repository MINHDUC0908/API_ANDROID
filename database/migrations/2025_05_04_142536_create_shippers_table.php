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
        Schema::create('shippers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('vehicle_number')->nullable(); // Biển số xe
            $table->enum('status', ['available', 'busy', 'offline'])->default('offline');
            $table->decimal('latitude', 10, 7)->nullable(); // Vị trí hiện tại
            $table->decimal('longitude', 10, 7)->nullable();
            $table->timestamp('last_online')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shippers');
    }
};
