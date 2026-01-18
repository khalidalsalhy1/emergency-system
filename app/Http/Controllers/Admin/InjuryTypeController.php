<?php

namespace App\Http\Controllers\Admin;

use App\Models\InjuryType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
use App\Http\Requests\InjuryTypeRequest; 
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\Rule;

class InjuryTypeController extends Controller
{
    // *******************************************************************
    // **** دوال الواجهة (Web Views) ****
    // *******************************************************************

    /**
     * 1. يعرض واجهة قوائم أنواع الإصابات (Web View) مع التصفح.
     * GET /admin/injury-types 
     */
    public function indexWeb()
    {
        $injuryTypes = InjuryType::orderBy('injury_name')->paginate(15);
        
        return view('admin.injury_types.index', compact('injuryTypes')); 
    }

    /**
     * 2. عرض نموذج إنشاء نوع إصابة جديد.
     * GET /admin/injury-types/create
     */
    public function createWeb()
    {
        return view('admin.injury_types.create'); 
    }

    /**
     * 3. معالجة بيانات إنشاء نوع الإصابة من الواجهة.
     * POST /admin/injury-types
     */
    public function storeWeb(InjuryTypeRequest $request) 
    {
        InjuryType::create($request->validated());

        return redirect()->route('admin.injury_types.index')->with('success', 'تم إنشاء نوع الإصابة بنجاح.');
    }
    
    /**
     * 4. عرض نموذج تعديل نوع إصابة.
     * GET /admin/injury-types/{injuryType}/edit
     */
    public function editWeb(InjuryType $injuryType)
    {
        return view('admin.injury_types.edit', compact('injuryType'));
    }

    /**
     * 5. تحديث نوع إصابة محدد (Update Web).
     * PUT/PATCH /admin/injury-types/{injuryType}
     */
    public function updateWeb(InjuryTypeRequest $request, InjuryType $injuryType)
    {
        $injuryType->update($request->validated());

        return redirect()->route('admin.injury_types.index')->with('success', 'تم تحديث نوع الإصابة بنجاح.');
    }

    /**
     * 6. حذف نوع إصابة محدد (Destroy Web).
     * DELETE /admin/injury-types/{injuryType}
     */
    public function destroyWeb(InjuryType $injuryType)
    {
        // منطق حماية الحذف
        if (method_exists($injuryType, 'emergencyRequests') && $injuryType->emergencyRequests()->exists()) {
            return back()->with('error', 'لا يمكن حذف نوع الإصابة لأنه مرتبط بطلبات طوارئ موجودة.');
        }
        
        $injuryType->delete();

        return redirect()->route('admin.injury_types.index')->with('success', 'تم حذف نوع الإصابة بنجاح.');
    }
}
