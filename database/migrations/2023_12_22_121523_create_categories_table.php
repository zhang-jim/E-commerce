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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // 類別名稱
            $table->string('slug')->unique(); // URL 友好版本的類別名稱，並設定唯一性
            $table->text('description')->nullable(); // 類別描述，允許為空
            $table->unsignedBigInteger('parent_id')->nullable(); // 父類別 ID，允許為空
            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('cascade'); // 設定外部鍵關聯，並設定當父類別被刪除時，子類別也被刪除
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
