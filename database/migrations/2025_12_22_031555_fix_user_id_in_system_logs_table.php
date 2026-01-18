<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('system_logs', function (Blueprint $table) {
            // 1. حذف الأعمدة الخاطئة إملائياً
            $table->dropColumn(['usre_id', 'ip_addrress']); 
            
            // 2. إضافة الأعمدة الصحيحة إملائياً
            // العمود الذي يسبب الخطأ: user_id
            $table->unsignedBigInteger('user_id')->nullable()->after('id');
            
            // تصحيح العمود ip_address
            $table->string('ip_address')->nullable(); 
        });
    }

    public function down(): void
    {
        Schema::table('system_logs', function (Blueprint $table) {
            // لإلغاء التعديلات، نقوم بعكس العملية
            $table->dropColumn(['user_id', 'ip_address']);
            
            // وإعادة الأعمدة القديمة (usre_id و ip_addrress) إذا كنت تحتاجها لاحقاً
            $table->unsignedBigInteger('usre_id')->nullable()->after('id');
            $table->string('ip_addrress')->nullable();
        });
    }
};
