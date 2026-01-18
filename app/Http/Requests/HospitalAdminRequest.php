<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\User;

class HospitalAdminRequest extends FormRequest
{
    /**
     * تحديد ما إذا كان المستخدم مصرحًا له بتقديم هذا الطلب.
     */
    public function authorize()
    {
        return true; 
    }

    /**
     * قواعد التحقق التي تنطبق على الطلب.
     */
    public function rules()
    {
        // 1. تحديد ID المستخدم الحالي لتجاهله في قاعدة Unique (لعمليات التعديل)
        // اسم المتغير المستخدم في المسار هو 'hospital_admin'
        $userId = $this->route('hospital_admin') ? $this->route('hospital_admin')->id : null;

        // قواعد التفرد الأساسية (Unique Rules)
        $phoneUniqueRule = Rule::unique('users', 'phone')->ignore($userId);
        $emailUniqueRule = Rule::unique('users', 'email')->ignore($userId); 
        $nationalIdUniqueRule = Rule::unique('users', 'national_id')->ignore($userId);
        
        // 2. قواعد الرقم الوطني: اختياري دائمًا، ولكن فريد إذا تم إدخاله
        $nationalIdRules = ['nullable', 'string', 'max:20', $nationalIdUniqueRule];
        
        // 3. قواعد كلمة المرور (مطلوبة للإنشاء، اختيارية للتعديل)
        $passwordRule = ['nullable', 'string', 'min:8', 'confirmed'];
        if ($this->isMethod('POST')) {
            $passwordRule[0] = 'required'; // جعلها مطلوبة عند الإنشاء
        }

        return [
            'full_name'   => 'required|string|max:255',
            // رقم الهاتف: مطلوب وفريد (حقل المصادقة)
            'phone'       => ['required', 'string', 'max:20', $phoneUniqueRule], 
            // البريد الإلكتروني: اختياري
            'email'       => ['nullable', 'email', 'max:255', $emailUniqueRule], 
            
            // 💡 الرقم الوطني: اختياري (nullable)
            'national_id' => $nationalIdRules, 
            
            'hospital_id' => 'required|integer|exists:hospitals,id',
            'status'      => 'required|string', 
            
            'password'    => $passwordRule,
        ];
    }

    /**
     * تخصيص أسماء الحقول لعرضها في رسائل الخطأ.
     */
    public function attributes()
    {
        return [
            'full_name'   => 'الاسم الكامل',
            'phone'       => 'رقم الهاتف',
            'email'       => 'البريد الإلكتروني',
            'national_id' => 'الرقم الوطني',
            'hospital_id' => 'المستشفى المرتبط',
            'password'    => 'كلمة المرور',
        ];
    }
}
