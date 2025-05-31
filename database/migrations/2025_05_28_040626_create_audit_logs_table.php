<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->string('action'); // ví dụ: create, update, delete
            $table->string('target_type'); // ví dụ: Admin, Product, Role...
            $table->unsignedBigInteger('target_id')->nullable(); // id của đối tượng bị tác động
            $table->text('description')->nullable(); // mô tả cụ thể
            $table->ipAddress('ip_address')->nullable();
            $table->timestamps();

            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
