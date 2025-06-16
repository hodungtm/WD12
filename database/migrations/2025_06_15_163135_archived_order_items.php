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
        Schema::create('archived_order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('discount_id')->nullable();

            // Thêm cột cho ID của Size và Color (có thể nullable nếu không phải sản phẩm nào cũng có size/color)
            $table->unsignedBigInteger('size_id')->nullable();
            $table->unsignedBigInteger('color_id')->nullable();

            $table->integer('quantity');
            $table->decimal('price', 10, 2);
            $table->decimal('discount_price', 10, 2)->nullable();
            $table->decimal('final_price', 10, 2)->nullable();
            $table->decimal('total_price', 15, 2);

            // Các cột mới để lưu trữ thông tin sản phẩm và biến thể tĩnh
            $table->string('product_name')->nullable();
            $table->string('product_sku')->nullable(); // Có thể lấy từ bảng product_variants
            $table->string('size_name')->nullable();    // Tên size tại thời điểm đặt hàng (vd: "M")
            $table->string('color_name')->nullable();   // Tên màu tại thời điểm đặt hàng (vd: "Đỏ")

            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('archived_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('archived_order_items');
    }
};