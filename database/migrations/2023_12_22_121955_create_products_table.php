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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name'); //商品名稱
            $table->text('description'); //商品描述
            $table->integer('price'); //商品售價
            $table->integer('inventory')->default(0); //商品庫存
            $table->string('image'); //商品圖片
            $table->foreignId('categories_id') //關聯類別
                ->nullable()
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->boolean('is_published')->default(false); //上架狀態
            $table->integer('sales_quantity')->default(0); //銷售數量
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
