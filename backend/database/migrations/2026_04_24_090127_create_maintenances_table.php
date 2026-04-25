<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenances', function (Blueprint $table) {
    $table->id('maintenance_id');

    $table->unsignedBigInteger('plant_id');
    $table->unsignedBigInteger('admin_staff_id'); // 🔥 NEW

    $table->string('task_type');
    $table->date('scheduled_date');
    $table->string('status');

    $table->timestamps();

    // 🔗 Foreign Keys (recommended)
    $table->foreign('plant_id')
          ->references('plant_id')
          ->on('plants')
          ->onDelete('cascade');

    $table->foreign('admin_staff_id')
          ->references('admin_staff_id')
          ->on('admin_staff')
          ->onDelete('cascade');
});
        // Schema::create('maintenances', function (Blueprint $table) {
        //     $table->id('maintenance_id');

        //     $table->unsignedBigInteger('plant_id');

        //     $table->string('task_type'); // watering / pruning / fertilization
        //     $table->date('scheduled_date');
        //     $table->string('status');
            

        //     $table->timestamps();
        // });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenances');
    }
};