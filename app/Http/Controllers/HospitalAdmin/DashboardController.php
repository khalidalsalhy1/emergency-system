<?php

namespace App\Http\Controllers\HospitalAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\EmergencyRequest;
use App\Models\User;
use Carbon\Carbon;
// 🟢 تأكد من استيراد كلاس Enum
use App\Enums\EmergencyRequestStatus; 

class DashboardController extends Controller
{
    /**
     * عرض لوحة الإحصائيات لمسؤول المستشفى.
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $hospitalId = $user->hospital_id;

        // 1. التحقق من مُعرف المستشفى المرتبطة بالمسؤول
        if (!$hospitalId) {
            return abort(403, 'المستخدم غير مرتبط بأي مستشفى.');
        }

        // 🚨 تعريف الحالات التي تعتبر "قيد المعالجة"
        // 🟢 تم التعديل: استخدام الثوابت الموجودة فعلياً في عمود 'status' لديك
        $IN_PROGRESS_STATUSES = [
            EmergencyRequestStatus::PENDING,
            EmergencyRequestStatus::IN_PROGRESS, 
        ];

        // 2. تجميع البيانات والإحصائيات
        
        // **********************************************
        // 🌟 إنشاء استعلام أساسي للطلبات الموجهة للمستشفى
        // **********************************************
        $hospitalRequests = EmergencyRequest::where('hospital_id', $hospitalId);

        // أ. إجمالي الطلبات التي تم توجيهها للمستشفى
        $totalAssignedRequests = (clone $hospitalRequests)->count();
        
        // ب. الطلبات التي ما زالت "قيد المعالجة"
        $inProgressRequests = (clone $hospitalRequests)
            ->whereIn('status', $IN_PROGRESS_STATUSES) // 🟢 الآن يستخدم [pending, in_progress]
            ->count();

        // ج. الطلبات المنجزة (مكتملة)
        $completedRequests = (clone $hospitalRequests)
            ->where('status', EmergencyRequestStatus::COMPLETED) // 🟢 استخدام ثابت Enum
            ->count();
        
        // د. الطلبات الواردة اليوم
        $todayRequests = (clone $hospitalRequests)
            ->whereDate('created_at', Carbon::today())
            ->count();
            
        // هـ. عدد المرضى المُسجلين في المستشفى (إن وجد ربط مباشر)
        $totalAssignedPatients = User::where('hospital_id', $hospitalId)
                                      ->where('user_role', 'patient')
                                      ->count();


        // 3. تجميع بيانات الداشبورد
        $dashboardStats = [
            'total_assigned_requests' => $totalAssignedRequests,
            'in_progress_requests'    => $inProgressRequests, 
            'completed_requests'      => $completedRequests,
            'today_requests'          => $todayRequests,
            'assigned_patients'       => $totalAssignedPatients,
            'hospital_name'           => $user->hospital->name ?? 'المستشفى', 
        ];
        
        // 4. إرسال البيانات إلى الـ View
        return view('hospital_admin.dashboard', compact('dashboardStats'));
    }
}
