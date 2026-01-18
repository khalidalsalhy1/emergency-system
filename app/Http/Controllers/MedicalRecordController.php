<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller; 

class MedicalRecordController extends Controller
{
    /**
     * عرض الملف الطبي الحالي للمستخدم المسجل دخوله.
     * GET /api/patient/medical-record
     */
    public function show(Request $request)
    {
        // نحتاج هنا أيضاً إلى تحميل علاقة الأمراض عبر المستخدم قبل إرجاعها
        $user = $request->user()->load('diseases');
        $medical = $user->medicalRecord;

        if (!$medical) {
            return response()->json(['status' => false, 'message' => 'Medical record not found'], 404);
        }

        // إرجاع الملف الطبي مع إلحاق بيانات الأمراض من نموذج المستخدم
        return response()->json([
            'status' => true, 
            'medical_record' => $medical,
            'diseases' => $user->diseases
        ]);
    }


    /**
     * تحديث الملف الطبي للمستخدم المسجل دخوله.
     * PUT /api/patient/medical-record
     */
    public function update(Request $request)
    {
        $user = $request->user();
        $medical = $user->medicalRecord;

        if (!$medical) {
            return response()->json(['status' => false, 'message' => 'Medical record not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'birth_date'          => 'nullable|date',
            'gender'              => 'nullable|in:male,female',
            'blood_type'          => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'emergency_contact'   => 'nullable|string|max:20',
            'medical_history'     => 'nullable|string',
            'allergies'           => 'nullable|string',
            'current_medications' => 'nullable|string',
            'notes'               => 'nullable|string',
            'height'              => 'nullable|numeric',
            'weight'              => 'nullable|numeric',
            
            // تحديث الأمراض المزمنة
            'diseases'            => 'nullable|array',
            'diseases.*'          => 'integer|exists:diseases,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        // 1. تحديث البيانات في سجل MedicalRecord
        $medical->fill($request->only([
            'birth_date','gender','blood_type','emergency_contact',
            'medical_history','allergies','current_medications','notes','height','weight'
        ]));
        $medical->save();

        // 2. تحديث جدول pivot للأمراض (يتم دائمًا عبر نموذج المستخدم $user)
        if ($request->filled('diseases')) {
            $user->diseases()->sync($request->diseases);
        } else {
             // إذا تم إرسال 'diseases' كـ array فارغ، يتم مسح كل الأمراض
             $user->diseases()->sync([]);
        }

        // 3. تحميل الأمراض المحدثة على نموذج المستخدم
        $user->load('diseases');

        // 4. إرجاع الاستجابة مع الأمراض المحدثة (من كائن المستخدم)
        return response()->json([
            'status' => true, 
            'message' => 'Medical record updated', 
            'medical_record' => $medical, // إرجاع السجل الطبي
            'diseases' => $user->diseases // 👈 إرجاع الأمراض المحدثة (تم الحل)
        ]);
    }
}
