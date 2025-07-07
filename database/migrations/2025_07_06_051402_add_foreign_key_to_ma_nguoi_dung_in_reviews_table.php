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
        $table->foreign('ma_nguoi_dung')
            ->references('id')
            ->on('users')
            ->onDelete('cascade'); // hoặc set null nếu muốn
    });
}

public function down()
{
    Schema::table('reviews', function (Blueprint $table) {
        $table->dropForeign(['ma_nguoi_dung']);
    });
}
};
