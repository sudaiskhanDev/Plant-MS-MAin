<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('maintenance', function (Blueprint $table) {
            $table->id('maintenance_id');

            $table->unsignedBigInteger('plant_id');

            $table->string('task_type'); // watering, pruning, fertilization
            $table->date('scheduled_date');
            $table->string('status'); // pending, completed

            $table->timestamps();

            // FK
            $table->foreign('plant_id')
                  ->references('plant_id')
                  ->on('plants')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance');
    }
};