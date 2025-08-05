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
        Schema::table('product_images', function (Blueprint $table) {
            // Thêm cột color_id, có thể là null nếu ảnh đó không liên quan đến màu nào
            $table->unsignedBigInteger('color_id')->nullable()->after('product_id');
            // Thiết lập khóa ngoại để đảm bảo tính toàn vẹn dữ liệu
            $table->foreign('color_id')->references('id')->on('colors')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('product_images', function (Blueprint $table) {
            $table->dropForeign(['color_id']);
            $table->dropColumn('color_id');
        });
    }
};
