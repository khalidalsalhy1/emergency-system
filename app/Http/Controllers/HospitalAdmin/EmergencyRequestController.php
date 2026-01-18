<?php

namespace App\Http\Controllers\HospitalAdmin;

use App\Http\Controllers\Controller;
use App\Models\EmergencyRequest;
use App\Models\RequestStatusHistory; 
use App\Models\Notification as CustomNotification; 
use App\Models\SystemLog; 
use App\Enums\EmergencyRequestStatus; 
// 🚨🚨 لا تستخدم سطر use هذا إذا كنت تستخدم المسار الكامل في دالة updateStatusWeb 🚨🚨
// use App\Notifications\EmergencyRequestStatusChanged; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth; 
use Carbon\Carbon;

class EmergencyRequestController extends Controller
{
    private const IN_PROGRESS_STATUSES = [
        EmergencyRequestStatus::PENDING,
        EmergencyRequestStatus::IN_PROGRESS, 
    ];
    
    public function indexWeb(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $hospitalId = $user->hospital_id; 

        if (!$hospitalId) {
             return redirect()->route('hospital.dashboard')->with('error', 'لا يوجد مستشفى مرتبط بحسابك.');
        }

        $query = EmergencyRequest::where('hospital_id', $hospitalId)
                                 ->with([
                                     'patient:id,full_name,phone', 
                                     'location:id,latitude,longitude,address'
                                 ]);

        if ($request->has('filter') && $request->filter === 'live_tracking') {
             $query->whereIn('status', self::IN_PROGRESS_STATUSES);
        }
        // ... (بقية الفلاتر)

        $requests = $query->latest()->paginate(20);
        $statuses = EmergencyRequestStatus::ALL_STATUSES;

        return view('hospital_admin.emergency_requests.index', compact('requests', 'statuses'));
    }

    public function showWeb(Request $request, EmergencyRequest $emergencyRequest)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        if ($emergencyRequest->hospital_id !== $user->hospital_id) {
            
            SystemLog::log(
                $user->id, 
                'Unauthorized Action', 
                'Hospital Admin attempted to view request ID: ' . $emergencyRequest->id . '.'
            );

            return redirect()->route('hospital.requests.index')->with('error', 'الطلب غير موجود أو لا تملك صلاحية الوصول إليه.');
        }

        $emergencyRequest->load([
            'patient:id,full_name,phone', 
            'patient.medicalRecord', 
            'patient.diseases',
            'location', 
            'injuryType',
            'statusHistory' 
        ]);
        
        $allowedTransitions = EmergencyRequestStatus::VALID_TRANSITIONS[$emergencyRequest->status] ?? [];
        
