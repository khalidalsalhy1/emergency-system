<?php

namespace App\Http\Controllers;

use App\Models\InjuryType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 

class PatientInjuryTypeController extends Controller
{
    /**
     * عرض قائمة بجميع أنواع الإصابات (للمريض لاختيار النوع).
     * GET /api/patient/injury-types
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // جلب المعرف والاسم والوصف فقط، مع ترتيب أبجدي لسهولة عرضها في التطبيق
        // 🚨 ملاحظة: تم استخدام 'injury_name' بناءً على الكود الأصلي الذي قدمته لي.
        // يجب التأكد من أن هذا هو اسم العمود الصحيح في جدول injury_types.
        $injuryTypes = InjuryType::select('id', 'injury_name', 'description')
                                 ->orderBy('injury_name')
                                 ->get();

        return response()->json(['status' => true, 'data' => $injuryTypes]);
    }

    // 🚨 تم حذف دوال الإدارة (store, show, update, destroy) من هنا،
    // وهي موجودة الآن في متحكم المدير Admin\InjuryTypeController.php
}
