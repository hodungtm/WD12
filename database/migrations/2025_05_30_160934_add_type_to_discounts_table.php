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
    Schema::table('discounts', function (Blueprint $table) {
        $table->enum('type', ['order', 'product', 'shipping'])->default('order')->after('min_order_amount');
    });
}

public function down()
{
    Schema::table('discounts', function (Blueprint $table) {
        $table->dropColumn('type');
    });
}
};
