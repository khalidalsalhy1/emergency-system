<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\User; // 🚨 تم استيراد موديل المستخدم
use App\Models\Notification; // 🚨 تم استيراد موديل الإشعار
use Illuminate\Http\Request; 
use Symfony\Component\HttpFoundation\Response;

class FeedbackController extends Controller
{
    /**
     * 1. عرض قائمة بجميع التقييمات والملاحظات (Index Web View).
     */
    public function indexWeb()
    {
        // جلب جميع التقييمات مع علاقات (المستخدم، الطلب، المستشفى)
        $feedbacks = Feedback::with(['user:id,full_name,name', 'emergencyRequest:id,status', 'hospital:id,hospital_name'])
                             ->latest()
                             ->paginate(20);

        return view('admin.feedback.index', compact('feedbacks'));
    }

    // 🚨🚨 إضافة دالة حفظ التقييم ومنطق الإشعار 🚨🚨
    /**
     * 2. (افتراضي) استقبال وحفظ تقييم جديد من المريض.
     * * *هذا هو المكان الذي يتم فيه إطلاق إشعار المدير العام.
     */
    public function store(Request $request)
    {
        // 1. منطق التحقق والحفظ (يجب استبداله بمنطق الحفظ الفعلي لديك)
        // ... $feedback = Feedback::create([...]); ...
        
        // 🚨 افتراض أن التقييم تم حفظه وأن $feedback كائن موجود 🚨
        // لغرض التجربة، يمكنك محاكاة الـ Feedback كما يلي:
        // $feedback = Feedback::find(1); 
        
        // 2. 🚨🚨 منطق إرسال الإشعار لمدراء النظام 🚨🚨
        
        // هذا المنطق يجب أن يُنفذ بعد عملية حفظ التقييم ($feedback) بنجاح
        if (isset($feedback)) { // التحقق من وجود كائن التقييم
            
            // 2.1. تحديد مدراء النظام (نستخدم 'system_admin' كدور افتراضي للمدير العام)
            $systemAdmins = User::where('user_role', 'system_admin')->get(); 
            
            // 2.2. إعداد بيانات الإشعار (جلب اسم المريض من علاقة الـ user في موديل Feedback)
            $patientName = $feedback->user->full_name ?? 'مريض غير معروف';
            $ratingText = ($feedback->rating) ? "بتقييم {$feedback->rating} نجوم" : "بملاحظات نصية";

            // 2.3. إنشاء الإشعار لكل مدير
            foreach ($systemAdmins as $admin) {
                Notification::create([ 
                    'user_id' => $admin->id,
                    'title'   => '⭐ تقييم جديد وصل!',
                    'message' => "وصل تقييم جديد {$ratingText} من المريض: {$patientName}. يرجى مراجعة سجل التقييمات.",
                    'type'    => 'new_feedback',
                    'is_read' => false,
                ]);
            }
        }
        
        // 3. إرجاع الرد (يجب تعديله ليتوافق مع الرد API/Web الخاص بك)
        return response()->json(['status' => true, 'message' => 'Feedback submitted successfully'], Response::HTTP_CREATED);
    }
    // 🚨🚨 نهاية الدالة المضافة 🚨🚨


    /**
     * 3. عرض تفاصيل تقييم محدد (Show Web View).
     */
    public function showWeb(Feedback $feedback)
    {
        // جلب التفاصيل الكاملة بما في ذلك العلاقات
        $feedback->load(['user', 'emergencyRequest', 'hospital']);

        // 🚨 تم توحيد اسم الـ View ليتوافق مع admin.feedback.show
        return view('admin.feedback.show', compact('feedback'));
    }

    /**
     * 4. حذف تقييم محدد (Destroy Web Action).
     */
    public function destroyWeb(Feedback $feedback)
    {
        $feedback->delete();

        // 🚨 تم توحيد اسم المسار ليتوافق مع admin.feedback.index
        return redirect()->route('admin.feedback.index')
                         ->with('success', 'تم حذف التقييم بنجاح.');
    }
}
