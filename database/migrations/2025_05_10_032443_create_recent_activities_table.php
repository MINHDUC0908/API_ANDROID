<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('recent_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('activity_type');
            $table->text('activity_data'); // Lưu thông tin hoạt động (có thể là JSON)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('recent_activities');
    }

};
