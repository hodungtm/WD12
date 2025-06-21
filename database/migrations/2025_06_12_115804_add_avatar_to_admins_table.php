<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Laravel\Prompts\table;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
<<<<<<<< HEAD:database/migrations/2025_06_18_041229_create_sizes_table.php
        Schema::create('sizes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();

========
        Schema::table('admins', function (Blueprint $table) {
             $table->string('avatar')->nullable()->after('email'); // hoặc sau name
>>>>>>>> main:database/migrations/2025_06_12_115804_add_avatar_to_admins_table.php
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            //
        });
    }
};
