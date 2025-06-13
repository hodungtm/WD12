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
        Schema::table('order_items', function (Blueprint $table) {
            $table->unsignedBigInteger('discount_id')->nullable()->after('product_id');
            $table->decimal('discount_price', 10, 2)->nullable()->after('price');
            $table->decimal('final_price', 10, 2)->nullable()->after('discount_price');

            $table->foreign('discount_id')->references('id')->on('discounts')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['discount_id']);
            $table->dropColumn([
                'discount_id',
                'discount_price',
                'final_price',
            ]);
        });
    }
};