        return view('hospital_admin.emergency_requests.show', compact('emergencyRequest', 'allowedTransitions'));
    }
    
    /**
     * 3. تحديث حالة الطلب (Web Action) - الحل النهائي المتوافق مع هيكل الجدول وترميز اللغة العربية.
     */
    public function updateStatusWeb(Request $request, EmergencyRequest $emergencyRequest)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $emergencyRequest->load(['patient', 'hospital']); 
        $user->load('hospital'); 

        $oldStatus = $emergencyRequest->status;

        if ($emergencyRequest->hospital_id !== $user->hospital_id) {
            SystemLog::log($user->id, 'Unauthorized Action', 'Hospital Admin attempted unauthorized update.');
            return redirect()->back()->with('error', 'الإجراء غير مصرح به. الطلب لا يخص مستشفاك.');
        }

        $request->validate([
            'status' => ['required', 'string', Rule::in(EmergencyRequestStatus::ALL_STATUSES)],
            'rejection_reason' => Rule::requiredIf($request->input('status') === EmergencyRequestStatus::CANCELED),
        ]);

        $newStatus = $request->input('status');
        $rejectionReason = $request->input('rejection_reason'); 

        $allowedNextStatuses = EmergencyRequestStatus::VALID_TRANSITIONS[$oldStatus] ?? [];
        if (!in_array($newStatus, $allowedNextStatuses)) {
            $message = ($oldStatus === $newStatus) ? 'الطلب موجود بالفعل في حالة ' . $newStatus : 'انتقال حالة غير صالح.';
            return redirect()->back()->with('warning', $message);
        }

        DB::beginTransaction();
        try {
            
            $emergencyRequest->status = $newStatus;
            $emergencyRequest->updated_by = $user->id; 

            if ($newStatus === EmergencyRequestStatus::COMPLETED) {
                $emergencyRequest->completed_at = now();
            } else {
                $emergencyRequest->completed_at = null; 
            }
            
            $isTerminalStatus = ($newStatus === EmergencyRequestStatus::CANCELED);
            $emergencyRequest->rejection_reason = $isTerminalStatus ? $rejectionReason : null;

            $emergencyRequest->save();

            RequestStatusHistory::create([
                'emergency_request_id' => $emergencyRequest->id,
                'status' => $newStatus,
                'changed_by_user_id' => $user->id,
                'reason' => $rejectionReason
            ]);
            
            // 6. إرسال الإشعار للمريض (الحل النهائي)
            try {
                if ($emergencyRequest->patient) {
                    
                    // 🚨🚨 الحل: استخدام المسار الكامل (بدون استخدام use في الأعلى) 🚨🚨
                    $notificationInstance = new \App\Notifications\EmergencyRequestStatusChanged($emergencyRequest, $newStatus, $user);

                    $notificationData = $notificationInstance->toDatabase($emergencyRequest->patient); 
                    
                    if (empty($notificationData['title']) || empty($notificationData['message'])) {
                        throw new \Exception("Notification data is missing Title or Message.");
                    }
                    
                    // 1. استخراج الرسالة النصية الأساسية
                    $baseMessage = $notificationData['message']; 
                    
                    // 2. تصفية البيانات الإضافية
                    $extraData = array_diff_key($notificationData, array_flip(['title', 'message', 'type', 'is_read']));

                    // 3. دمج الرسالة النصية والبيانات الإضافية في Payload واحد
                    // 🚨🚨 استخدام JSON_UNESCAPED_UNICODE لمنع تشفير النص العربي 🚨🚨
                    $fullMessagePayload = json_encode([
                        'text' => $baseMessage, // الرسالة النصية للواجهة
                        'data' => $extraData   // البيانات الوصفية (metadata)
                    ], JSON_UNESCAPED_UNICODE); 

                    // 4. إدراج الإشعار في الجدول المخصص
                    CustomNotification::create([
                        'user_id' => $emergencyRequest->patient->id,
                        'title' => $notificationData['title'],
                        'message' => $fullMessagePayload, // نستخدم JSON Payload الكامل
                        'type' => $notificationData['type'] ?? 'emergency_request_status',
                        'is_read' => $notificationData['is_read'] ?? 0,
                    ]);
                    
                } else {
                     SystemLog::log(Auth::id(), 'Notification Skipped', 'Patient relationship is NULL for request ID: ' . $emergencyRequest->id);
                }
                
            } catch (\Exception $notificationError) {
                // تسجيل الخطأ في السجل النهائي
                SystemLog::log(Auth::id(), 'Notification Creation Failed', 'Error: ' . $notificationError->getMessage());
            }

            // 7. التوثيق في سجل النظام
            SystemLog::log(Auth::id(), 'Emergency Request Status Update', 'Request ID: ' . $emergencyRequest->id . ' status changed to ' . $newStatus . ' by Hospital Admin.');

            DB::commit();
            
            // 🌟🌟 إعادة التوجيه برسالة نجاح قياسية 🌟🌟
            return redirect()->route('hospital.requests.show', $emergencyRequest->id)->with('success', "تم تحديث حالة الطلب إلى '{$newStatus}' بنجاح.");

        } catch (\Exception $e) {
            DB::rollBack();
            
            SystemLog::log(Auth::id(), 'Emergency Request Status Update Failed', 'Failed to update request. Error: ' . $e->getMessage());

            return redirect()->back()->with('error', 'فشل تحديث حالة الطلب: حدث خطأ في الخادم.' . $e->getMessage());
        }
    }
    



/**
 * فحص وجود طلبات طوارئ جديدة للمستشفى الحالي
 * تم إلغاء شرط الوقت لضمان عمل التنبيهات بغض النظر عن توقيت السيرفر
 */
public function checkNewRequests()
{
    try {
        // 1. الحصول على بيانات المستخدم المسجل حالياً باستخدام Auth
        $user = \Illuminate\Support\Facades\Auth::user();

        // 2. التحقق من وجود مستخدم مرتبط بمستشفى
        if (!$user || !$user->hospital_id) {
            return response()->json([
                'has_new' => false,
                'message' => 'User not authenticated or not linked to a hospital'
            ]);
        }

        $hospitalId = $user->hospital_id;

        // 3. البحث عن أحدث طلب معلق (pending) موجه لهذا المستشفى تحديداً
        // نعتمد على latest('id') لضمان جلب الطلب الأخير المدخل في قاعدة البيانات
        $latestRequest = \App\Models\EmergencyRequest::where('hospital_id', $hospitalId)
                ->where('status', 'pending')
                ->latest('id') 
                ->first();

        // 4. إذا وجد طلب، نرسل رقم المعرف الخاص به للمتصفح
        if ($latestRequest) {
            return response()->json([
                'has_new'   => true,
                'latest_id' => $latestRequest->id 
            ]);
        }

        // في حال عدم وجود أي طلبات معلقة
        return response()->json([
            'has_new' => false
        ]);

    } catch (\Exception $e) {
        // تسجيل الخطأ في السجل وإرجاع رسالة خطأ منظمة
        //Log::error("Emergency Check Error: " . $e->getMessage());
        return response()->json([
            'has_new' => false, 
            'error'   => 'Server Error'
        ], 500);
    }
}

















}
