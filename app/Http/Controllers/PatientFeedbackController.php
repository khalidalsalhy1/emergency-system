<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\EmergencyRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller; 
// 🚨 إضافة الاستيراد لمعالجة حالات الـ HTTP بشكل صحيح
use Symfony\Component\HttpFoundation\Response; 

class PatientFeedbackController extends Controller
{
    /**
     * إرسال تقييم لطلب طوارئ محدد.
     * POST /api/patient/emergency/{emergencyRequest}/feedback
     */
    public function store(Request $request, EmergencyRequest $emergencyRequest)
    {
        $user = $request->user();

        // 1. التحقق من الصلاحيات والمنطق
        
        // أ. التأكد أن المستخدم هو مالك الطلب
        if ($emergencyRequest->user_id !== $user->id) {
            return response()->json(['status' => false, 'message' => 'Unauthorized or request not found'], Response::HTTP_NOT_FOUND);
        }

        // ب. التأكد أن الطلب مكتمل (completed)
        if ($emergencyRequest->status !== 'completed') {
            return response()->json(['status' => false, 'message' => 'Feedback can only be submitted for completed requests'], Response::HTTP_FORBIDDEN);
        }

        // ج. التأكد من عدم وجود تقييم مسبق لنفس الطلب
        if (Feedback::where('emergency_request_id', $emergencyRequest->id)->exists()) {
             return response()->json(['status' => false, 'message' => 'Feedback already submitted for this request'], Response::HTTP_CONFLICT);
        }

        // 2. التحقق من البيانات المرسلة
        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'comments' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // 3. إنشاء سجل التقييم
        $feedback = Feedback::create([
            'emergency_request_id' => $emergencyRequest->id,
            'user_id' => $user->id,
            'hospital_id' => $emergencyRequest->hospital_id, 
            'rating' => $request->rating,
            'comments' => $request->comments,
        ]);

        return response()->json(['status' => true, 'message' => 'Feedback submitted successfully', 'data' => $feedback], Response::HTTP_CREATED);
    }
}





