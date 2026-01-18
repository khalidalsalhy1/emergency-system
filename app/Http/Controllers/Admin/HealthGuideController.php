<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller; 
use App\Models\HealthGuide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage; // لإدارة الصور
use Illuminate\Validation\Rule;

class HealthGuideController extends Controller
{
    /**
     * 1. عرض جميع الإرشادات الصحية مع التصفح (Web Index).
     */
    public function indexWeb()
    {
        $guides = HealthGuide::latest()->paginate(15);
        
        // يعرض ملف resources/views/admin/health_guides/index.blade.php
        return view('admin.health_guides.index', compact('guides'));
    }

    /**
     * 2. عرض نموذج إنشاء إرشاد صحي جديد (Web Create).
     */
    public function createWeb()
    {
        return view('admin.health_guides.create');
    }

    /**
     * 3. إنشاء إرشاد صحي جديد وحفظه (Web Store).
     */
    public function storeWeb(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255|unique:health_guides,title',
            'content' => 'required|string',
            'category' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // التعامل مع تحميل الصورة
        if ($request->hasFile('image')) {
            // 💡 التعديل 1: التخزين باستخدام القرص 'public_direct'
            // ' ' (فارغ) يعني التخزين مباشرة في المسار المحدد في filesystems.php (وهو public/health_guides/)
            $imagePath = $request->file('image')->store('', 'public_direct');
            $validatedData['image'] = $imagePath;
        }

        HealthGuide::create($validatedData);

        return redirect()->route('admin.health_guides.index')
                         ->with('success', 'تم إضافة الإرشاد الصحي بنجاح.');
    }

    /**
     * 4. عرض تفاصيل إرشاد صحي محدد (Web Show).
     */
    public function showWeb(HealthGuide $healthGuide)
    {
        return view('admin.health_guides.show', compact('healthGuide'));
    }

    /**
     * 5. عرض نموذج تحديث إرشاد صحي محدد (Web Edit).
     */
    public function editWeb(HealthGuide $healthGuide)
    {
        return view('admin.health_guides.edit', compact('healthGuide'));
    }


    /**
     * 6. تحديث إرشاد صحي محدد (Web Update).
     */
    public function updateWeb(Request $request, HealthGuide $healthGuide)
    {
        $validatedData = $request->validate([
            'title' => ['required', 'string', 'max:255', Rule::unique('health_guides', 'title')->ignore($healthGuide->id)],
            'content' => 'required|string',
            'category' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        // التعامل مع تحديث الصورة
        if ($request->hasFile('image')) {
            // حذف الصورة القديمة إذا كانت موجودة
            if ($healthGuide->image) {
                // 💡 التعديل 2: حذف الصورة القديمة من القرص 'public_direct'
                Storage::disk('public_direct')->delete($healthGuide->image);
            }
            // 💡 التعديل 3: التخزين باستخدام القرص 'public_direct'
            $validatedData['image'] = $request->file('image')->store('', 'public_direct');
        }
        
        $healthGuide->update($validatedData);

        return redirect()->route('admin.health_guides.index')
                         ->with('success', 'تم تحديث الإرشاد الصحي بنجاح.');
    }

    /**
     * 7. حذف إرشاد صحي محدد (Web Destroy).
     */
    public function destroyWeb(HealthGuide $healthGuide)
    {
        // حذف الصورة المرتبطة إذا كانت موجودة
        if ($healthGuide->image) {
            // 💡 التعديل 4: حذف الصورة من القرص 'public_direct'
            Storage::disk('public_direct')->delete($healthGuide->image);
        }
        
        $healthGuide->delete();

        return redirect()->route('admin.health_guides.index')
                         ->with('success', 'تم حذف الإرشاد الصحي بنجاح.');
    }
}
