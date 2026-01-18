<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\InjuryType; 
use App\Models\Location;
use App\Models\Hospital;
use App\Models\RequestStatusHistory; // 🚨 يجب استيراد هذا النموذج لـ statusHistory

class EmergencyRequest extends Model
{
     use HasFactory;
    protected $table = 'emergency_requests';

    // الحقول التي يسمح بملؤها
    protected $fillable = [
        'user_id',
        'injury_type_id',
        'location_id',
        'hospital_id',
        'request_type',
        'description',
        'status',
        'completed_at',
        'updated_by',
        
    ];

    // تحويل التاريخ تلقائياً من JSON إلى Date object
    protected $dates = [
        'completed_at',
        'created_at',
        'updated_at',
    ];

    /*
    |--------------------------------------------------------------------------
    | العلاقات Relationships
    |--------------------------------------------------------------------------
    */

    // علاقة الطلب ← المستخدم (المريض)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // 🚨🚨 التعديل الذي حل مشكلة الخطأ: إضافة اسم مستعار للعلاقة
    public function patient()
    {
        return $this->user();
    }
    
    // 🚨🚨 التعديل الحاسم: إضافة علاقة المحدث (Updater)
    // تجلب بيانات المستخدم (المسؤول) الذي قام بآخر تحديث
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // علاقة الطلب ← نوع الإصابة
    public function injuryType()
    {
        return $this->belongsTo(InjuryType::class);
    }

    // علاقة الطلب ← الموقع
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    // علاقة الطلب ← المستشفى (اختياري)
    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    // علاقة الطلب ← سجل تغيير الحالة
    public function statusHistory()
    {
        return $this->hasMany(RequestStatusHistory::class, 'emergency_request_id');
    }

    /*
    |--------------------------------------------------------------------------
    | دوال مساعدة Helper Methods
    |--------------------------------------------------------------------------
    */

    // هل الطلب هو طلب إسعاف؟
    public function isAmbulanceRequest()
    {
        return $this->request_type === 'DISPATCH';
    }

    // هل الطلب عبارة عن بلاغ فقط؟
    public function isReport()
    {
        return $this->request_type === 'NOTIFY';
    }

    // هل الطلب بانتظار قبول المستشفى؟
    public function isPending()
    {
        return $this->status === 'pending';
    }

    // هل الإسعاف في الطريق؟
    public function isInProgress()
    {
        return $this->status === 'in_progress';
    }

    // هل الطلب مكتمل؟
    public function isCompleted()
    {
        return $this->status === 'completed';
    }
}
