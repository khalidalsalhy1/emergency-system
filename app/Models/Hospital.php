<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{
    use HasFactory;

    protected $fillable = [
        'hospital_name',
        'phone',
        'emergency_number',
        'city',
        'district',
        
        
    ];

    /**
     * علاقة: المستشفى → مدراء المستشفى (users)
     * Hospital hasMany Hospital Admins
     */
    public function admins()
    {
        return $this->hasMany(User::class, 'hospital_id')
                    ->where('user_role', 'hospital_admin');
    }

    /**
     * علاقة: المستشفى → الطلبات
     * Hospital hasMany Emergency Requests
     */
    public function emergencyRequests()
    {
        return $this->hasMany(EmergencyRequest::class);
    }

    /**
     * علاقة: المستشفى → الموقع
     * Hospital hasOne Location
     */
    public function location()
    {
        return $this->hasOne(Location::class);
    }
}