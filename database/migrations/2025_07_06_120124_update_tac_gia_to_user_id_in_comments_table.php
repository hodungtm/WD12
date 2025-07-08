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
        Schema::table('comments', function (Blueprint $table) {
            // Đổi kiểu dữ liệu tac_gia sang unsignedBigInteger
            $table->unsignedBigInteger('tac_gia')->change();
            // Thêm khóa ngoại liên kết với users(id)
            $table->foreign('tac_gia')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            // Xóa khóa ngoại
            $table->dropForeign(['tac_gia']);
            // Đổi lại kiểu dữ liệu tac_gia về string
            $table->string('tac_gia')->change();
        });
    }
};
