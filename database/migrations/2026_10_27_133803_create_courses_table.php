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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('course_number');
            $table->string('day');

            $table->unsignedBigInteger('area_id');
            $table->foreign('area_id')
            ->references('id')
            ->on('areas');

            $table->unsignedBigInteger('training_center_id');
            $table->foreign('training_center_id')
            ->references('id')
            ->on('training_centers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
