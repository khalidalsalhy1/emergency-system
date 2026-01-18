<?php

use App\Http\Controllers\AuthController;
// use App\Http\Controllers\EmergencyRequestController; 

use App\Http\Controllers\NotificationController; 
use App\Http\Controllers\LocationController; 
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\PatientProfileController;

// 🚨 متحكمات المريض بعد الفصل:
use App\Http\Controllers\PatientInjuryTypeController; 
use App\Http\Controllers\PatientHealthGuideController; 
use App\Http\Controllers\PatientFeedbackController; 

// 🚨🚨 تم حذف الاستيراد القديم لمتحكم مصادقة المستشفى (سيستخدم الاستيراد الجديد أدناه)
// use App\Http\Controllers\HospitalAdminAuthController;

// 🚨🚨 تم حذف الاستيراد القديم لمتحكم الحساب الشخصي
// use App\Http\Controllers\HospitalAdminController;    

use App\Http\Controllers\Admin\HospitalAdminManagementController; 
use App\Http\Controllers\Admin\PatientController; 

// 🚨🚨 الاستيراد الجديد لمتحكم مصادقة المدير
use App\Http\Controllers\Admin\AuthController as AdminAuthController; 

// 🚨🚨 الاستيراد الجديد لمتحكم إدارة المستشفيات
use App\Http\Controllers\Admin\HospitalController as AdminHospitalController; 

// 🚨🚨 الاستيراد الجديد لمتحكم الحساب الشخصي لمسؤول المستشفى
use App\Http\Controllers\HospitalAdmin\ProfileController as HospitalAdminProfileController; 

// 🚨🚨 الاستيراد الجديد لمتحكم مصادقة المستشفى (من موقعه الجديد)
use App\Http\Controllers\HospitalAdmin\AuthController as HospitalAdminAuthController; 

// 🚨 متحكمات المدير بعد الفصل:
use App\Http\Controllers\Admin\EmergencyRequestController as AdminEmergencyRequestController; 
use App\Http\Controllers\Admin\InjuryTypeController as AdminInjuryTypeController; 
use App\Http\Controllers\Admin\HealthGuideController as AdminHealthGuideController; 
use App\Http\Controllers\Admin\FeedbackController as AdminFeedbackController; 

// 🚨🚨 إضافة الاستيراد لمتحكم طلبات الطوارئ لمسؤول المستشفى
use App\Http\Controllers\HospitalAdmin\EmergencyRequestController as HospitalAdminEmergencyRequestController; 

use Illuminate\Support\Facades\Route;


// ----------------------------------------------------------------
// 🥇 مجموعة المسارات الخارجية للمريض (Prefix: /api/patient)
// ----------------------------------------------------------------
Route::prefix('patient')
    ->middleware('api') 
    ->group(function () {
        
        // مسارات التوثيق (Authentication)
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/password/request-otp', [AuthController::class, 'requestPasswordReset']);
        Route::post('/password/reset', [AuthController::class, 'resetPasswordWithOtp']);
        
        Route::middleware('auth:sanctum')->group(function () {
            
            Route::post('/logout', [AuthController::class, 'logout']); 
            Route::get('/profile', [PatientProfileController::class, 'showProfile']);
            Route::put('/profile', [PatientProfileController::class, 'updateProfile']);
            Route::put('/profile/change-password', [PatientProfileController::class, 'changePassword']);
            Route::delete('/profile', [PatientProfileController::class, 'deleteAccount']);
            Route::get('/medical-record', [MedicalRecordController::class, 'show']);
            Route::put('/medical-record', [MedicalRecordController::class, 'update']);

            // مسارات الطوارئ (تستخدم المتحكم الأصلي)
            Route::post('/emergency/initiate', [\App\Http\Controllers\EmergencyRequestController::class, 'initiateRequest']);
            Route::post('/emergency/send', [\App\Http\Controllers\EmergencyRequestController::class, 'sendRequest']);
            Route::get('/emergency/my-requests', [\App\Http\Controllers\EmergencyRequestController::class, 'listForPatient']);
            Route::get('/emergency/{id}', [\App\Http\Controllers\EmergencyRequestController::class, 'show'])->name('patient.emergency_request.show');
            Route::put('/emergency/{id}/cancel', [\App\Http\Controllers\EmergencyRequestController::class, 'cancelRequest']);
            
            // تحديث المسار لاستخدام متحكم المريض الجديد
            Route::post('/emergency/{emergencyRequest}/feedback', [PatientFeedbackController::class, 'store']);
            
            Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount']);
            Route::put('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead']);
            Route::get('/notifications', [NotificationController::class, 'index']);           
            Route::put('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
            Route::get('/notifications/{id}', [NotificationController::class, 'show']);       
            Route::resource('locations', LocationController::class)->only(['index', 'store', 'update', 'destroy']);
            
            Route::get('/injury-types', [PatientInjuryTypeController::class, 'index']); 
            
            Route::get('/health-guides', [PatientHealthGuideController::class, 'index']);           
            Route::get('/health-guides/{healthGuide}', [PatientHealthGuideController::class, 'show']);
    
        }); 

    });

