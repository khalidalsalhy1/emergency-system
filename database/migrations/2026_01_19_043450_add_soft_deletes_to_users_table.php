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
        Schema::table('users', function (Blueprint $blueprint) {
            // هذا السطر يضيف عمود deleted_at الذي يطلبه الخطأ في الصورة
            $blueprint->softDeletes(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $blueprint) {
            // لحذف العمود في حال تراجعت عن الـ migration
            $blueprint->dropSoftDeletes();
        });
    }
};
