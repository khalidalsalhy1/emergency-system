<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('locations', function (Blueprint $table) {

            // إضافة العمود المرتبط بالمريض
            if (!Schema::hasColumn('locations', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
                $table->foreign('user_id')
                      ->references('id')->on('users')
                      ->onDelete('cascade')
                      ->onUpdate('cascade');
            }

            // إضافة العمود المرتبط بالمستشفى (اختياري)
            // احذفه إذا لا تريد ربط المستشفى
            if (!Schema::hasColumn('locations', 'hospital_id')) {
                $table->unsignedBigInteger('hospital_id')->nullable()->after('user_id');
                $table->foreign('hospital_id')
                      ->references('id')->on('hospitals')
                      ->onDelete('set null')
                      ->onUpdate('cascade');
            }

        });
    }

    public function down(): void
    {
        Schema::table('locations', function (Blueprint $table) {

            // إزالة العلاقات قبل حذف الأعمدة
            $table->dropForeign(['user_id']);
            $table->dropForeign(['hospital_id']);

            // حذف الأعمدة
            $table->dropColumn(['user_id', 'hospital_id']);
        });
    }
};