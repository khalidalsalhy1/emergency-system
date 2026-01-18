<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // 🚨 إضافة العمود المطلوب:
            $table->softDeletes(); 
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // لإلغاء الترحيل:
            $table->dropSoftDeletes();
        });
    }
};
