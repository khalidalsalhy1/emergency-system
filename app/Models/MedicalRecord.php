<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class MedicalRecord extends Model
{
    use HasFactory;

    /**
     * الأعمدة المسموح بالتعديل عليها
     */
    protected $fillable = [
        'user_id',
        'birth_date',
        'gender',
        'blood_type',
        'emergency_contact',
        'medical_history',
        'allergies',
        'current_medications',
        'notes',
    ];

    /**
     * التحويل التلقائي للحقول
     */
    protected $casts = [
        'birth_date' => 'date',
    ];

    /**
     * العلاقة: السجل الطبي ← ينتمي إلى ← المستخدم
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * حساب العمر تلقائياً من تاريخ الميلاد
     */
    public function getAgeAttribute()
    {
        if (!$this->birth_date) {
            return null;
        }

        return Carbon::parse($this->birth_date)->age;
    }
}