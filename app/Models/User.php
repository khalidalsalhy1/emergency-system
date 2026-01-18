<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | Role Constants
    |--------------------------------------------------------------------------
    */
    const ROLE_PATIENT        = 'patient';
    const ROLE_HOSPITAL_ADMIN = 'hospital_admin';
    const ROLE_SYSTEM_ADMIN   = 'system_admin';

    /*
    |--------------------------------------------------------------------------
    | Fillable Fields
    |--------------------------------------------------------------------------
    */
    protected $fillable = [
        'full_name',
        'phone',
        'email',
        'password',
        'national_id',
        'user_role',
        'hospital_id',
        'status',
    ];

    /*
    |--------------------------------------------------------------------------
    | Hidden Fields
    |--------------------------------------------------------------------------
    */
    protected $hidden = [
        'password',
    ];

    /*
    |--------------------------------------------------------------------------
    | Casting
    |--------------------------------------------------------------------------
    */
    protected $casts = [
        'phone_verified_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Mutators
    |--------------------------------------------------------------------------
    */
    // 🚨 تم إزالة setPasswordAttribute بالكامل لتجنب تضارب التشفير
    /*
    public function setPasswordAttribute($value)
    {
        if ($value !== null) {
            $this->attributes['password'] = bcrypt(trim($value));
        }
    }
    */

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // المستشفى الذي ينتمي إليه (إذا كان hospital admin)
    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    // ملف المريض الطبي
    public function medicalRecord()
    {
        return $this->hasOne(MedicalRecord::class, 'user_id');
    }

    // الأمراض المرتبطة بالمريض
    public function diseases()
    {
        return $this->belongsToMany(Disease::class, 'disease_user', 'user_id', 'disease_id');
    }

    // طلبات الطوارئ الخاصة بالمريض
    public function emergencyRequests()
    {
        return $this->hasMany(EmergencyRequest::class, 'user_id');
    }

    // الطلبات الموجهة للمستشفى (إذا كان مسؤول مستشفى)
    public function hospitalEmergencyRequests()
    {
        return $this->hasMany(EmergencyRequest::class, 'hospital_id', 'hospital_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers - Role Check
    |--------------------------------------------------------------------------
    */

    public function isPatient()
    {
        return $this->user_role === self::ROLE_PATIENT;
    }

    public function isHospitalAdmin()
    {
        return $this->user_role === self::ROLE_HOSPITAL_ADMIN;
    }

    public function isSystemAdmin()
    {
        return $this->user_role === self::ROLE_SYSTEM_ADMIN;
    }

    public function hasRole($role)
    {
        return $this->user_role === $role;
    }
}
