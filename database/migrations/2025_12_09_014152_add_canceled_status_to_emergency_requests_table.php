<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * تشغيل الهجرة (Run the migrations).
     */
    public function up(): void
    {
        // 🚀 تعديل عمود 'status' لإضافة القيمة 'canceled' إلى خيارات ENUM
        // ملاحظة: يجب أن يتضمن هذا الأمر جميع الحالات الحالية لديك: new, pending, in_progress, completed
        DB::statement("
            ALTER TABLE emergency_requests MODIFY status 
            ENUM('new', 'pending', 'in_progress', 'completed', 'canceled') 
            DEFAULT 'new' NOT NULL
        ");
    }

    /**
     * التراجع عن الهجرة (Reverse the migrations).
     */
    public function down(): void
    {
        // 🗑️ إزالة قيمة 'canceled' من خيارات ENUM
        // ملاحظة: هذا سيفشل إذا كانت هناك سجلات حالتها 'canceled' في قاعدة البيانات.
        DB::statement("
            ALTER TABLE emergency_requests MODIFY status 
            ENUM('new', 'pending', 'in_progress', 'completed') 
            DEFAULT 'new' NOT NULL
        ");
    }
};
