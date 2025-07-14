<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCategoriesTableRemoveMaDanhMucAddMoTa extends Migration
{
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            // Xóa cột ma_danh_muc nếu tồn tại
            if (Schema::hasColumn('categories', 'ma_danh_muc')) {
                $table->dropColumn('ma_danh_muc');
            }

            // Thêm cột mo_ta kiểu text, cho phép null
            if (!Schema::hasColumn('categories', 'mo_ta')) {
                $table->text('mo_ta')->nullable()->after('ten_danh_muc');
            }
        });
    }

    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            // Thêm lại cột ma_danh_muc kiểu string (bạn chỉnh lại độ dài nếu cần)
            if (!Schema::hasColumn('categories', 'ma_danh_muc')) {
                $table->string('ma_danh_muc')->after('id');
            }

            // Xóa cột mo_ta
            if (Schema::hasColumn('categories', 'mo_ta')) {
                $table->dropColumn('mo_ta');
            }
        });
    }
}
