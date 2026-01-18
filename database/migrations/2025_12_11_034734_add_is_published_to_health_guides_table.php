<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('health_guides', function (Blueprint $table) {
            // إضافة عمود is_published كقيمة منطقية (Boolean)، بقيمة افتراضية FALSE (غير منشور)
            $table->boolean('is_published')->default(false)->after('content'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('health_guides', function (Blueprint $table) {
            // حذف العمود عند التراجع عن الهجرة
            $table->dropColumn('is_published');
        });
    }
};
