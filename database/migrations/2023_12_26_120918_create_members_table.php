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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // 會員姓名
            $table->string('username')->unique(); // 會員帳號，唯一性索引
            $table->string('password'); // 會員密碼
            $table->string('avatar')->nullable(); // 會員頭像路徑，可為空
            $table->integer('phone')->nullable(); // 會員電話，可為空
            $table->string('email')->unique(); // 會員Email，唯一性索引
            $table->text('address')->nullable(); // 會員地址，可為空
            $table->enum('status', ['active', 'inactive', 'deleted'])->default('active'); // 會員帳號狀態，預設為活動中
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
