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
    Schema::table('orders', function (Blueprint $table) {
        // Xóa các cột cũ
        $table->dropColumn('shipping_method');
        $table->dropColumn('shipping_fee');

        // Thêm khoá ngoại
        $table->unsignedBigInteger('shipping_method_id')->after('payment_method');

        $table->foreign('shipping_method_id')->references('id')->on('shipping_methods')->nullOnDelete();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        
    }
};
