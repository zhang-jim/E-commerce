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
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique();
            $table->integer('phone')->nullable(); // 會員電話，可為空
            $table->text('address')->nullable(); // 會員地址，可為空
            $table->enum('status', ['active', 'inactive', 'deleted'])->default('active'); // 會員帳號狀態，預設為活動中
            $table->string('rule')->default('member');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
