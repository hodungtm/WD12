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
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->date('order_date')->nullable();
    $table->decimal('total_price', 15, 2)->default(0);
    $table->enum('status', ['Đang chờ', 'Đang giao hàng', 'Hoàn thành', 'Đã hủy'])->default('Đang chờ');
    $table->enum('payment_status', ['Chờ thanh toán', 'Đã thanh toán'])->default('Chờ thanh toán');
    $table->enum('payment_method', ['Tiền mặt', 'Chuyển khoản'])->nullable();
    $table->text('note')->nullable();
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
