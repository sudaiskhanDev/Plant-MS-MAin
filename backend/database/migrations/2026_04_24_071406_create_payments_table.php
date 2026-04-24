<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id('payment_id');

            $table->unsignedBigInteger('order_id');
            $table->decimal('amount', 10, 2);

            $table->dateTime('payment_date')->nullable();
            $table->string('payment_method'); // cash, card, jazzcash etc
            $table->string('payment_status'); // pending, paid, failed

            $table->timestamps();

            // FK
            $table->foreign('order_id')
                  ->references('order_id')
                  ->on('orders')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};