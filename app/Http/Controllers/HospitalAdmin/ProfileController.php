<?php

namespace App\Http\Controllers\HospitalAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // 🚨 تم إضافة: لاستخدام المستخدم الحالي
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password; // 🚨 تم إضافة: لاستخدام قواعد كلمة المرور المتقدمة

class ProfileController extends Controller
{
    // ملاحظة: يمكنك إضافة دالة indexWeb لعرض الملف الشخصي هنا إذا لزم الأمر

    /**
     * [Web] يعرض نموذج تغيير كلمة المرور.
     * GET /hospital/profile/change-password
     */
    public function changePasswordWeb()
    {
        // اسم ملف العرض يجب أن يكون: resources/views/hospital_admin/profile/change_password.blade.php
        return view('hospital_admin.profile.change_password'); 
    }

        /**
     * [Web] يعالج تحديث كلمة المرور.
     * POST /hospital/profile/update-password
     */
    public function updatePassword(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 1. التحقق من صحة المدخلات
        $request->validate([
            'current_password' => ['required', 'string'],
            // 'confirmed' تتطلب حقل password_confirmation
            'password' => ['required', 'string', 'confirmed', Password::min(8)], 
        ]);

        // 2. التحقق من كلمة المرور الحالية
        if (!Hash::check($request->current_password, $user->password)) {
            // فشل التحقق
            return back()->withErrors([
                'current_password' => 'كلمة المرور الحالية غير صحيحة.',
            ])->withInput();
        }

        // 3. تحديث كلمة المرور وحفظ المستخدم
        $user->password = Hash::make($request->password);
        $user->save();

        // 4. (تم إزالة السطر المسبب لخطأ Intelephense مؤقتاً لتجنب الخطأ المستمر)
        // إذا كنت تريد هذه الميزة للأمان، يجب أن تستخدم Auth::guard('web')->logoutOtherDevices($request->password);
        // وتتأكد من أن نموذج المستخدم (User Model) يستخدم Illuminate\Foundation\Auth\AuthenticatesUsers trait.
        
        // 5. إعادة التوجيه برسالة نجاح
        return redirect()->route('hospital.profile.change_password')->with('success', 'تم تحديث كلمة المرور بنجاح. قد تحتاج لإعادة تسجيل الدخول.');
    }

    
    
    
    
    
    
    

    
    
    
    
    
    
    

    
    
    
    
    
    
    

    
    
    

    
    
    
    
    
   
    
}
