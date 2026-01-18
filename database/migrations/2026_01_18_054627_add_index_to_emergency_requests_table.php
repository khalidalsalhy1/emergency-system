<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('emergency_requests', function (Blueprint $table) {
            // إضافة فهرس مركب يسرع البحث عن المستشفى والحالة معاً
            $table->index(['hospital_id', 'status'], 'hospital_status_index');
        });
    }

    public function down(): void
    {
        Schema::table('emergency_requests', function (Blueprint $table) {
            // حذف الفهرس في حال أردت التراجع عن الخطوة
            $table->dropIndex('hospital_status_index');
        });
    }
};
