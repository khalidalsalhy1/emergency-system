<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
// *************************************************************
// ******* Admin Controller Imports *******
// *************************************************************
use App\Http\Controllers\Admin\StatsController; 
use App\Http\Controllers\Admin\AuthController as AdminAuthController; 
use App\Http\Controllers\Admin\HospitalController as AdminHospitalController; 
use App\Http\Controllers\Admin\HospitalAdminManagementController; 
use App\Http\Controllers\Admin\PatientController; 
use App\Http\Controllers\Admin\EmergencyRequestController as AdminEmergencyRequestController; 
use App\Http\Controllers\Admin\InjuryTypeController as AdminInjuryTypeController; 
use App\Http\Controllers\Admin\DiseaseController; 
use App\Http\Controllers\Admin\RequestStatusHistoryController; 
use App\Http\Controllers\Admin\FeedbackController; 
use App\Http\Controllers\Admin\LocationController; 
use App\Http\Controllers\Admin\HealthGuideController; 
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\SystemLogController; 

// *****************************************************************
// ******* 🌟 استيرادات كنترولرات مسؤول المستشفى (جديد) 🌟 *******
// *****************************************************************
use App\Http\Controllers\HospitalAdmin\DashboardController as HospitalDashboardController;
use App\Http\Controllers\HospitalAdmin\EmergencyRequestController as HospitalEmergencyRequestController; 
use App\Http\Controllers\HospitalAdmin\NotificationController as HospitalNotificationController; 
use App\Http\Controllers\HospitalAdmin\ProfileController; 
// *****************************************************************


/*
|--------------------------------------------------------------------------
| مسارات تسجيل الدخول ولوحة تحكم المسؤول (Admin Web Routes)
|--------------------------------------------------------------------------
*/

Route::get('/admin/login', function () {
    return view('admin.auth.login');
})->name('admin.login');

Route::post('/admin/login', [AdminAuthController::class, 'loginWeb'])->name('admin.login.post');

