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
    Schema::create('order_details', function (Blueprint $table) {
        $table->id('order_detail_id');

        $table->unsignedBigInteger('order_id');
        $table->foreign('order_id')
              ->references('order_id')
              ->on('orders')
              ->onDelete('cascade');

        $table->unsignedBigInteger('plant_id');
        $table->foreign('plant_id')
              ->references('plant_id')
              ->on('plants')
              ->onDelete('cascade');

        $table->integer('quantity');
        $table->decimal('price', 10, 2);

        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
