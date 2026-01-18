<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('emergency_requests', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('injury_type_id');
            $table->unsignedBigInteger('location_id');
            $table->unsignedBigInteger('hospital_id')->nullable();

            $table->text('description')->nullable();

            $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending');
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();
        });

        Schema::table('emergency_requests', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')
                  ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('injury_type_id')->references('id')->on('injury_types')
                  ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('location_id')->references('id')->on('locations')
                  ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('hospital_id')->references('id')->on('hospitals')
                  ->nullOnDelete()->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('emergency_requests', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['injury_type_id']);
            $table->dropForeign(['location_id']);
            $table->dropForeign(['hospital_id']);
        });

        Schema::dropIfExists('emergency_requests');
    }
};