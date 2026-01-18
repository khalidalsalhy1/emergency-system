<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    /**
     * الأعمدة التي يمكن تعبئتها
     */
    protected $fillable = [
        'latitude',
        'longitude',
        'address',
        'user_id',
        'hospital_id',
    ];

    /**
     * علاقة: الموقع يتبع لمريض (اختياري)
     * Patient hasMany Locations
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * علاقة: الموقع يتبع لمستشفى (اختياري)
     * Hospital hasOne Location
     */
    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    /**
     * علاقة: الموقع مرتبط بعدة طلبات إسعاف
     * One Location → Many Emergency Requests
     */
    public function emergencyRequests()
    {
        return $this->hasMany(EmergencyRequest::class);
    }
}