// مجموعة المسارات المحمية لكلا الدورين
Route::middleware(['auth'])->group(function () {

    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    })->name('logout');

    // *************************************************************
    // ******* 🌟 مسارات مسؤول المستشفى (Hospital Manager) 🌟 *******
    // *************************************************************
    Route::group([
        'prefix' => 'hospital',
        'as' => 'hospital.',
        'middleware' => ['role:hospital_admin']
    ], function () {

        // 🚨 نظام التنبيه الفوري: فحص وجود طلبات جديدة
        // تم ربطه بـ HospitalEmergencyRequestController
        Route::get('/check-new-emergencies', [HospitalEmergencyRequestController::class, 'checkNewRequests'])
             ->name('check.new.emergencies');

        // لوحة الإحصائيات (Dashboard)
        Route::get('/dashboard', [HospitalDashboardController::class, 'index'])->name('dashboard');

        // إدارة الطلبات الموجهة للمستشفى
        Route::get('/requests', [HospitalEmergencyRequestController::class, 'indexWeb'])->name('requests.index');
        Route::get('/requests/{emergencyRequest}', [HospitalEmergencyRequestController::class, 'showWeb'])->name('requests.show');
        Route::put('/requests/{emergencyRequest}/status', [HospitalEmergencyRequestController::class, 'updateStatusWeb'])->name('requests.update_status');
        
        // مسارات إدارة الإشعارات
        Route::get('/notifications', [HospitalNotificationController::class, 'indexWeb'])->name('notifications.index');
        Route::get('/notifications/{notification}/read', [HospitalNotificationController::class, 'updateAndRedirect'])->name('notifications.update');
        Route::put('notifications/mark-all-as-read', [HospitalNotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
        
        // مسارات إدارة الملف الشخصي
        Route::controller(ProfileController::class)->group(function () {
            Route::get('/profile/change-password', 'changePasswordWeb')->name('profile.change_password');
            Route::post('/profile/update-password', 'updatePassword')->name('profile.update_password');
        });
    });


    // *************************************************************
    // ******* مسارات الإدارة العامة (System Admin) *******
    // *************************************************************
    Route::group([
        'prefix' => 'admin', 
        'as' => 'admin.', 
        'middleware' => ['role:system_admin']
    ], function () {

        Route::get('/dashboard', [StatsController::class, 'index'])->name('dashboard');

        // مسارات سجل النظام
        Route::get('/system-logs', [SystemLogController::class, 'indexWeb'])->name('system_logs.index');
        Route::get('/system-logs/{systemLog}', [SystemLogController::class, 'showWeb'])->name('system_logs.show');

        // مسارات إدارة الإشعارات
        Route::resource('notifications', NotificationController::class)->only(['index']);
        Route::put('/notifications/{notification}/read', [NotificationController::class, 'update'])->name('notifications.update');
        Route::put('notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');

        // إدارة المستشفيات
        Route::get('/hospitals', [AdminHospitalController::class, 'indexWeb'])->name('hospitals.index');
        Route::get('/hospitals/create', [AdminHospitalController::class, 'createWeb'])->name('hospitals.create');
        Route::post('/hospitals', [AdminHospitalController::class, 'storeWeb'])->name('hospitals.store');
        Route::delete('/hospitals/{hospital}', [AdminHospitalController::class, 'destroyWeb'])->name('hospitals.destroy');
        Route::get('/hospitals/{hospital}/edit', [AdminHospitalController::class, 'editWeb'])->name('hospitals.edit');
        Route::put('/hospitals/{hospital}', [AdminHospitalController::class, 'updateWeb'])->name('hospitals.update');
        Route::get('/hospitals/{hospital}', [AdminHospitalController::class, 'showWeb'])->name('hospitals.show');

        // إدارة أنواع الإصابات
        Route::get('/injury-types', [AdminInjuryTypeController::class, 'indexWeb'])->name('injury_types.index');
        Route::get('/injury-types/create', [AdminInjuryTypeController::class, 'createWeb'])->name('injury_types.create');
        Route::post('/injury-types', [AdminInjuryTypeController::class, 'storeWeb'])->name('injury_types.store');
        Route::get('/injury-types/{injuryType}/edit', [AdminInjuryTypeController::class, 'editWeb'])->name('injury_types.edit');
        Route::put('/injury-types/{injuryType}', [AdminInjuryTypeController::class, 'updateWeb'])->name('injury_types.update');
        Route::delete('/injury-types/{injuryType}', [AdminInjuryTypeController::class, 'destroyWeb'])->name('injury_types.destroy');

        // إدارة مسؤولي المستشفيات
        Route::get('/hospital-admins', [HospitalAdminManagementController::class, 'indexWeb'])->name('hospital_admins.index');
        Route::get('/hospital-admins/create', [HospitalAdminManagementController::class, 'createWeb'])->name('hospital_admins.create');
        Route::post('/hospital-admins', [HospitalAdminManagementController::class, 'storeWeb'])->name('hospital_admins.store');
        Route::get('/hospital-admins/{hospital_admin}/edit', [HospitalAdminManagementController::class, 'editWeb'])->name('hospital_admins.edit');
        Route::put('/hospital-admins/{hospital_admin}', [HospitalAdminManagementController::class, 'updateWeb'])->name('hospital_admins.update');
        Route::delete('/hospital-admins/{hospital_admin}', [HospitalAdminManagementController::class, 'destroyWeb'])->name('hospital_admins.destroy');

        // إدارة المرضى
        Route::get('/patients', [PatientController::class, 'indexWeb'])->name('patients.index');
        Route::get('/patients/create', [PatientController::class, 'createWeb'])->name('patients.create');
        Route::post('/patients', [PatientController::class, 'storeWeb'])->name('patients.store');
        Route::get('/patients/{patient}/edit', [PatientController::class, 'editWeb'])->name('patients.edit');
        Route::put('/patients/{patient}', [PatientController::class, 'updateWeb'])->name('patients.update');
        Route::delete('/patients/{patient}', [PatientController::class, 'destroyWeb'])->name('patients.destroy');

        // إدارة الأمراض المزمنة
        Route::get('/diseases', [DiseaseController::class, 'index'])->name('diseases.index');
        Route::get('/diseases/create', [DiseaseController::class, 'create'])->name('diseases.create');
        Route::post('/diseases', [DiseaseController::class, 'store'])->name('diseases.store');
        Route::get('/diseases/{disease}/edit', [DiseaseController::class, 'edit'])->name('diseases.edit');
        Route::put('/diseases/{disease}', [DiseaseController::class, 'update'])->name('diseases.update');
        Route::delete('/diseases/{disease}', [DiseaseController::class, 'destroy'])->name('diseases.destroy');

        // إدارة المواقع الجغرافية
        Route::get('/locations', [LocationController::class, 'indexWeb'])->name('locations.index');
        Route::get('/locations/create', [LocationController::class, 'createWeb'])->name('locations.create');
        Route::post('/locations', [LocationController::class, 'storeWeb'])->name('locations.store');
        Route::get('/locations/{location}', [LocationController::class, 'showWeb'])->name('locations.show');
        Route::get('/locations/{location}/edit', [LocationController::class, 'editWeb'])->name('locations.edit');
        Route::put('/locations/{location}', [LocationController::class, 'updateWeb'])->name('locations.update');
        Route::delete('/locations/{location}', [LocationController::class, 'destroyWeb'])->name('locations.destroy');

        // إدارة الإرشادات الصحية
        Route::get('/health-guides', [HealthGuideController::class, 'indexWeb'])->name('health_guides.index');
        Route::get('/health-guides/create', [HealthGuideController::class, 'createWeb'])->name('health_guides.create');
        Route::post('/health-guides', [HealthGuideController::class, 'storeWeb'])->name('health_guides.store');
        Route::get('/health-guides/{healthGuide}', [HealthGuideController::class, 'showWeb'])->name('health_guides.show');
        Route::get('/health-guides/{healthGuide}/edit', [HealthGuideController::class, 'editWeb'])->name('health_guides.edit');
        Route::put('/health-guides/{healthGuide}', [HealthGuideController::class, 'updateWeb'])->name('health_guides.update');
        Route::delete('/health-guides/{healthGuide}', [HealthGuideController::class, 'destroyWeb'])->name('health_guides.destroy');

        // إدارة التقييمات
        Route::get('/feedback', [FeedbackController::class, 'indexWeb'])->name('feedback.index');
        Route::get('/feedback/{feedback}', [FeedbackController::class, 'showWeb'])->name('feedback.show');
        Route::delete('/feedback/{feedback}', [FeedbackController::class, 'destroyWeb'])->name('feedback.destroy');

        // سجل حالة الطلب
        Route::get('/request-history', [RequestStatusHistoryController::class, 'index'])->name('request_history.index');
        Route::get('/request-history/{requestStatusHistory}', [RequestStatusHistoryController::class, 'show'])->name('request_history.show');

        // إدارة طلبات الطوارئ
        Route::get('/emergency-requests', [AdminEmergencyRequestController::class, 'indexWeb'])->name('emergency_requests.index');
        Route::get('/emergency-requests/{emergencyRequest}', [AdminEmergencyRequestController::class, 'showWeb'])->name('emergency_requests.show');
        Route::put('/emergency-requests/{emergencyRequest}', [AdminEmergencyRequestController::class, 'updateWeb'])->name('emergency_requests.update');
        Route::delete('/emergency-requests/{emergencyRequest}', [AdminEmergencyRequestController::class, 'destroyWeb'])->name('emergency_requests.destroy');
        Route::get('/emergency-requests/advanced-search', [AdminEmergencyRequestController::class, 'advancedSearchWeb'])->name('emergency_requests.advanced-search');

    });
});

