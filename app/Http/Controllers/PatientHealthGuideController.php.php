<?php

namespace App\Http\Controllers;

use App\Models\HealthGuide;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
use Symfony\Component\HttpFoundation\Response;

class PatientHealthGuideController extends Controller
{
    /**
     * (للمريض) عرض قائمة بجميع إرشادات الإسعافات الأولية المنشورة.
     * GET /api/patient/health-guides
     * * يمكن تصفيتها حسب 'category'
     */
    public function index(Request $request)
    {
        // 🚨 تعديل هام: يجب عرض الإرشادات المنشورة فقط (is_published = true)
        $query = HealthGuide::select('id', 'title', 'category', 'content')
                             ->where('is_published', true); // افتراض أن النموذج يحتوي على هذا الحقل

        // تطبيق الفلتر حسب التصنيف إذا تم إرساله
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        // جلب الإرشادات مرتبة حسب العنوان
        $guides = $query->orderBy('title')->get();

        return response()->json(['status' => true, 'data' => $guides]);
    }

    /**
     * (للمريض) عرض تفاصيل إرشاد محدد.
     * GET /api/patient/health-guides/{id}
     */
    public function show(HealthGuide $healthGuide)
    {
        // 🚨 يجب التأكد أن الإرشاد منشور قبل عرضه للمريض
        if (!$healthGuide->is_published) { 
             return response()->json(['status' => false, 'message' => 'Health guide not found or not published.'], Response::HTTP_NOT_FOUND);
        }
        
        return response()->json(['status' => true, 'data' => $healthGuide]);
    }

    // 🚨 تم حذف دوال الإدارة (store, update, destroy) من هنا
}
