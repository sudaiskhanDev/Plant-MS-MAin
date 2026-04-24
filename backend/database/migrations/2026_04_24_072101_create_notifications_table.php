<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id('notification_id');

            $table->unsignedBigInteger('admin_staff_id');

            $table->text('message');
            $table->string('type'); 
            // stock, maintenance, order

            $table->dateTime('date')->default(now());

            $table->timestamps();

            // FK
            $table->foreign('admin_staff_id')
                  ->references('admin_staff_id')
                  ->on('admin_staff')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};