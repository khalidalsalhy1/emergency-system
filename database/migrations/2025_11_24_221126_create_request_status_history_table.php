<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('request_status_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('emergency_request_id');
            $table->enum('status', ['pending', 'in_progress', 'completed']);
            $table->timestamp('changed_at')->nullable();
            $table->timestamps();
        });

        Schema::table('request_status_history', function (Blueprint $table) {
            $table->foreign('emergency_request_id')
                ->references('id')->on('emergency_requests')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('request_status_history', function (Blueprint $table) {
            $table->dropForeign(['emergency_request_id']);
        });

        Schema::dropIfExists('request_status_history');
    }
};