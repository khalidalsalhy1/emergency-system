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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('full_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('password');
            $table->string('national_id')->nullable();
           // $table->softDeletes();
            // user roles: patient - hospital_admin - system_admin
            $table->enum('user_role', ['patient', 'hospital_admin', 'system_admin']);

            // hospital_id is nullable because only hospital admins need it
            $table->unsignedBigInteger('hospital_id')->nullable();
            //$table->foreign('hospital_id')->references('id')->on('hospitals')->onDelete('set null');

            $table->enum('status', ['active', 'blocked'])->default('active');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};