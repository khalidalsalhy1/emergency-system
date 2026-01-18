<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Disease;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class DiseaseController extends Controller
{
    /**
     * 1. عرض جميع الأمراض المزمنة (Index).
     */
    public function index()
    {
        $diseases = Disease::orderBy('disease_name', 'asc')->paginate(15);
        
        return view('admin.diseases.index', compact('diseases'));
    }

    /**
     * 2. عرض نموذج إنشاء مرض جديد (Create).
     */
    public function create()
    {
        return view('admin.diseases.create');
    }

    /**
     * 3. تخزين مرض جديد (Store).
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'disease_name' => 'required|string|max:255|unique:diseases,disease_name',
            'description' => 'nullable|string|max:1000',
        ], [
            'disease_name.required' => 'اسم المرض مطلوب.',
            'disease_name.unique' => 'هذا المرض مسجل بالفعل.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Disease::create($request->all());

        return redirect()->route('admin.diseases.index')
                         ->with('success', 'تم إضافة المرض المزمن بنجاح.');
    }

    /**
     * 4. عرض نموذج تعديل مرض (Edit).
     */
    public function edit(Disease $disease)
    {
        return view('admin.diseases.edit', compact('disease'));
    }

    /**
     * 5. تحديث مرض (Update).
     */
    public function update(Request $request, Disease $disease)
    {
        $validator = Validator::make($request->all(), [
            'disease_name' => 'required|string|max:255|unique:diseases,disease_name,' . $disease->id,
            'description' => 'nullable|string|max:1000',
        ], [
            'disease_name.required' => 'اسم المرض مطلوب.',
            'disease_name.unique' => 'هذا المرض مسجل بالفعل.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $disease->update($request->all());

        return redirect()->route('admin.diseases.index')
                         ->with('success', 'تم تحديث بيانات المرض بنجاح.');
    }

    /**
     * 6. حذف مرض (Destroy).
     */
    public function destroy(Disease $disease)
    {
        // 🚨 ملاحظة: يجب التأكد هنا من عدم وجود أي مستخدم مرتبط بهذا المرض
        // أو استخدام خاصية الحذف المتتالي (Cascade Delete) في قاعدة البيانات.
        try {
            $disease->delete();
            return redirect()->route('admin.diseases.index')
                             ->with('success', 'تم حذف المرض المزمن بنجاح.');
        } catch (\Exception $e) {
            return redirect()->back()
                             ->with('error', 'لا يمكن حذف المرض لوجود سجلات مرتبطة به في النظام.');
        }
    }
}
