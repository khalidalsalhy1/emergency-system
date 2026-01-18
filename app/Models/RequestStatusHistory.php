<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestStatusHistory extends Model
{
     use HasFactory;
    protected $table = 'request_status_history';

    protected $fillable = [
        'emergency_request_id',
        'status',
        'changed_at',
        'changed_by_user_id', 
        'reason',             
    ];

    // 🎯 التعديل: استخدام $casts بدلاً من $dates لـ changed_at لضمان التعامل معه ككائن Carbon
    protected $casts = [
        'changed_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | العلاقات Relationships
    |--------------------------------------------------------------------------
    */

    // كل سجل حالة ينتمي إلى طلب إسعاف واحد
    public function emergencyRequest()
    {
        return $this->belongsTo(EmergencyRequest::class, 'emergency_request_id');
    }

    // علاقة من قام بتغيير الحالة (مدير النظام)
    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by_user_id');
    }

    /*
    |--------------------------------------------------------------------------
    | دوال مساعدة Helper Methods
    |--------------------------------------------------------------------------
    */

    // هل حالة الطلب هي "قيد الانتظار"؟
    public function isPending()
    {
        return $this->status === 'pending';
    }

    // هل حالة الطلب "قيد المعالجة"؟
    public function isInProgress()
    {
        return $this->status === 'in_progress';
    }

    // هل حالة الطلب "مكتمل"؟
    public function isCompleted()
    {
        return $this->status === 'completed';
    }
}
