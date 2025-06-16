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
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->integer('price'); // Giá sản phẩm
            $table->integer('quantity')->default(0);
            $table->string('image')->nullable();
            $table->foreignId('brand_id')->constrained()->onDelete('cascade'); // Thương hiệu
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
