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
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // اسم الشيفت (صباحي - مسائي - ليلي)
            $table->time('start_time'); // وقت بدء الشيفت
            $table->time('end_time'); // وقت انتهاء الشيفت
            $table->boolean('is_rotational')->default(false); // هل الشيفت دوار؟
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};
