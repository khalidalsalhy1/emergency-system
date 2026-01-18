<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification; // 🚨 موديل الإشعار المخصص لديك
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * عرض جميع الإشعارات الواردة إلى المدير الحالي.
     * GET /admin/notifications
     */
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $notifications = Notification::where('user_id', $user->id)
            ->orderBy('is_read', 'asc') // غير المقروء أولاً
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.notifications.index', compact('notifications'));
    }

    /**
     * وضع علامة "مقروء" على إشعار واحد.
     * PUT/PATCH /admin/notifications/{notification}
     */
    public function update(Notification $notification)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 1. التأكد من أن الإشعار يخص المستخدم الحالي وأنه غير مقروء
        if ($notification->user_id !== $user->id || $notification->is_read) {
            return redirect()->back()->with('warning', 'الإشعار مقروء بالفعل أو ليس متاحاً لك.');
        }
        
        // 2. تحديث الحالة
        $notification->markAsRead(); // نستخدم دالة markAsRead() من موديل Notification الذي أرسلته

        // 3. تحليل بيانات الإشعار وإعادة توجيه المدير إلى مصدره (البلاغ)
        $route = $this->getNotificationRoute($notification);

        return redirect()->to($route)->with('success', 'تم وضع الإشعار كمقروء.');
    }
    
    /**
     * وضع علامة "مقروء" على جميع الإشعارات غير المقروءة.
     * PUT /admin/notifications/mark-all-as-read
     */
    public function markAllAsRead()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // تحديث جميع الإشعارات غير المقروءة للمستخدم الحالي فقط
        $updatedCount = Notification::where('user_id', $user->id)
                                    ->where('is_read', 0)
                                    ->update(['is_read' => 1]);

        if ($updatedCount > 0) {
            return redirect()->back()->with('success', "تم وضع علامة مقروء على {$updatedCount} إشعار.");
        }

        return redirect()->back()->with('info', 'لا توجد إشعارات جديدة غير مقروءة.');
    }
    
    /**
     * دالة مساعدة لتحليل الإشعار وتحديد مسار إعادة التوجيه.
     * (هام: يجب أن تكون لديك مسارات باسم 'admin.emergency_requests.show' بالفعل)
     */
    protected function getNotificationRoute(Notification $notification): string
    {
        // إذا كان نوع الإشعار بلاغ طوارئ (كما يحدث عند وصول بلاغ جديد)
        if ($notification->type === 'emergency') {
            // بما أن رسالة الإشعار عند وصول بلاغ جديد كانت JSON، يجب تحليلها
            $data = json_decode($notification->message, true);
            
            // تحقق من وجود ID الطلب
            if (isset($data['request_id'])) { 
                 // نعود إلى صفحة عرض تفاصيل الطلب
                return route('admin.emergency_requests.show', $data['request_id']);
            }
        }
        
        // إذا كان الإشعار من نوع آخر أو لم نتمكن من تحليل الرابط، نعود إلى صفحة الإشعارات
        return route('admin.notifications.index');
    }
}
