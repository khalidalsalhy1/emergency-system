<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disease_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('disease_id');
            $table->timestamps();
        });

        Schema::table('disease_user', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')
                  ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('disease_id')->references('id')->on('diseases')
                  ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('disease_user', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['disease_id']);
        });

        Schema::dropIfExists('disease_user');
    }
};