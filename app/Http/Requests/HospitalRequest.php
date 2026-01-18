<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HospitalRequest extends FormRequest
{
    /**
     * هل المستخدم مسموح له بإجراء هذا الطلب؟
     */
    public function authorize(): bool
    {
        // بما أننا نستخدم Middleware 'role:system_admin' في المسارات، يمكننا إرجاع true هنا.
        return true;
    }

    /**
     * قواعد التحقق التي تنطبق على هذا الطلب.
     */
    public function rules(): array
    {
        // لتجاهل المستشفى الحالي عند تحديث الهاتف في دالة update
        $hospitalId = $this->route('hospital') ? $this->route('hospital')->id : null;
        
        return [
            // قواعد المستشفى (Hospital Model)
            'hospital_name'    => ['required', 'string', 'max:255'],
            // الهاتف: يجب أن يكون فريداً في جدول hospitals باستثناء المستشفى الحالي عند التحديث
            'phone'            => ['required', 'string', 'max:20', 'unique:hospitals,phone,' . $hospitalId], 
            'emergency_number' => ['nullable', 'string', 'max:20'],
            'city'             => ['required', 'string', 'max:100'],
            'district'         => ['required', 'string', 'max:100'],
            
            // قواعد الموقع (Location Model)
            'latitude'         => ['required', 'numeric', 'between:-90,90'],
            'longitude'        => ['required', 'numeric', 'between:-180,180'],
            'address'          => ['nullable', 'string', 'max:500'],
        ];
    }
    
    /**
     * رسائل الخطأ المخصصة.
     */
    public function messages(): array
    {
        return [
            'phone.unique' => 'رقم الهاتف هذا مسجل لمستشفى آخر بالفعل.',
            // ... يمكنك إضافة رسائل أخرى حسب الحاجة
        ];
    }
}
