<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id(); // ID
            $table->unsignedBigInteger('product_id')->nullable(); // Sản phẩm
            $table->string('tac_gia'); // Tác giả
            $table->text('noi_dung'); // Nội dung
            $table->boolean('trang_thai')->default(0); // Trạng thái: 0 = Ẩn, 1 = Hiển thị
            $table->timestamps();

            // Khóa ngoại nếu có bảng products
            $table->foreign('product_id')->references('id')->on('products')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
}
