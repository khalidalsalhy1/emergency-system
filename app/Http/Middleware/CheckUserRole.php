<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // 1. التحقق من المصادقة أولاً
        if (!Auth::check()) {
            // إذا لم يكن مصادقاً: يجب أن يعيد توجيه إلى صفحة تسجيل الدخول في الويب
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['message' => 'Unauthenticated.', 'status' => false], 401);
            }
            // التوجيه إلى مسار تسجيل دخول المسؤول
            return redirect()->route('admin.login'); 
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 2. التحقق من تطابق الدور المطلوب
        if ($user->user_role === $role) {
            return $next($request);
        }

        // 3. رفض الوصول إذا لم يطابق الدور
        $errorMessage = 'Access Denied. Insufficient privileges.';
        
        // إذا كان الطلب API (JSON)، أرجع استجابة JSON
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'message' => $errorMessage,
                'status' => false
            ], 403);
        }

        // إذا كان الطلب WEB، أرجع توجيه أو استجابة 403 (صفحة خطأ)
        // الخيار الأفضل هو استخدام الدالة abort
        abort(403, $errorMessage); 
    }
}
