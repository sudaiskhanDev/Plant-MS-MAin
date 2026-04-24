<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id('payment_id');

            // 🔥 RELATION FIX (VERY IMPORTANT)
            $table->unsignedBigInteger('order_id');

            $table->decimal('amount', 10, 2);

            $table->string('payment_method'); // cod / card

            $table->string('stripe_payment_id')->nullable();

            $table->string('payment_status')->default('pending');
            // pending / completed / failed

            $table->timestamp('payment_date')->nullable();

            $table->timestamps();

            // 🔥 FOREIGN KEY
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


// use Illuminate\Database\Migrations\Migration;
// use Illuminate\Database\Schema\Blueprint;
// use Illuminate\Support\Facades\Schema;

// return new class extends Migration
// {
//     public function up(): void
//     {
//         Schema::create('payments', function (Blueprint $table) {
//             $table->id('payment_id');

//             $table->unsignedBigInteger('order_id');

//             $table->decimal('amount', 10, 2);
//             $table->date('payment_date');

//             $table->string('payment_method');
//             $table->string('payment_status');

//             $table->timestamps();
//         });
//     }

//     public function down(): void
//     {
//         Schema::dropIfExists('payments');
//     }
// };