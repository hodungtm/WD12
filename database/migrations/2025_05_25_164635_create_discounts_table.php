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
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('description')->nullable();
            $table->decimal('discount_amount', 8, 2)->nullable();
            $table->integer('discount_percent')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('max_usage')->nullable();
            $table->decimal('min_order_amount', 10, 2)->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('role_required')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
