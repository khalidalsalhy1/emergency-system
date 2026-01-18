<?php

namespace App\Http\Controllers;

use App\Models\EmergencyRequest;
use App\Models\Location;
use App\Models\Hospital;
use App\Models\User;
use App\Models\Notification;
use App\Models\SystemLog; // 🚨 تم استيراد موديل سجل النظام
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller; 

class EmergencyRequestController extends Controller
{
    // -------------------------------------------------------------------
    // 1. عمليات المريض: البحث واختيار المستشفى (initiate & send)
    // -------------------------------------------------------------------

    /**
     * الخطوة 1: تحديد موقع المريض وعرض أقرب المستشفيات.
     */
    public function initiateRequest(Request $request)
    {
        $user = $request->user();

        // 1) فاليديشن للمُدخلات لتحديد الموقع
        $request->validate([
            'use_saved_location'  => 'nullable|boolean',
            'location_id'         => 'nullable|integer|exists:locations,id',
            'latitude'            => 'nullable|numeric|required_if:use_saved_location,false', 
            'longitude'           => 'nullable|numeric|required_if:use_saved_location,false',
        ]);

        $userLat = null;
        $userLng = null;
        $locationId = null;

        if ($request->boolean('use_saved_location')) {
            // استخدام موقع محفوظ
            $location = Location::where('id', $request->location_id)
                                ->where('user_id', $user->id)
                                ->first();

            if (! $location) {
                return response()->json(['status' => false, 'message' => 'Saved location not found or does not belong to user.'], 404);
            }
            $userLat = $location->latitude;
            $userLng = $location->longitude;
            $locationId = $location->id;

        } else {
            // استخدام موقع لحظي
            $userLat = $request->latitude;
            $userLng = $request->longitude;
        }

        // 2) إيجاد أقرب المستشفيات (افتراضي 10 كم)
        $nearbyHospitals = $this->findNearbyHospitals($userLat, $userLng, 10);

        return response()->json([
            'status' => true,
            'message' => 'Nearest hospitals retrieved successfully. Please select one to send the request.',
            'chosen_latitude' => $userLat,
            'chosen_longitude' => $userLng,
            'chosen_location_id' => $locationId, 
            'hospitals' => $nearbyHospitals
        ], 200);
    }
    
    /**
     * الخطوة 2: إرسال الطلب النهائي للمستشفى الذي اختاره المريض.
     */
    public function sendRequest(Request $request)
    {
        $user = $request->user();

        // 1) فاليديشن للمُدخلات
        $request->validate([
            'injury_type_id'      => 'required|integer|exists:injury_types,id',
            'hospital_id'         => 'required|integer|exists:hospitals,id', 
            'latitude'            => 'required|numeric', 
            'longitude'           => 'required|numeric',
            'address'             => 'nullable|string',
            'description'         => 'nullable|string',
            'request_type'        => 'required|in:DISPATCH,NOTIFY',
            'location_id'         => 'nullable|integer|exists:locations,id', 
        ]);


        // 2) تحديد/إنشاء الموقع الذي سيرتبط بالطلب
        if ($request->filled('location_id')) {
            // استخدام موقع محفوظ
            $location = Location::where('id', $request->location_id)
                                ->where('user_id', $user->id)
                                ->first();

            if (! $location) {
                return response()->json(['status' => false, 'message' => 'Location not found or does not belong to user.'], 404);
            }
        } else {
            // استخدام موقع لحظي (إنشاء سجل جديد للموقع في كل مرة)
            $location = Location::create([
                'user_id'     => $user->id,
                'hospital_id' => null, // موقع المريض اللحظي ليس مستشفى
                'latitude'    => $request->latitude,
                'longitude'   => $request->longitude,
                'address'     => $request->address ?? null,
            ]);
        }

        // 3) إنشاء سجل طلب الطوارئ
        $emergency = EmergencyRequest::create([
            'user_id'         => $user->id,
            'injury_type_id'  => $request->injury_type_id,
            'location_id'     => $location->id,
            'hospital_id'     => $request->hospital_id,
            'description'     => $request->description ?? null,
            'status'          => 'pending', 
            'request_type'    => $request->request_type, 
        ]);
        
        // 🚨 4. التوثيق في سجل النظام (إرسال طلب طوارئ) 🚨
        SystemLog::log(
            $user->id, 
            'Emergency Request Sent', 
            'New ' . $emergency->request_type . ' request sent to Hospital ID: ' . $emergency->hospital_id . ' by Patient ID: ' . $user->id . ' (Request ID: ' . $emergency->id . ')'
        );
        
        // 5) إرسال الإشعار للمستشفى المختار
        $user->load(['medicalRecord', 'diseases']);
        $this->sendNotificationToHospital($emergency, $user, $location);
        
        // 6) إرجاع الاستجابة للمريض
        return response()->json([
            'status' => true,
            'message' => 'Emergency request sent to the chosen hospital successfully.',
            'data' => $emergency->load('location','injuryType','hospital'),
            'patient_medical_data' => [
                'medical_record' => $user->medicalRecord,
                'chronic_diseases' => $user->diseases, 
            ],
        ], 201);
    }

