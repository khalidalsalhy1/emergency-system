<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\User;

class PatientRequest extends FormRequest
{
    public function authorize()
    {
        return true; 
    }

    public function rules()
    {
        // 1. تحديد ID المستخدم الحالي لتجاهله في قاعدة Unique
        // يجب أن يكون اسم المتغير في المسار هو 'patient'
        $userId = $this->route('patient') ? $this->route('patient')->id : null;

        // قواعد التفرد (Unique Rules)
        $phoneUniqueRule = Rule::unique('users', 'phone')->ignore($userId);
        $emailUniqueRule = Rule::unique('users', 'email')->ignore($userId); 
        $nationalIdUniqueRule = Rule::unique('users', 'national_id')->ignore($userId);
        
        // تحديد ما إذا كان الطلب إنشاء (POST)
        $isCreating = $this->isMethod('POST');
        
        // 2. تحديد القاعدة الأساسية لحقول السجل الطبي (required عند الإنشاء، nullable عند التعديل)
        $recordBaseRule = $isCreating ? 'required' : 'nullable';
        
        // قواعد كلمة المرور (مطلوبة للإنشاء، اختيارية للتعديل)
        $passwordRule = ['nullable', 'string', 'min:8', 'confirmed'];
        if ($isCreating) {
            $passwordRule[0] = 'required'; // مطلوبة عند الإنشاء
        }

        // 🚨 القواعد الأساسية للمريض (User Model) 🚨
        $rules = [
            'full_name'   => 'required|string|max:255',
            'phone'       => ['required', 'string', 'max:20', $phoneUniqueRule], 
            'email'       => ['nullable', 'email', 'max:255', $emailUniqueRule], 
            'national_id' => ['nullable', 'string', 'max:20', $nationalIdUniqueRule], 
            'status'      => 'required|string|in:active,inactive',
            'password'    => $passwordRule,
        ];

        // 🚨 إضافة قواعد السجل الطبي (MedicalRecord Model) 🚨
        // هذه القواعد تُضاف دائماً، وتصبح nullable تلقائياً عند التعديل
        $rules = array_merge($rules, [
            'birth_date'        => [$recordBaseRule, 'date', 'before:today'],
            'gender'            => [$recordBaseRule, 'string', 'in:Male,Female'],
            'blood_type'        => [$recordBaseRule, 'string', 'in:A+,A-,B+,B-,AB+,AB-,O+,O-'],
            'emergency_contact' => [$recordBaseRule, 'string', 'max:20'],
            
            // الحقول النصية الطويلة
            'medical_history'   => [$recordBaseRule, 'string'], 
            'allergies'         => [$recordBaseRule, 'string'],
            'current_medications' => [$recordBaseRule, 'string'],
            'notes'             => 'nullable|string', 

            // 🚨 حقل الأمراض المزمنة (Many-to-Many) 🚨
            // عند التعديل يجب أن تكون المصفوفة قابلة للإلغاء
            'diseases_ids'      => [$recordBaseRule, 'array'], 
            'diseases_ids.*'    => 'nullable|integer|exists:diseases,id',
        ]);
        
        return $rules;
    }

    public function attributes()
    {
        return [
            // ... (باقي المصفوفة لم يتغير)
            'full_name'   => 'الاسم الكامل',
            'phone'       => 'رقم الهاتف',
            'email'       => 'البريد الإلكتروني',
            'national_id' => 'الرقم الوطني',
            'status'      => 'حالة الحساب',
            'password'    => 'كلمة المرور',
            
            // حقول السجل الطبي
            'birth_date'        => 'تاريخ الميلاد',
            'gender'            => 'الجنس',
            'blood_type'        => 'فصيلة الدم',
            'emergency_contact' => 'رقم الطوارئ',
            'medical_history'   => 'التاريخ الطبي',
            'allergies'         => 'الحساسيات',
            'current_medications' => 'الأدوية الحالية',
            'diseases_ids'      => 'الأمراض المزمنة',
        ];
    }
}
