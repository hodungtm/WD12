<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // Khách đặt đơn
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Mã đơn hàng
            $table->string('order_code')->unique();

            // Ngày tạo đơn
            $table->date('order_date')->nullable();

            // Phương thức vận chuyển
            $table->foreignId('shipping_method_id')->constrained()->nullable();

            // Thông tin người nhận (snapshot)
            $table->string('receiver_name');
            $table->string('receiver_phone');
            $table->string('receiver_email')->nullable();
            $table->string('receiver_address');

            // Tổng tiền các sản phẩm
            $table->decimal('total_price', 15, 2)->default(0);

            // Mã giảm giá (snapshot)
            $table->string('discount_code')->nullable();
            $table->unsignedInteger('discount_amount')->default(0);

            // Tổng tiền sau giảm giá + ship
            $table->decimal('final_amount', 15, 2)->default(0);

            // Trạng thái đơn
            $table->enum('status', ['Đang chờ', 'Đang giao hàng', 'Hoàn thành', 'Đã hủy'])->default('Đang chờ');

            // Trạng thái thanh toán
            $table->enum('payment_status', ['Chờ thanh toán', 'Đã thanh toán'])->default('Chờ thanh toán');
            $table->enum('payment_method', ['Tiền mặt', 'Chuyển khoản'])->nullable();

            // Ghi chú
            $table->text('note')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
