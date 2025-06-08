<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeletedAtToDiscountsTable extends Migration
{
    public function up()
    {
        Schema::table('discounts', function (Blueprint $table) {
            $table->softDeletes(); 
        });
    }

    public function down()
    {
        Schema::table('discounts', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
