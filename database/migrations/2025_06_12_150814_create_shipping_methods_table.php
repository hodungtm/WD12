<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
 public function up()
{
    Schema::create('shipping_methods', function (Blueprint $table) {
        $table->id();
        $table->string('name'); // Ví dụ: Giao hàng tiêu chuẩn
        $table->decimal('fee', 10, 2)->default(0); // Phí vận chuyển
        $table->text('description')->nullable(); // Mô tả tùy chọn
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_methods');
    }
};
