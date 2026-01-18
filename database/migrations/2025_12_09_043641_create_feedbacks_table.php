<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            
            // 1. ربط الطلب (العلاقة الرئيسية)
            $table->foreignId('emergency_request_id')->constrained('emergency_requests')->onDelete('cascade');
            // التأكد من أن التقييم فريد لكل طلب
            $table->unique('emergency_request_id'); 

            // 2. ربط المستخدم والمستشفى (لتسهيل التقارير)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('hospital_id')->constrained('hospitals')->onDelete('cascade');

            // 3. بيانات التقييم
            $table->tinyInteger('rating')->comment('Rating from 1 to 5 stars'); 
            $table->text('comments')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feedbacks');
    }
};
