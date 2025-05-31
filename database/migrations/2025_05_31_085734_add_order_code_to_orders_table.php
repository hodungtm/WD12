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
        Schema::table('orders', function (Blueprint $table) {
            // Thêm cột order_code, đảm bảo nó là UNIQUE và có thể NULL ban đầu hoặc NOT NULL
            $table->string('order_code', 50)->unique()->after('id'); // Hoặc sau cột nào đó bạn muốn
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('order_code');
        });
    }
};