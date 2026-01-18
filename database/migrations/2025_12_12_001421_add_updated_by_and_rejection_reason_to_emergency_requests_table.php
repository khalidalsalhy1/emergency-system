<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('emergency_requests', function (Blueprint $table) {
            // المفتاح الخارجي للمستخدم الذي قام بآخر تحديث (مسؤول المستشفى)
            $table->foreignId('updated_by')->nullable()->after('hospital_id')->constrained('users')->onDelete('set null');
            
            // إضافة حقل اختياري لسبب الرفض (التحسين رقم 10)
            $table->text('rejection_reason')->nullable()->after('updated_by');
        });
    }

    public function down(): void
    {
        Schema::table('emergency_requests', function (Blueprint $table) {
            $table->dropForeign(['updated_by']);
            $table->dropColumn('updated_by');
            $table->dropColumn('rejection_reason');
        });
    }
};
