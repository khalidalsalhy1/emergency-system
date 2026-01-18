<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RequestStatusHistory;
use Illuminate\Http\Request;
use App\Models\EmergencyRequest; // نحتاج لموديل الطلبات لعرض اسم الطلب

class RequestStatusHistoryController extends Controller
{
    /**
     * 1. عرض جميع سجلات تغيير الحالات (Index).
     * الترتيب حسب created_at لضمان الترتيب الزمني الصحيح لجميع السجلات.
     */
    public function index()
    {
        $histories = RequestStatusHistory::with(['emergencyRequest', 'changedBy'])
            // 🎯 التعديل: الترتيب حسب created_at (الأضمن ليكون غير NULL)
            ->orderBy('created_at', 'desc') 
            ->paginate(20);
            
        return view('admin.request_history.index', compact('histories'));
    }

    /**
     * 2. عرض تفاصيل سجل حالة معين (Show).
     */
    public function show(RequestStatusHistory $requestStatusHistory)
    {
        // تحميل العلاقات اللازمة لعرض التفاصيل
        $requestStatusHistory->load('emergencyRequest', 'changedBy');

        return view('admin.request_history.show', compact('requestStatusHistory'));
    }
}
