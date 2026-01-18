<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller; 

class PatientProfileController extends Controller
{
    /**
     * عرض بروفايل المريض (البيانات الأساسية والطبية).
     * GET /api/patient/profile
     */
    public function showProfile(Request $request)
    {
        $user = $request->user()->load('medicalRecord','diseases');
        return response()->json(['status' => true, 'user' => $user]);
    }

    /**
     * تحديث بيانات المستخدم الأساسية (الاسم، الرقم الوطني، الهاتف).
     * PUT /api/patient/profile
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'full_name'   => 'nullable|string|max:255',
            // التحقق من أن الهاتف فريد مع استثناء المستخدم الحالي
            'phone'       => 'nullable|string|max:20|unique:users,phone,'.$user->id, 
            'national_id' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        // استخدام fill لحفظ البيانات المحدثة فقط
        $user->fill($request->only(['full_name','phone','national_id']));
        $user->save();

        return response()->json(['status' => true, 'message' => 'Profile updated successfully', 'user' => $user]);
    }


    /**
     * تغيير كلمة المرور للمستخدم المسجل دخوله.
     * PUT /api/patient/profile/change-password
     */
    public function changePassword(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed', // يتطلب وجود new_password_confirmation
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        // التحقق من كلمة المرور القديمة
        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json(['status' => false, 'message' => 'Old password is incorrect'], 403);
        }

        // 🚨🚨 التعديل الحاسم: تعيين وتشفير كلمة المرور الجديدة
        $user->password = Hash::make($request->new_password); 
        $user->save();

        // حذف كل التوكنات القديمة لتأمين الحساب
        $user->tokens()->delete();

        return response()->json(['status' => true, 'message' => 'Password changed successfully. All sessions revoked.']);
    }

    /**
     * حذف الحساب الحالي للمستخدم (حذف ناعم/أرشفة).
     * DELETE /api/patient/profile
     */
    public function deleteAccount(Request $request)
    {
        $user = $request->user();

        // حذف التوكنات
        $user->tokens()->delete();

        // 🚨 تنفيذ الحذف الناعم (Soft Delete) بسبب تفعيله في نموذج User.php
        // هذا يحافظ على السجلات الطبية والتاريخية
        $user->delete(); 

        return response()->json(['status' => true, 'message' => 'Account deleted (archived) successfully.']);
    }
}
