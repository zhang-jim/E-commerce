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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique(); //訂單編號 unique設定唯一值
            $table->foreignId('member_id') //關聯會員
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->integer('order_amount'); //訂單金額
            $table->enum('payment_status', ['unpaid','paid','payment-fail','refunding','refunded']);
            $table->enum('order_status', ['confirmed', 'processing', 'completed', 'cancelled']);
            $table->enum('cargo_status', ['in-stock', 'shipped', 'arrived', 'picked-up','returned','returning']);
            $table->text('notes')->nullable(); // 允許備註為空
            $table->timestamps();
        });
        Schema::create('order_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id') //關聯訂單
            ->constrained()
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->foreignId('product_id') //關聯商品
            ->constrained()
            ->onUpdate('cascade')
            ->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
