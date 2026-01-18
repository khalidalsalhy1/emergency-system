<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            // إضافة علاقة مدير المستشفى -> المستشفى
            if (!Schema::hasColumn('users', 'hospital_id')) {
                $table->unsignedBigInteger('hospital_id')->nullable()->after('user_role');
            }

            // إضافة المفتاح الأجنبي
            $table->foreign('hospital_id')
                  ->references('id')->on('hospitals')
                  ->onDelete('set null')
                  ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['hospital_id']);
            $table->dropColumn('hospital_id');
        });
    }
};