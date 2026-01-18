<?php

namespace App\Http\Controllers\Admin; 

use App\Models\User;
use App\Models\SystemLog; // 🚨 موديل سجل النظام
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use App\Http\Controllers\Controller;

// ⚠️ تم إزالة الاستيرادات غير المستخدمة: Hash, ValidationException, Response

class AuthController extends Controller
{
    /**
     * تسجيل دخول مسؤول النظام ومسؤول المستشفى للواجهة (Web - Sessions/Cookies).
     * POST /admin/login (في ملف web.php)
     */
    public function loginWeb(Request $request)
    {
        // 1. التحقق من صحة البيانات (مستخدمين phone)
        $credentials = $request->validate([
            'phone' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        // 2. محاولة المصادقة باستخدام حارس الـ web الافتراضي
        if (Auth::attempt($credentials)) {

            $user = Auth::user();

            // 3. التحقق من الدور: يجب أن يكون system_admin أو hospital_admin
            if (
                $user->user_role !== User::ROLE_SYSTEM_ADMIN &&
                $user->user_role !== User::ROLE_HOSPITAL_ADMIN
            ) { 
                
                // 🚨 توثيق محاولة وصول ممنوعة
                SystemLog::log(
                    $user->id, 
                    'Login Denied (Web)', 
                    'User: ' . ($user->full_name ?? $user->phone) . ' attempted to login but has role: ' . $user->user_role . '.'
                );
                
                // تسجيل الخروج فوراً ومنعه من الدخول
                Auth::logout();
                return back()->withErrors([
                    'phone' => 'ممنوع. الحساب غير مصرح له بالدخول إلى هذه المنطقة.',
                ])->onlyInput('phone');
            }

            // 🚨 4. التوثيق في سجل النظام (تسجيل دخول ناجح) 🚨
            SystemLog::log(
                $user->id, 
                'Login Success (Web)', 
                'Successful login to Dashboard by User: ' . $user->full_name . ' (Role: ' . $user->user_role . ')'
            );
            
            // 5. تجديد الجلسة والأمان 
            $request->session()->regenerate();

            // 6. 🌟🌟 التوجيه الذكي بناءً على الدور 🌟🌟
            if ($user->user_role === User::ROLE_SYSTEM_ADMIN) {
                return redirect()->intended(route('admin.dashboard')); 
            } elseif ($user->user_role === User::ROLE_HOSPITAL_ADMIN) {
                return redirect()->intended(route('hospital.dashboard'));
            }
            
            // خيار احتياطي (يتم الوصول إليه فقط في حالة وجود دور مسموح به لم يتم تغطيته في if/elseif)
            return redirect()->intended(route('admin.dashboard')); 
        }

        // فشل المصادقة
        return back()->withErrors([
            'phone' => 'بيانات تسجيل الدخول غير صحيحة.',
        ])->onlyInput('phone');
    }
    
    // ⚠️ تم حذف دوال الـ API بناءً على المراجعة السابقة.
}
