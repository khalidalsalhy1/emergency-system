<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\MedicalRecord;
use App\Models\SystemLog; 
use App\Models\Notification; // 🚨🚨 الاستيراد الجديد 🚨🚨
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // ===========================
    // Helper: اسم المفتاح في الكاش للـ OTP
    // ===========================
    protected function otpCacheKey(string $phone): string
    {
        return 'pwd_reset_otp:' . $phone;
    }

    // ===========================
    // تسجيل مريض جديد + إنشاء الملف الطبي + ربط الأمراض
    // ===========================
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name'       => 'required|string|max:255',
            'phone'           => 'required|string|max:20|unique:users,phone',
            'password'        => 'required|string|min:6',
            'national_id'     => 'nullable|string|max:50',

            // Medical record (إلزامي)
            'birth_date'          => 'required|date',
            'gender'              => 'required|in:male,female',
            'blood_type'          => 'nullable|in:A+,A-,B+,B-,O+,O-,AB+,AB-',
           
           
            
            // الأمراض
            'diseases'            => 'nullable|array',
            'diseases.*'          => 'integer|exists:diseases,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['status'=>false,'errors'=>$validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            // 1. إنشاء المستخدم (المريض)
            $user = User::create([
                'full_name'       => $request->full_name,
                'phone'           => $request->phone,
                // 🚨🚨 التعديل الأمني: تشفير كلمة المرور هنا يدوياً
                'password'        => Hash::make($request->password),
                'national_id'     => $request->national_id,
                'user_role'       => User::ROLE_PATIENT, // تعيين الدور
                'status'          => 'active',
            ]);

            // 2. إنشاء الملف الطبي
            $medicalRecord = MedicalRecord::create([
                'user_id'             => $user->id,
                'birth_date'          => $request->birth_date,
                'gender'              => $request->gender,
                'blood_type'          => $request->blood_type,
               
               
                // يتم إكمال الحقول المتبقية لاحقاً عبر MedicalRecordController
            ]);

            // 3. ربط الأمراض المزمنة
            if ($request->filled('diseases')) {
                $user->diseases()->sync($request->diseases);
            }
            
            // 🚨 4. التوثيق في سجل النظام (Patient Registration) 🚨
            // نمرر ID المستخدم الذي تم إنشاؤه
            SystemLog::log(
                $user->id, 
                'Patient Registration', 
                'New patient registered via API: ' . $user->full_name . ' (ID: ' . $user->id . ')'
            );

            DB::commit();

            // 5. تسجيل الدخول التلقائي وتوليد التوكن
            $token = $user->createToken('patient_auth_token', ['role:patient'])->plainTextToken;

            return response()->json([
                'status' => true,
                'message' => 'Registration successful.',
                'user' => $user->load('medicalRecord', 'diseases'),
                'token' => $token
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Registration failed for phone {$request->phone}: " . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Registration failed due to a server error.'], 500);
        }
    }

    // ===========================
    // تسجيل دخول المريض
    // ===========================
    public function login(Request $request)
    {
        $throttleKey = $request->phone . '|' . $request->ip();

        // 1. تحديد سرعة المحاولة لمنع هجمات القوة الغاشمة
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return response()->json([
                'status' => false,
                'message' => "Too many login attempts. Try again in {$seconds} seconds."
            ], 429);
        }

        $request->validate([
            'phone' => 'required|string', 
            'password' => 'required|string',
        ]);

        // 2. محاولة المصادقة
        if (! Auth::attempt($request->only('phone', 'password'))) {
            RateLimiter::hit($throttleKey, 60); // زيادة عدد مرات المحاولة الفاشلة
            
            // 💡 توثيق محاولة تسجيل دخول فاشلة للمراقبة 
            $failedUser = User::where('phone', $request->phone)->first();
            if ($failedUser) {
                SystemLog::log(
                    $failedUser->id, 
                    'Login Failed (API)', 
                    'User with phone: ' . $request->phone . ' failed login attempt. IP: ' . $request->ip()
                );
            }
            
            throw ValidationException::withMessages([
                'phone' => ['بيانات الاعتماد المدخلة غير صحيحة.'],
            ]);
        }

        $user = $request->user();

        // 3. التحقق من الدور والحالة
        if (! $user->isPatient() || $user->status !== 'active') {
            Auth::logout(); // تسجيل الخروج التلقائي
            return response()->json([
                'status' => false,
                'message' => 'غير مصرح لك بالدخول، هذا الحساب غير نشط أو ليس مريضاً.'
            ], 403);
        }
        
        // 🚨 4. التوثيق في سجل النظام (تسجيل دخول ناجح) 🚨
        SystemLog::log(
            $user->id, 
            'Login Success (API)', 
            'Patient login successful. Phone: ' . $user->phone
        );
        
        // 5. توليد التوكن وإعادة تعيين عداد المحاولات
        RateLimiter::clear($throttleKey);
        $token = $user->createToken('patient_auth_token', ['role:patient'])->plainTextToken; 

        return response()->json([
            'status' => true,
            'message' => 'تم تسجيل الدخول بنجاح.',
            'user' => $user->load('medicalRecord', 'diseases'),
            'token' => $token
        ]);
    }

    // ===========================
    // تسجيل الخروج
    // ===========================
    public function logout(Request $request)
    {
        // 🚨 1. التوثيق في سجل النظام (تسجيل خروج) 🚨
        if ($request->user()) {
             SystemLog::log(
                $request->user()->id, 
                'Logout (API)', 
                'Patient logged out successfully.'
            );
        }
        
        $request->user()->currentAccessToken()->delete();
        return response()->json(['status' => true, 'message' => 'تم تسجيل الخروج بنجاح.']);
    }
    
    // ===========================
    // طلب رمز OTP لإعادة تعيين كلمة المرور
    // 🚨🚨 تم التعديل للإرسال كإشعار يدوي 🚨🚨
    // ===========================
    public function requestPasswordReset(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|exists:users,phone',
        ]);

        $phone = $request->phone;
        $throttleKey = 'otp_request:' . $phone;

        // تحديد سرعة الطلب (مثلاً: 5 طلبات في الساعة)
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return response()->json([
                'status' => false,
                'message' => "Too many OTP requests. Try again in {$seconds} seconds."
            ], 429);
        }

        // 1. توليد الـ OTP وتخزينه في الكاش لمدة 10 دقائق
        $otp = random_int(100000, 999999);
        $cacheKey = $this->otpCacheKey($phone);
        Cache::put($cacheKey, $otp, now()->addMinutes(10));

        // 2. 🌟🌟 منطق الإرسال كإشعار يدوي (Manual Notification) 🌟🌟
        $user = User::where('phone', $phone)->first();
        
        if ($user) {
             try {
                // إنشاء الإشعار يدوياً ليظهر في NotificationController
                Notification::create([
                    'user_id' => $user->id, 
                    'title' => 'رمز التحقق (OTP) لإعادة تعيين كلمة المرور', 
                    'message' => "رمز التحقق الخاص بك هو: {$otp}. صالح لمدة 10 دقائق.", 
                    'type' => 'password_reset_otp', 
                    'is_read' => 0, 
                    'data' => json_encode(['otp' => $otp]), // لتخزين الرمز لاستخدامه لاحقًا في التطبيق
                ]);
                
             } catch (\Exception $e) {
                // التعامل مع أي خطأ قد يحدث أثناء الإرسال اليدوي
                Log::error("OTP Notification failed to create for {$phone}: " . $e->getMessage());
                // لا نرجع خطأ 500 إلا إذا كان الفشل يؤثر على العملية
                // لكننا سنستمر لضمان إمكانية إكمال العملية (الرمز لا يزال في الكاش)
             }
        } 
        // ----------------------------------------------------

        // 3. زيادة عدد المحاولات وإرجاع الرد
        RateLimiter::hit($throttleKey, 60);

        // 🚨 تم حذف سطر Log::info("Password reset OTP...") لتحسين الأمان 🚨
        
        // 🚨 4. التوثيق في سجل النظام (طلب رمز) 🚨
        if ($user) {
            SystemLog::log(
                $user->id, 
                'Password Reset Request', 
                'OTP requested and sent as manual notification by User ID: ' . $user->id
            );
        }

        return response()->json([
            'status' => true,
            'message' => 'تم توليد رمز التحقق وإرساله كإشعار إلى حسابك.',
        ]);
    }

    // ===========================
    // إعادة تعيين كلمة المرور عبر OTP
    // ===========================
    public function resetPasswordWithOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|exists:users,phone',
            'otp'   => 'required|digits:6',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['status'=>false,'errors'=>$validator->errors()], 422);
        }

        $phone = $request->phone;
        $cacheKey = $this->otpCacheKey($phone);
        $cached = Cache::get($cacheKey);

        // 1. التحقق من صحة الـ OTP
        if (!$cached || (string)$cached !== (string)$request->otp) {
            return response()->json(['status'=>false,'message'=>'Invalid or expired OTP'], 400);
        }

        $user = User::where('phone', $phone)->first();
        
        // 2. 🚨🚨 التعديل الأمني: تشفير كلمة المرور الجديدة يدوياً قبل حفظها
        $user->password = Hash::make($request->new_password); 
        $user->save();

        // 3. إبطال الـ OTP وحذف الجلسات القديمة
        Cache::forget($cacheKey);
        $user->tokens()->delete(); // حذف جميع التوكنات القديمة لتأمين الحساب
        
        // 🚨 4. التوثيق في سجل النظام (نجاح إعادة التعيين) 🚨
        SystemLog::log(
            $user->id, 
            'Password Reset Success', 
            'Password successfully reset via OTP by User ID: ' . $user->id
        );

        return response()->json(['status'=>true,'message'=>'Password reset successfully. All sessions revoked.']);
    }
}
