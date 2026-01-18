<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HospitalResource extends JsonResource
{
    /**
     * تحويل المورد (Hospital Model) إلى مصفوفة JSON.
     * هذا يضمن أن هيكل الاستجابة ثابت ويشمل بيانات الموقع المرتبط.
     */
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'hospital_name'    => $this->hospital_name, // استخدام hospital_name
            'phone'            => $this->phone,
            'emergency_number' => $this->emergency_number,
            'city'             => $this->city,
            'district'         => $this->district,
            // 'email'         => $this->email ?? null, // إذا كان الحقل موجوداً
            // 'status'        => $this->status ?? 'active', // إذا كان الحقل موجوداً

            'created_at'       => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at'       => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null,
            
            // دمج بيانات الموقع الجغرافي (يتم إدراجها فقط إذا تم تحميل العلاقة مسبقاً)
            'location' => $this->whenLoaded('location', function () {
                // التحقق من وجود الموقع قبل محاولة الوصول إليه
                if ($this->location) {
                    return [
                        'id'        => $this->location->id,
                        'latitude'  => (float) $this->location->latitude,
                        'longitude' => (float) $this->location->longitude,
                        'address'   => $this->location->address,
                    ];
                }
                return null;
            }),
        ];
    }
}
