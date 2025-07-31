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
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('description')->nullable();
            $table->decimal('discount_amount', 8, 2)->nullable(); // Số tiền giảm
            $table->integer('discount_percent')->nullable(); // Hoặc phần trăm giảm
            $table->date('start_date');
            $table->date('end_date');
            $table->unsignedInteger('used')->default(0); // Số lần đã sử dụng
            $table->integer('max_usage')->nullable(); // Số lượt sử dụng tối đa
            $table->decimal('min_order_amount', 10, 2)->nullable(); // Điều kiện đơn hàng tối thiểu
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
