<?php
// .../database/migrations/..._add_change_fields_to_request_status_history_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('request_status_history', function (Blueprint $table) {
            // إضافة عمود هوية المستخدم الذي قام بالتغيير
            $table->foreignId('changed_by_user_id')->nullable()->constrained('users')->after('status');
            
            // إضافة عمود سبب التغيير اليدوي
            $table->string('reason')->nullable()->after('changed_by_user_id');
        });
    }

    public function down(): void
    {
        Schema::table('request_status_history', function (Blueprint $table) {
            $table->dropForeign(['changed_by_user_id']);
            $table->dropColumn(['changed_by_user_id', 'reason']);
        });
    }
};




















