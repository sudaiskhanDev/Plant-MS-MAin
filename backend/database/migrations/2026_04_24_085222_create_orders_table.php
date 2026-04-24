<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id('order_id');

            $table->unsignedBigInteger('user_id');

            // 📅 order info
            $table->dateTime('order_date');
            $table->string('status')->default('pending');

            // 💰 payment info (summary)
            $table->decimal('total_amount', 10, 2);
            $table->string('payment_method'); // cod | card

            // 📦 shipping snapshot (IMPORTANT ADDITIONS)
            $table->string('name');
            $table->string('phone');
            $table->string('city');
            $table->string('zip')->nullable();
            $table->text('shipping_address');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
// use Illuminate\Database\Migrations\Migration;
// use Illuminate\Database\Schema\Blueprint;
// use Illuminate\Support\Facades\Schema;

// return new class extends Migration
// {
//     public function up(): void
//     {
//         Schema::create('orders', function (Blueprint $table) {
//             $table->id('order_id');

//             $table->date('order_date');
//             $table->decimal('total_amount', 10, 2);
//             $table->string('status');

//             $table->unsignedBigInteger('user_id');

//             $table->timestamps();
//         });
//     }

//     public function down(): void
//     {
//         Schema::dropIfExists('orders');
//     }
// };