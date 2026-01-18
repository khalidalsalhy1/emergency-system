<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('system_logs', function (Blueprint $table) {
            // إضافة عمود type (بعنوان 50 حرف وقيمة افتراضية)
            $table->string('type', 50)->default('ADMIN_ACTION')->after('action');
        });
    }

    public function down(): void
    {
        Schema::table('system_logs', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
