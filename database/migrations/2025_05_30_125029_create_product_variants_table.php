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

            $table->float('foot_length')->nullable(); // chiều dài bàn chân (cm)
            $table->float('chest_size')->nullable();  // cỡ ngực (cm)
            $table->float('waist_size')->nullable();  // cỡ eo (cm)
            $table->float('hip_size')->nullable();    // cỡ hông (cm)

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_variants');
    }
};
