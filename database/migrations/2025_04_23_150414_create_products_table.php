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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Tên sản phẩm
            $table->text('description'); // Mô tả sản phẩm
            $table->integer('rating')->default(0); // Đánh giá
            $table->foreignId('category_id')          // Khóa ngoại đến bảng categories
                  ->constrained('categories')       // Liên kết với bảng categories
                  ->onDelete('cascade');           // Khi xóa category sẽ xóa tất cả sản phẩm liên quan
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->text('description')->nullable(false)->change(); // Đảm bảo không null nếu quay lại
        });
    }
};
