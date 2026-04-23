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
        Schema::create('admin_staff', function (Blueprint $table) {
    $table->id('admin_staff_id');
    $table->string('name');
    $table->string('email')->unique();
    $table->string('password');
    $table->enum('role', ['Admin', 'Staff'])->default('Staff');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_staff');
    }
};
