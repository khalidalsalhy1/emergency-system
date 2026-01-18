<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * تشغيل الهجرات.
     */
    public function up(): void
    {
        // 🚨 نقوم بتعديل عمود 'status' في جدول 'request_status_history'
        Schema::table('request_status_history', function (Blueprint $table) {
            
            // الحل المقترح (الأكثر أماناً ومرونة): تغيير النوع إلى VARCHAR بطول كافٍ
            // هذا يسمح بسهولة إضافة حالات مستقبلية دون تعديل الهجرة مجدداً.
            // 50 حرف كافية جداً.
            $table->string('status', 50)->change();
            
            /*
            // البديل (إذا كنت تفضل استخدام ENUM):
            // $table->enum('status', ['pending', 'in_progress', 'completed', 'canceled'])->change();
            */
        });
    }

    /**
     * عكس الهجرات.
     */
    public function down(): void
    {
        // 🚨 عند عكس الهجرة، نقوم بالرجوع إلى حالة أكثر تقييداً أو القيمة السابقة
        Schema::table('request_status_history', function (Blueprint $table) {
            
            // الرجوع إلى ENUM (إذا كان هذا هو الأصل)
            $table->enum('status', ['pending', 'in_progress', 'completed'])->change();
            
            // أو الرجوع إلى طول VARCHAR الأصلي (إذا كان هذا هو الأصل)
            // $table->string('status', 20)->change(); 
        });
    }
};
