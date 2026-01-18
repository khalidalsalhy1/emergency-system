<?php

namespace App\Notifications;

use App\Models\EmergencyRequest;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Route; 
use App\Enums\EmergencyRequestStatus; 

class EmergencyRequestStatusChanged extends Notification
{
    use Queueable;

    protected $emergencyRequest;
    protected $newStatus;
    protected $hospitalAdmin;

    public function __construct(EmergencyRequest $emergencyRequest, string $newStatus, User $hospitalAdmin)
    {
        $this->emergencyRequest = $emergencyRequest;
        $this->newStatus = $newStatus;
        $this->hospitalAdmin = $hospitalAdmin;
    }

    public function via(object $notifiable): array
    {
        return ['database']; 
    }
    
    /**
     * الحصول على تمثيل الإشعار لقناة قاعدة البيانات (متوافق مع الحلول النهائية).
     */
    public function toDatabase(object $notifiable): array
    {
        // 1. الأولوية للمستشفى المسند للطلب
        $hospital = $this->emergencyRequest->hospital;
        
        // 2. إذا كان الطلب غير مُسند، نستخدم المستشفى المرتبط بالمدير
        if (!$hospital) {
            $hospital = $this->hospitalAdmin->hospital;
        }

        // 3. تحديد الاسم والـ ID النهائيين باستخدام اسم العمود الصحيح 'hospital_name'
        $hospitalName = $hospital->hospital_name ?? 'مستشفى غير معروف'; 
        $hospitalId = $hospital->id ?? null;
        
        // 4. ترجمة/تنسيق نصوص الحالات
        $translatedStatus = $this->translateStatus($this->newStatus);

        // النص الرئيسي للإشعار 
        $messageText = "طلبك للطوارئ رقم {$this->emergencyRequest->id} تم تحديث حالته إلى '{$translatedStatus}' بواسطة مستشفى {$hospitalName}.";
        
        // 5. الحصول على العنوان والرابط
        $actionUrl = $this->getPatientRequestShowRoute(); 
        $notificationTitle = $this->getTitleForStatus($this->newStatus);

        // 6. تجميع البيانات الإضافية التي سيتم تخزينها كـ JSON في حقل 'message'
        $extraData = [
            'request_id' => $this->emergencyRequest->id,
            'status' => $this->newStatus,
            'hospital_id' => $hospitalId,
            'hospital_name' => $hospitalName, 
            'url' => $actionUrl,
        ];
        
        // 7. دمج الرسالة النصية مع البيانات الإضافية في payload واحد (لن يتم الترميز هنا، ولكن الكنترولر يستخدم هذه البيانات)
        // ملاحظة: لا نستخدم JSON_UNESCAPED_UNICODE هنا لأن الكنترولر يقوم بالترميز النهائي.
        // يتم إرجاع مصفوفة عادية تحتوي على الحقول الأساسية والنص العربي غير المشفر (baseMessage).
        

        return [
            // 🚨🚨 الحقول الأساسية الأربعة فقط التي يتوقعها جدولك 🚨🚨
            'title' => $notificationTitle, 
            'message' => $messageText, // إرجاع النص الأساسي غير المُرمز
            'is_read' => 0, 
            'type' => 'emergency_request_status', 
            
            // إضافة البيانات الإضافية مباشرة ليتم تجميعها في الكنترولر (خطوة ضرورية)
            'request_id' => $extraData['request_id'],
            'status' => $extraData['status'],
            'hospital_id' => $extraData['hospital_id'],
            'hospital_name' => $extraData['hospital_name'],
            'url' => $extraData['url'],
        ];
    }
    
    /**
     * دالة مساعدة للحصول على عنوان الإشعار بناءً على الحالة.
     */
    protected function getTitleForStatus(string $status): string
    {
        return match ($status) {
            EmergencyRequestStatus::CANCELED => 'نأسف، تم إلغاء طلبك للطوارئ', 
            EmergencyRequestStatus::IN_PROGRESS => 'الإسعاف في الطريق إليك الآن', 
            EmergencyRequestStatus::COMPLETED => 'اكتملت عملية الطوارئ',
            default => 'تم تحديث حالة طلبك للطوارئ',
        };
    }
    
    /**
     * دالة مساعدة لترجمة الحالة.
     */
    protected function translateStatus(string $status): string
    {
        return match ($status) {
            EmergencyRequestStatus::PENDING => 'قيد الانتظار',
            EmergencyRequestStatus::CANCELED => 'ملغي', 
            EmergencyRequestStatus::IN_PROGRESS => 'قيد المعالجة (في الطريق)', 
            EmergencyRequestStatus::COMPLETED => 'مكتمل',
            default => $status,
        };
    }

    /**
     * دالة مساعدة لإنشاء رابط API المسمى.
     */
    protected function getPatientRequestShowRoute(): string
    {
        // يجب أن يكون لديك مسار مسمى (named route) في routes/api.php
        if (Route::has('patient.emergency_requests.show')) {
            return route('patient.emergency_requests.show', $this->emergencyRequest->id);
        }
        
        // رابط API مباشر كاحتياطي إذا لم يكن المسار مسمى
        return "/api/patient/emergency/{$this->emergencyRequest->id}";
    }
}
