<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmergencyRequest;
use App\Models\Hospital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Models\InjuryType; 
use App\Models\User; 
use App\Models\Notification; 
use App\Notifications\EmergencyRequestStatusChanged; 
use App\Models\SystemLog; 
use Illuminate\Support\Facades\DB; 

class EmergencyRequestController extends Controller
{
    // الحالات المسموح بها في النظام
    private const ALLOWED_STATUSES = ['pending', 'in_progress', 'completed', 'canceled'];

    /**
     * 1. عرض جميع طلبات الطوارئ مع التصفح والفلاتر (index & Filter).
     * 🚨 تمت إضافة البحث عن طريق المستخدم (user_search) 🚨
     */
    public function indexWeb(Request $request)
    {
        $query = EmergencyRequest::query();
        $allowedStatuses = self::ALLOWED_STATUSES;

        // 🔍 تحميل العلاقات اللازمة للعرض السريع (Eager Loading)
        $query->with(['user', 'hospital:id,hospital_name', 'injuryType:id,injury_name']);

        // 📚 التصفية حسب حالة الطلب (من الـ Sidebar أو البطاقات)
        if ($request->filled('status') && in_array($request->status, $allowedStatuses)) {
            $query->where('status', $request->status);
        }

        // 🏥 التصفية حسب المستشفى (ID) - (هذه للتصفية العادية في جدول الفلاتر)
        if ($request->filled('hospital_id')) {
            $query->where('hospital_id', $request->hospital_id);
        }
        
        // 🚨 التصفية حسب اسم المستشفى (Hospital Name) - (من بطاقة الإحصائيات)
        if ($request->filled('hospital_name')) {
            $hospitalName = $request->hospital_name;
            $query->whereHas('hospital', function ($q) use ($hospitalName) {
                $q->where('hospital_name', $hospitalName);
            });
        }
        
        // 🩸 التصفية حسب اسم نوع الإصابة (Injury Name) - (من بطاقة الإحصائيات)
        if ($request->filled('injury_name')) {
            $injuryName = $request->injury_name;
            $query->whereHas('injuryType', function ($q) use ($injuryName) {
                $q->where('injury_name', $injuryName);
            });
        }

        // 🌟🌟 الإضافة الجديدة: البحث عن طريق اسم المستخدم أو رقم هاتفه 🌟🌟
        if ($request->filled('user_search')) {
            $searchTerm = '%' . $request->user_search . '%';
            $query->whereHas('user', function ($q) use ($searchTerm) {
                // البحث في حقل full_name أو حقل phone
                $q->where('full_name', 'like', $searchTerm)
                  ->orWhere('phone', 'like', $searchTerm);
            });
        }
        // 🌟🌟 نهاية الإضافة الجديدة 🌟🌟


        $requests = $query->latest()->paginate(20);

        // 🚨 جلب قائمة المستشفيات لإتاحتها في خيار الفلترة في الـ View
        $hospitals = Hospital::select('id', 'hospital_name')->get();

        // 🛑 إرجاع الواجهة (View)
        return view('admin.emergency_requests.index', [
            'requests' => $requests,
            'hospitals' => $hospitals,
            'allowedStatuses' => $allowedStatuses,
            // 🚨 يتم تمرير قيمة البحث للحفاظ على حالة الإدخال بعد البحث
            'userSearchValue' => $request->user_search, 
        ]);
    }

    /**
     * 2. عرض تفاصيل طلب واحد.
     */
    public function showWeb(EmergencyRequest $emergencyRequest)
    {
        // 📚 تحميل جميع العلاقات المطلوبة للمراجعة التفصيلية
        $emergencyRequest->load([
            'user.medicalRecord',
            'injuryType',
            'location',
            'hospital',
            'statusHistory.changedBy'
        ]);
        
        // 🚨 جلب قوائم البيانات المرجعية المطلوبة لواجهة التعديل اليدوي
        $hospitals = Hospital::select('id', 'hospital_name')->get();
        $injuryTypes = InjuryType::select('id', 'injury_name')->get();

        return view('admin.emergency_requests.show', [
            'emergencyRequest' => $emergencyRequest,
            'hospitals' => $hospitals,
            'injuryTypes' => $injuryTypes,
            'allowedStatuses' => self::ALLOWED_STATUSES,
        ]);
    }

