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
        Schema::table('reviews', function (Blueprint $table) {
            $table->renameColumn('ma_san_pham', 'product_id');
        });
    }

    public function down()
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->renameColumn('product_id', 'ma_san_pham');
        });
    }
};
