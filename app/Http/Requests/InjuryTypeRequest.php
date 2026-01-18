<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InjuryTypeRequest extends FormRequest
{
    /**
     * تحديد ما إذا كان المستخدم مصرحًا له بتقديم هذا الطلب.
     */
    public function authorize()
    {
        return true; // 🟢 تم التعديل
    }

    /**
     * قواعد التحقق التي تنطبق على الطلب.
     */
    public function rules()
    {
        // قاعدة التفرد (Unique) لاسم الإصابة
        $uniqueRule = Rule::unique('injury_types', 'injury_name');
        
        // 💡 منطق التحقق: إذا كان الطلب UPDATE (PUT/PATCH)، نتجاهل السجل الحالي
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            // يتم الوصول إلى الـ ID عبر route model binding 
            $uniqueRule->ignore($this->injuryType->id ?? null);
        }

        return [
            // 🚨 قواعد التحقق المطبقة
            'injury_name' => ['required', 'string', 'max:255', $uniqueRule],
            'description' => 'nullable|string',
        ];
    }

    /**
     * تخصيص أسماء الحقول لعرضها في رسائل الخطأ.
     */
    public function attributes()
    {
        return [
            'injury_name' => 'اسم الإصابة',
            'description' => 'الوصف',
        ];
    }
}
