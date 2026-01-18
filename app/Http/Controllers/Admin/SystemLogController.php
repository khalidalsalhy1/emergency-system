<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemLog;
use Illuminate\Http\Request;

class SystemLogController extends Controller
{
    /**
     * عرض صفحة سجلات النظام (index Web View).
     */
    public function indexWeb()
    {
        // جلب جميع السجلات مع علاقة المستخدم (الذي قام بالفعل)
        // تحديد الأعمدة (id, full_name, user_role) لتحسين الأداء
        $logs = SystemLog::with('user:id,full_name,user_role')
                         ->latest() // عرض الأحدث أولاً
                         ->paginate(50);

        return view('admin.system_logs.index', compact('logs'));
    }

    /**
     * عرض تفاصيل سجل واحد (show Web View).
     * @param  \App\Models\SystemLog  $systemLog
     */
    public function showWeb(SystemLog $systemLog)
    {
        $systemLog->load('user');
        
        // تمرير الكائن باسم 'log' ليتوافق مع ملف الـ View (show.blade.php) الذي يستخدم المتغير $log
        return view('admin.system_logs.show', ['log' => $systemLog]);
    }
}