    // -------------------------------------------------------------------
    // 2. عمليات عرض الطلبات (للمريض)
    // -------------------------------------------------------------------

    /**
     * عرض جميع طلبات المريض (الخاصة بالمستخدم الحالي)
     */
    public function listForPatient(Request $request)
    {
        $user = $request->user();

        $requests = EmergencyRequest::where('user_id', $user->id)
            ->with(['location','injuryType','hospital'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['status' => true, 'requests' => $requests]);
    }

    /**
     * تفاصيل طلب واحد.
     * الصلاحية: المريض مالك الطلب فقط، أو مسؤول النظام.
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();
        
        $r = EmergencyRequest::with(['location','injuryType','hospital','user.medicalRecord','user.diseases'])->find($id);

        if (! $r) {
            return response()->json(['status' => false, 'message' => 'Request not found'], 404);
        }

        // التحقق من الصلاحية
        if ($user->id !== $r->user_id && !($user->user_role === User::ROLE_SYSTEM_ADMIN)) {
            return response()->json(['status' => false, 'message' => 'Access denied: You are not the owner of this request.'], 403);
        }

        $medical = $r->user->medicalRecord;
        $diseases = $r->user->diseases;

        return response()->json([
            'status' => true,
            'request' => $r,
            'patient_medical' => $medical,
            'patient_diseases' => $diseases,
        ]);
    }

    /**
     * 3. إلغاء طلب طوارئ أرسله المريض.
     * PUT /api/patient/emergency/{id}/cancel
     */
    public function cancelRequest(Request $request, $id)
    {
        $user = $request->user();

        // 1. البحث عن الطلب والتحقق من الملكية
        $emergencyRequest = $user->emergencyRequests()
                                 ->where('id', $id)
                                 ->first();

        if (!$emergencyRequest) {
            return response()->json([
                'status' => false, 
                'message' => 'Emergency request not found or access denied.'
            ], 404);
        }

        // 2. التحقق من حالة الطلب قبل الإلغاء (يجب أن يكون جديد 'new' أو بانتظار 'pending')
        $allowedStatuses = ['new', 'pending'];
        
        if (!in_array($emergencyRequest->status, $allowedStatuses)) {
            return response()->json([
                'status' => false,
                'message' => "Request cannot be cancelled. Current status is: {$emergencyRequest->status}"
            ], 403);
        }

        // 3. تحديث الحالة إلى "ملغي" باستخدام update() المضمونة 🚀
        $emergencyRequest->update(['status' => 'canceled']);
        
        // 🚨 4. التوثيق في سجل النظام (إلغاء طلب طوارئ) 🚨
        SystemLog::log(
            $user->id, 
            'Emergency Request Cancelled', 
            'Request ID: ' . $emergencyRequest->id . ' cancelled by Patient ID: ' . $user->id . '.'
        );
        
        // 5. (اختياري) إرسال إشعار للمستشفى إذا كان الطلب موجهاً إليه لإعلامهم بالإلغاء.

        return response()->json([
            'status' => true,
            'message' => 'Emergency request cancelled successfully.',
            'request' => $emergencyRequest
        ]);
    }
    
    // -------------------------------------------------------------------
    // 4. دوال مساعدة خاصة (Helper Functions)
    // -------------------------------------------------------------------


    /**
     * Helper داخلي: يستخدم لإحضار المستشفيات القريبة (بصيغة Haversine).
     */
    protected function findNearbyHospitals($lat, $lng, $radius_km = 10)
    {
        // الثوابت التي سيتم ربطها بالاستعلام
        $bindings = [$lat, $lng, $lat];

        // الاستعلام الخام
        $rawQuery = "(6371 * acos(
            cos(radians(?)) * cos(radians(locations.latitude)) *
            cos(radians(locations.longitude) - radians(?)) +
            sin(radians(?)) * sin(radians(locations.latitude))
        ))";

