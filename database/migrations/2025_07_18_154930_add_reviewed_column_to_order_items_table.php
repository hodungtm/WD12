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
       Schema::table('order_items', function (Blueprint $table) {
           $table->boolean('reviewed')->default(false)->after('quantity');
       });
   }
   public function down()
   {
       Schema::table('order_items', function (Blueprint $table) {
           $table->dropColumn('reviewed');
       });
   }
};