Route::get('/', function () {
    return view('admin.auth.login');
});
// Route::get('/stress-test', function () {
    // 💡 ضع هنا ID المستشفى الخاص بك (مثلاً 4 كما يظهر في صورتك)
    $hospitalId = 4; 
    
    // 💡 ضع هنا ID مستخدم موجود فعلياً في جدول users (مثلاً 1) ليكون هو الـ user_id
    $anyUserId = 4; 

    $injuryTypeId =1;
    $locationId = 3;

    for ($i = 1; $i <= 50; $i++) {
        \App\Models\EmergencyRequest::create([
            'hospital_id' => $hospitalId,
            'user_id'     => $anyUserId, // 👈 هذا هو الحقل الذي سبب المشكلة
            'status'      => 'pending',
            'injury_type_id' => $injuryTypeId , 
            'location_id' => $locationId,
            'details'     => "بلاغ اختبار ضغط رقم $i",
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
    }
    return "تم حقن 50 طلب بنجاح! اذهب الآن لصفحة المستشفى وراقب التنبيه.";
// });
//



Route::get('/setup-admin', function () {
    $admin = User::create([
        'full_name'   => 'khalid hashem alsalhy',
        'email'       => 'khalidalsalhy1@gmail.com', // البريد للدخول
        'phone'       => '771333328',
        'password'    => Hash::make('12345678'), // كلمة المرور
        'user_role'   => User::ROLE_SYSTEM_ADMIN, // 'system_admin'
        'status'      => 'active',
        'national_id' => '1000000000',
    ]);

    return "Admin account created successfully!";
});
