<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductVariantsTable extends Migration
{
    public function up()
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');

            $table->string('size')->nullable();       // size quần áo hoặc giày
            $table->string('color')->nullable();      // màu sắc
            $table->integer('quantity')->default(0);  // số lượng
            $table->decimal('variant_price', 15, 2)->default(0);       // giá biến thể
            $table->decimal('variant_sale_price', 15, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_variants');
    }
};
 