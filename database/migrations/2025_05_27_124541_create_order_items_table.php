<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            // Khóa ngoại
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_variant_id')->constrained()->onDelete('cascade');

            // Giá & số lượng
            $table->integer('quantity');
            $table->decimal('price', 15, 2)->default(0);
            $table->decimal('total_price', 15, 2)->default(0);

            // Snapshot thông tin sản phẩm
            $table->string('product_name');
            $table->string('variant_name')->nullable(); // VD: Size M - Màu đỏ
            $table->string('product_image')->nullable(); // đường dẫn ảnh
            $table->string('sku')->nullable();
            $table->string('brand_name')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