        return DB::table('locations')
            // استخدام selectRaw لإضافة الحساب الجغرافي وربط أول 3 ثوابت (الإحداثيات) بأمان
            ->selectRaw("
                locations.id as location_id, 
                locations.latitude,
                locations.longitude,
                locations.address,
                hospitals.id as hospital_id, 
                hospitals.hospital_name as hospital_name, 
                {$rawQuery} as distance_km
            ", $bindings) // 👈 تمرير الـ 3 Bindings هنا
            ->join('hospitals', 'locations.hospital_id', '=', 'hospitals.id')
            ->whereNotNull('locations.hospital_id') 
            // استخدام havingRaw لربط القيمة الرابعة (الـ radius) فقط
            ->havingRaw("distance_km <= ?", [$radius_km]) // 👈 تمرير الـ Binding الأخير هنا
            ->orderBy('distance_km')
            ->get();
    }




















     
    


    /**
     * Helper داخلي: لتجهيز وإرسال الإشعار إلى مدراء المستشفى المختار.
     */
    protected function sendNotificationToHospital(EmergencyRequest $emergency, User $user, Location $location)
    {
        $medicalRecord = $user->medicalRecord;
        $diseases = $user->diseases()->pluck('disease_name')->toArray(); 

        $notificationPayload = [
            'emergency_id' => $emergency->id,
            'patient' => [
                'id' => $user->id,
                'full_name' => $user->full_name,
                'phone' => $user->phone,
                'national_id' => $user->national_id,
            ],
            'medical_record' => $medicalRecord ? [
                'blood_type' => $medicalRecord->blood_type ?? null,
                'allergies' => $medicalRecord->allergies ?? null,
                'current_medications' => $medicalRecord->current_medications ?? null,
            ] : null,
            'chronic_diseases' => $diseases,
            'request' => [
                'description' => $emergency->description,
                'request_type' => $emergency->request_type,
                'location' => [
                    'latitude' => $location->latitude,
                    'longitude' => $location->longitude,
                    'address' => $location->address,
                ],
                'created_at' => $emergency->created_at->toDateTimeString(),
            ],
        ];

        // إرسال الإشعار للمستشفى المختار فقط
        $hospital = Hospital::find($emergency->hospital_id);
        if ($hospital) {
            // نستخدم علاقة admins() الموجودة في Hospital Model
            foreach ($hospital->admins as $admin) { 
                Notification::create([
                    'user_id' => $admin->id,
                    'title'   => 'طلب إسعاف وارد',
                    'message' => json_encode($notificationPayload, JSON_UNESCAPED_UNICODE),
                    'type'    => 'emergency',
                    'is_read' => false,
                ]);
            }
        }
    }
}
