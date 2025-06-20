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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->text('attribute_text');
            $table->string('sku')->unique();
            $table->decimal('price', 10, 2);
            $table->integer('quantity')->default(0); // Số lượng tồn kho
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->string('stock_status')->default('còn hàng'); // hoặc dùng ENUM

            $table->text('description')->nullable();
            $table->string('image')->nullable(); // đường dẫn ảnh

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
