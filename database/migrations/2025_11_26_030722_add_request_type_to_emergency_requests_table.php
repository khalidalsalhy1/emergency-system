<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('emergency_requests', function (Blueprint $table) {

            // إضافة نوع الطلب (طلب إسعاف / بلاغ)
            if (!Schema::hasColumn('emergency_requests', 'request_type')) {
                $table->enum('request_type', ['DISPATCH', 'NOTIFY'])
                      ->default('DISPATCH')
                      ->after('user_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('emergency_requests', function (Blueprint $table) {
            $table->dropColumn('request_type');
        });
    }
};