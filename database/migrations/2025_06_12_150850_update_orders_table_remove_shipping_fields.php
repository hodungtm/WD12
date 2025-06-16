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
        // $table->dropColumn('shipping_method');
        // $table->dropColumn('shipping_fee');

        // Thêm khoá ngoại
        // $table->unsignedBigInteger('shipping_method_id')->after('payment_method');

        // $table->foreign('shipping_method_id')->references('id')->on('shipping_methods')->nullOnDelete();

        //---------------------------------ta sửa tạm thịnh nhé có j lỗi bảo ta-----------------
$table->string('order_code')->unique()->nullable()->after('id'); // Tạm thời nullable 
          // Thêm cột shipping_method_id và cho phép null
            $table->unsignedBigInteger('shipping_method_id')->nullable()->after('payment_method');

            // Thêm khóa ngoại với hành vi nullOnDelete
           // Kiểm tra chỉ thêm foreign key nếu cột đã có
            $table->foreign('shipping_method_id')
                ->references('id')
                ->on('shipping_methods')
                ->nullOnDelete();
                });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Xóa foreign key trước
            $table->dropForeign(['shipping_method_id']);

            // Sau đó xóa cột
            $table->dropColumn('shipping_method_id');
        });
    }
};
