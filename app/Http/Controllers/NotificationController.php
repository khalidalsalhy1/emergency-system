<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 

class NotificationController extends Controller
{
    /**
     * 1. عرض جميع الإشعارات للمستخدم الحالي.
     * GET /api/patient/notifications
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $notifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Notifications retrieved successfully.',
            'notifications' => $notifications
        ]);
    }

    /**
     * 2. عرض إشعار واحد (مع التحقق من الملكية).
     * GET /api/patient/notifications/{id}
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();

        // التحقق من الملكية لضمان أمان البيانات
        $n = Notification::where('id', $id)
                         ->where('user_id', $user->id)
                         ->first();

        if (! $n) {
            return response()->json(['status' => false, 'message' => 'Notification not found or access denied.'], 404);
        }

        return response()->json([
            'status' => true,
            'notification' => $n
        ]);
    }

    /**
     * 3. وضع إشعار واحد كمقروء (مع التحقق من الملكية).
     * PUT /api/patient/notifications/{id}/read
     */
    public function markAsRead(Request $request, $id)
    {
        $user = $request->user();

        // التحقق من الملكية
        $n = Notification::where('id', $id)
                         ->where('user_id', $user->id)
                         ->first();

        if (! $n) {
            return response()->json(['status' => false, 'message' => 'Notification not found or access denied.'], 404);
        }

        $n->update(['is_read' => true]);

        return response()->json([
            'status' => true,
            'message' => 'Notification marked as read successfully.'
        ]);
    }

    /**
     * 4. جلب عدد الإشعارات غير المقروءة. (وظيفة الـ Badge)
     * GET /api/patient/notifications/unread-count
     */
    public function unreadCount(Request $request)
    {
        $user = $request->user();

        $count = Notification::where('user_id', $user->id)
                             ->where('is_read', false)
                             ->count();

        return response()->json([
            'status' => true,
            'unread_count' => $count
        ]);
    }
    
    /**
     * 5. وضع جميع الإشعارات غير المقروءة كمقروءة.
     * PUT /api/patient/notifications/mark-all-as-read
     */
    public function markAllAsRead(Request $request)
    {
        $user = $request->user();

        // تحديث جميع الإشعارات غير المقروءة للمستخدم الحالي فقط
        $updatedCount = Notification::where('user_id', $user->id)
                                    ->where('is_read', false)
                                    ->update(['is_read' => true]);

        return response()->json([
            'status' => true,
            'message' => "Successfully marked {$updatedCount} notifications as read."
        ]);
    }
}
