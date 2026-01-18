<?php

namespace App\Http\Controllers\Admin; 

use App\Http\Requests\HospitalRequest;
use App\Models\Hospital;
use App\Models\Location;
use App\Models\SystemLog; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller; 

class HospitalController extends Controller
{
    // *******************************************************************
    // **** دوال الواجهة (Web Views) ****
    // *******************************************************************

    /**
     * 1. يعرض واجهة قوائم المستشفيات (Web View) مع البحث.
     * GET /admin/hospitals 
     */
    public function indexWeb(Request $request) 
    {
        $query = Hospital::with('location');

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            
            $query->where(function ($q) use ($keyword) {
                $q->where('hospital_name', 'like', '%' . $keyword . '%')
                  ->orWhere('phone', 'like', '%' . $keyword . '%')
                  ->orWhere('emergency_number', 'like', '%' . $keyword . '%') // إضافة البحث برقم الطوارئ
                  ->orWhere('city', 'like', '%' . $keyword . '%')
                  ->orWhere('district', 'like', '%' . $keyword . '%');
            });
        }

        $hospitals = $query->latest()->paginate(15)->appends($request->query());

        return view('admin.hospitals.index', compact('hospitals'));
    }

    /**
     * 2. عرض نموذج إنشاء مستشفى جديد.
     * GET /admin/hospitals/create
     */
    public function createWeb()
    {
        return view('admin.hospitals.create');
    }

    /**
     * 3. معالجة بيانات إنشاء مستشفى (Store Web) - تتضمن المعاملات.
     * POST /admin/hospitals
     */
    public function storeWeb(HospitalRequest $request)
    {
        DB::beginTransaction();

        try {
            // 1. إنشاء المستشفى - تم إضافة emergency_phone و district
            $hospital = Hospital::create($request->only(
                'hospital_name', 
                'phone', 
                'emergency_number', 
                'city', 
                'district', 
                'email'
            ));

            // 2. إنشاء بيانات الموقع وربطها بالمستشفى
            $location = $hospital->location()->create($request->only('latitude', 'longitude', 'address'));
            
            // 🚨🚨 3. التوثيق في سجل النظام (إنشاء مستشفى) - تعريب بالكامل 🚨🚨
            SystemLog::log(
                Auth::id(), 
                'إنشاء مستشفى جديد', 
                'تم إنشاء المستشفى: ' . $hospital->hospital_name . ' في مديرية ' . $hospital->district . ' (رقم الطوارئ: ' . $hospital->emergency_number . ') (الهوية: ' . $hospital->id . ').'
            );
            // ----------------------------------------------------

            DB::commit();

            return redirect()->route('admin.hospitals.index')->with('success', 'تم تسجيل المستشفى وموقعه بنجاح.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'حدث خطأ أثناء حفظ المستشفى: ' . $e->getMessage());
        }
    }

    /**
     * 4. عرض تفاصيل المستشفى بالكامل (Web View).
     * GET /admin/hospitals/{hospital}
     */
    public function showWeb(Hospital $hospital)
    {
        // تحميل الموقع، المسؤولين، وحساب عدد طلبات الطوارئ المرتبطة بالمستشفى
        $hospital->load(['location', 'admins']);
        $hospital->loadCount('emergencyRequests');

        return view('admin.hospitals.show', compact('hospital'));
    }

    /**
     * 5. عرض نموذج تعديل مستشفى (Edit Web View).
     * GET /admin/hospitals/{hospital}/edit
     */
    public function editWeb(Hospital $hospital)
    {
        $hospital->load('location');
        return view('admin.hospitals.edit', compact('hospital'));
    }

    /**
     * 6. تحديث المستشفى (Update Web) - تتضمن المعاملات.
     * PUT/PATCH /admin/hospitals/{hospital}
     */
    public function updateWeb(HospitalRequest $request, Hospital $hospital)
    {
        DB::beginTransaction();

        try {
            // حفظ البيانات الأصلية للتوثيق قبل التحديث
            $originalData = $hospital->getOriginal();
            $originalLocation = $hospital->location ? $hospital->location->getOriginal() : [];
            
            // 1. تحديث المستشفى - تم شمل emergency_phone و district
            $hospital->update($request->only(
                'hospital_name', 
                'phone', 
                'emergency_number', 
                'city', 
                'district', 
                'email'
            ));

            // 2. تحديث/إنشاء بيانات الموقع
            $location = $hospital->location()->updateOrCreate(
                ['hospital_id' => $hospital->id], 
                $request->only(['latitude', 'longitude', 'address']) 
            );

            // 🚨🚨 3. التوثيق في سجل النظام (تحديث مستشفى) - تعريب بالكامل 🚨🚨
            
            // تتبع التغييرات في بيانات المستشفى
            $hospitalChanges = array_diff_assoc($hospital->getChanges(), $originalData);
            
            // تتبع التغييرات في بيانات الموقع
            $locationChanges = array_diff_assoc($location->getChanges(), $originalLocation);
            
            $logDetails = "تم تحديث بيانات المستشفى: {$hospital->hospital_name} (الهوية: {$hospital->id}). ";

            if (!empty($hospitalChanges) || !empty($locationChanges)) {
                $logDetails .= "التغييرات المسجلة: ";
                
                if (!empty($hospitalChanges)) {
                    $logDetails .= "في بيانات المستشفى (" . json_encode($hospitalChanges, JSON_UNESCAPED_UNICODE) . "). ";
                }
                if (!empty($locationChanges)) {
                    $logDetails .= "في بيانات الموقع (" . json_encode($locationChanges, JSON_UNESCAPED_UNICODE) . ").";
                }
            } else {
                $logDetails .= "لم يتم تسجيل أي تغييرات فعلية.";
            }

            SystemLog::log(
                Auth::id(), 
                'تحديث بيانات مستشفى', 
                $logDetails
            );
            // ----------------------------------------------------
            
            DB::commit();

            return redirect()->route('admin.hospitals.index')->with('success', 'تم تحديث بيانات المستشفى بنجاح.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'حدث خطأ أثناء تحديث المستشفى: ' . $e->getMessage());
        }
    }

    /**
     * 7. حذف المستشفى (Destroy Web) - تتضمن المعاملات.
     * DELETE /admin/hospitals/{hospital}
     */
    public function destroyWeb(Hospital $hospital)
    {
        DB::beginTransaction();
        
        $hospitalId = $hospital->id;
        $hospitalName = $hospital->hospital_name;

        try {
            
            // 🚨🚨 1. التوثيق في سجل النظام (حذف مستشفى) - تعريب بالكامل 🚨🚨
            SystemLog::log(
                Auth::id(), 
                'حذف مستشفى', 
                'تم بدء عملية حذف المستشفى: ' . $hospitalName . ' (الهوية: ' . $hospitalId . ').'
            );
            // ----------------------------------------------------
            
            $hospital->delete();
            
            DB::commit();

            return redirect()->route('admin.hospitals.index')->with('success', 'تم حذف المستشفى بنجاح.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // التعامل مع قيود المفاتيح الأجنبية
            if (str_contains($e->getMessage(), 'foreign key')) {
                return back()->with('error', 'لا يمكن حذف المستشفى لأنه مرتبط ببيانات أخرى (مثل طلبات طوارئ أو مسؤولي مستشفيات).');
            }
            return back()->with('error', 'حدث خطأ أثناء حذف المستشفى: ' . $e->getMessage());
        }
    }
}
