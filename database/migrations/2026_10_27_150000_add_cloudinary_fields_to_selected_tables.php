<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['training_centers', 'courses', 'teachers'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table): void {
                $table->string('imagen_url')->nullable();
                $table->string('imagen_public_id')->nullable();
            });
        }
    }

    public function down(): void
    {
        foreach (['training_centers', 'courses', 'teachers'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table): void {
                $table->dropColumn(['imagen_url', 'imagen_public_id']);
            });
        }
    }
};