    /**
     * 3. تعديل الطلب (الحالة، المستشفى).
     * 🚨 هذا الكود مقيد ليسمح فقط بتعديل الحالة والمستشفى 🚨
     */
    public function updateWeb(Request $request, EmergencyRequest $emergencyRequest)
    {
        // 1. التحقق من صحة البيانات (Validation)
        // 🚨 تم تقييد التحقق على status و hospital_id و reason المطلوب 🚨
        $validator = Validator::make($request->all(), [
            'status' => ['nullable', 'string', Rule::in(self::ALLOWED_STATUSES)],
            'hospital_id' => 'nullable|exists:hospitals,id',
            'reason' => 'required|string|max:255', // السبب مطلوب الآن لأي تعديل إداري
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // 🚨 2. تحديد الحقول المسموح بتحديثها فقط 🚨
        $updates = $request->only(['status', 'hospital_id']);
        $originalStatus = $emergencyRequest->status;
        
        // حفظ البيانات الأصلية للتوثيق قبل التحديث
        $originalData = $emergencyRequest->getOriginal();

        // 🚨 منطق تسجيل وقت الإكمال (completed_at)
        if (isset($updates['status']) && $updates['status'] === 'completed' && is_null($emergencyRequest->completed_at)) {
            $updates['completed_at'] = now();
        } elseif (isset($updates['status']) && $updates['status'] !== 'completed' && !is_null($emergencyRequest->completed_at)) {
             $updates['completed_at'] = null;
        }

        // 3. تحديث دقيق للطلب
        $emergencyRequest->update($updates);

        // 🚨🚨 4. التوثيق في سجل النظام (SystemLog) 🚨🚨
        $user = Auth::user(); 
        
        // جلب التغييرات التي حدثت فعلياً بعد التحديث
        $changes = $emergencyRequest->getChanges();
        
        // استبعاد حقول التحديث التلقائي من التغييرات المرصودة
        unset($changes['updated_at']);
        
        // إذا كان هناك تغييرات فعلية في status أو hospital_id
        if (!empty($changes)) {
             $logDetails = "تم تعديل الطلب رقم: " . $emergencyRequest->id . " يدوياً بواسطة المسؤول (هوية: " . Auth::id() . "). ";
             
             // تفصيل التغييرات الحساسة (الحالة والمستشفى)
             if (isset($changes['status'])) {
                 $original = $originalData['status'] ?? 'غير محدد';
                 $logDetails .= "تم تغيير الحالة من '{$original}' إلى '{$changes['status']}'. ";
             }
             if (isset($changes['hospital_id'])) {
                 $original = $originalData['hospital_id'] ?? 'لا يوجد';
                 $logDetails .= "تم تغيير المستشفى المسند من (ID: {$original}) إلى (ID: {$changes['hospital_id']}). ";
             }
             
             // إضافة التغييرات الأخرى المسجلة والسبب
             $logDetails .= "التغييرات الأخرى: " . json_encode($changes, JSON_UNESCAPED_UNICODE) . ". ";
             $logDetails .= "سبب التدخل الإداري: " . $request->reason;
             
             SystemLog::log(
                Auth::id(), 
                'تدخل إداري لتعديل طلب طوارئ', // تغيير الـ Action ليعكس طبيعة التدخل
                $logDetails
             );
        }
        // ----------------------------------------------------


        // 🚨 5. منطق تسجيل تغيير الحالة في statusHistory (يسجل فقط عند تغير 'status')
        if (isset($updates['status']) && $updates['status'] !== $originalStatus) {
            
            /** @var \App\Models\User $user */
            if ($user) {
                $emergencyRequest->statusHistory()->create([
                    'status' => $updates['status'],
                    'changed_by_user_id' => $user->id, 
                    'reason' => $request->reason, // استخدام السبب المقدم من المدير
                ]);
            }
            
            // 🛑🛑 منطق الإشعار: إذا تغيرت الحالة، أرسل إشعار للمريض
            $patient = $emergencyRequest->user; 
            
            if ($patient && $patient->id !== $user->id) { 
                
                $notificationInstance = new EmergencyRequestStatusChanged($emergencyRequest, $updates['status'], $user);
                $notificationData = $notificationInstance->toDatabase($patient);
                
                Notification::create([
                    'user_id' => $patient->id, 
                    'title' => $notificationData['title'], 
                    'message' => $notificationData['message'], 
                    'type' => $notificationData['type'] ?? 'emergency_request_status', 
                    'is_read' => 0, 
                ]);
            }
        }

        // 🛑 إرجاع المستخدم إلى صفحة التفاصيل مع رسالة نجاح
        // التأكد من أن التعديلات تمت قبل الإرجاع
        return redirect()->route('admin.emergency_requests.show', $emergencyRequest->id)
                         ->with('success', 'تم تطبيق التدخل الإداري وتحديث الطلب بنجاح.');
    }

    /**
     * 4. حذف الطلب (حذف دائم).
     */
    public function destroyWeb(EmergencyRequest $emergencyRequest)
    {
        $id = $emergencyRequest->id;
        $status = $emergencyRequest->status;

        // 🚨🚨 1. التوثيق في سجل النظام (قبل الحذف) - تعريب بالكامل 🚨🚨
        SystemLog::log(
            Auth::id(), 
            'حذف دائم لطلب طوارئ', 
            "تم حذف طلب الطوارئ رقم: {$id} بشكل دائم بواسطة المسؤول (هوية: " . Auth::id() . "). كانت حالته قبل الحذف: {$status}."
        );
        // ----------------------------------------------------
        
        $emergencyRequest->delete();

        // 🛑 إرجاع المستخدم إلى صفحة الـ index مع رسالة نجاح
        return redirect()->route('admin.emergency_requests.index')
                         ->with('success', 'تم حذف طلب الطوارئ بنجاح.');
    }
    
    // ----------------------------------------------------------------
    // دوال البحث والتصفية المخصصة (واجهة الويب)
    // ----------------------------------------------------------------

    /**
     * 8. البحث المتقدم والشامل (advancedSearchWeb).
     */
    public function advancedSearchWeb(Request $request)
    {
        // 1. التحقق من صحة مدخلات البحث
        $validator = Validator::make($request->all(), [
            'request_type' => ['nullable', Rule::in(['DISPATCH', 'NOTIFY'])],
            'injury_type_id' => 'nullable|exists:injury_types,id',
            'from_date' => 'nullable|date_format:Y-m-d',
            'to_date' => 'nullable|date_format:Y-m-d|after_or_equal:from_date',
        ]);

        if ($validator->fails()) {
             return redirect()->back()->withErrors($validator)->withInput();
        }

        $query = EmergencyRequest::query();
        $query->with(['user', 'hospital:id,hospital_name', 'injuryType:id,injury_name']);

        // 2. تطبيق شروط البحث المتقدم
        if ($request->filled('request_type')) {
            $query->where('request_type', $request->request_type);
        }

        if ($request->filled('injury_type_id')) {
            $query->where('injury_type_id', $request->injury_type_id);
        }
        
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $requests = $query->latest()->paginate(20);
        
        // 🚨 جلب قوائم البيانات المرجعية للواجهة
        $hospitals = Hospital::select('id', 'hospital_name')->get();
        $injuryTypes = InjuryType::select('id', 'injury_name')->get();

        // 🛑 إرجاع الـ View مع النتائج
        return view('admin.emergency_requests.index', [
            'requests' => $requests,
            'hospitals' => $hospitals,
            'allowedStatuses' => self::ALLOWED_STATUSES,
            'isAdvancedSearch' => true, 
        ]);
    }
    
    // ----------------------------------------------------------------
    // ⚠️ تم حذف دوال API المتبقية ⚠️ 
    // ----------------------------------------------------------------
}
