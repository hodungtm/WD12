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
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('discount_id')->nullable()->after('receiver_id'); // Khóa ngoại đến bảng discounts
            $table->decimal('discount_amount', 10, 2)->nullable()->after('total_price'); // Số tiền giảm thực tế
            $table->decimal('final_amount', 15, 2)->default(0.00)->after('discount_amount'); // Tổng tiền sau giảm
            // $table->string('shipping_method', 100)->nullable()->after('payment_method');
            // $table->decimal('shipping_fee', 10, 2)->nullable()->after('shipping_method');

            $table->foreign('discount_id')->references('id')->on('discounts')->nullOnDelete();
        });
    }
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['discount_id']);
            $table->dropColumn([
                'discount_id',
                'discount_amount',
                'final_amount',
                // 'shipping_method',
                // 'shipping_fee',
            ]);
        });
    }
};
