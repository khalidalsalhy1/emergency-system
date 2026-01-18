<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request; // 💡 استيراد Request لجلب IP

class SystemLog extends Model
{
    protected $table = 'system_logs';

    protected $fillable = [
        'user_id',
        'action',
        'details',
        'ip_address', // 💡 (إضافة افتراضية لحقل شائع مطلوب في السجلات)
        'type',       // 💡 (إضافة افتراضية لحقل شائع مطلوب في السجلات)
        // أضف هنا أي حقل آخر مطلوب في جدول قاعدة البيانات لديك.
    ];

    /*
    |--------------------------------------------------------------------------
    | العلاقات Relationships
    |--------------------------------------------------------------------------
    */

    // السجل متعلق بمستخدم واحد (الذي قام بالفعل)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /*
    |--------------------------------------------------------------------------
    | دوال مساعدة Helper Methods
    |--------------------------------------------------------------------------
    */

    /**
     * إنشاء سجل جديد بسهولة مع دعم الحقول الإضافية التلقائية.
     * @param int $user_id هوية المستخدم الذي قام بالإجراء (Auth::id())
     * @param string $action وصف الإجراء (مثل 'Hospital Admin Update')
     * @param string|null $details تفاصيل إضافية (مثل التغييرات أو JSON)
     * @param array $extraData بيانات إضافية لتمريرها إلى الدالة create
     */
    public static function log($user_id, $action, $details = null, $extraData = [])
    {
        // 🚨 1. تجميع البيانات الأساسية 🚨
        $data = [
            'user_id' => $user_id,
            'action'  => $action,
            'details' => $details,
        ];
        
        // 🚨 2. إضافة حقول تلقائية إذا كانت موجودة في الـ $fillable
        if (in_array('ip_address', (new self())->getFillable())) {
            $data['ip_address'] = Request::ip();
        }
        
        if (in_array('type', (new self())->getFillable()) && !isset($extraData['type'])) {
             // تعيين قيمة افتراضية لنوع السجل إذا كان مطلوباً ولم يتم تمريره
            $data['type'] = 'ADMIN_ACTION'; 
        }

        // 3. دمج البيانات الأساسية مع أي بيانات إضافية تمرر من الكنترولر
        $finalData = array_merge($data, $extraData);

        return self::create($finalData);
    }
}
