<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Hospital;
use App\Models\SystemLog;
use App\Http\Requests\HospitalAdminRequest; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; 
use Symfony\Component\HttpFoundation\Response;

class HospitalAdminManagementController extends Controller
{
    /**
     * 1. عرض قائمة مسؤولي المستشفيات (Index Web View).
     * GET /admin/hospital-admins
     */
    public function indexWeb()
    {
        // جلب مسؤولي المستشفيات فقط، مع التصفح وعرض اسم المستشفى المرتبط
        $hospitalAdmins = User::where('user_role', User::ROLE_HOSPITAL_ADMIN)
                              ->with('hospital:id,hospital_name')
                              ->orderBy('full_name')
                              ->paginate(15);

        return view('admin.hospital_admins.index', compact('hospitalAdmins'));
    }

    /**
     * 2. عرض نموذج إنشاء مسؤول مستشفى جديد (Create Web View).
     * GET /admin/hospital-admins/create
     */
    public function createWeb()
    {
        // نحتاج قائمة المستشفيات لإتاحة الربط
        $hospitals = Hospital::select('id', 'hospital_name')->orderBy('hospital_name')->get();

        return view('admin.hospital_admins.create', compact('hospitals'));
    }

    /**
     * 3. معالجة بيانات إنشاء مسؤول مستشفى (Store Web).
     * POST /admin/hospital-admins
     */
    public function storeWeb(HospitalAdminRequest $request) 
    {
        // 1. التحقق من البيانات (تم بواسطة HospitalAdminRequest)
        $data = $request->validated();
        
        // 2. تعيين الدور وتشفير كلمة المرور
        $data['user_role'] = User::ROLE_HOSPITAL_ADMIN;
        $data['password'] = Hash::make($data['password']);

        // 3. إنشاء المستخدم
        $admin = User::create($data);

        // 🚨 4. التوثيق في سجل النظام (System Log) - معرب بالكامل 🚨
        SystemLog::log(
            Auth::id(), 
            'Hospital Admin Creation', 
            'تم إنشاء مسؤول مستشفى جديد: ' . $admin->full_name . ' (الهوية: ' . $admin->id . ') وتم ربطه بالمستشفى رقم: ' . $admin->hospital_id
        );

        return redirect()->route('admin.hospital_admins.index')->with('success', 'تم إنشاء مسؤول المستشفى بنجاح.');
    }

    /**
     * 4. عرض نموذج تعديل مسؤول مستشفى (Edit Web View).
     * GET /admin/hospital-admins/{hospital_admin}/edit
     */
    public function editWeb(User $hospital_admin)
    {
        // التحقق للتأكد من أن المستخدم المُعطى هو بالفعل مدير مستشفى
        if ($hospital_admin->user_role !== User::ROLE_HOSPITAL_ADMIN) {
            return redirect()->route('admin.hospital_admins.index')->with('error', 'السجل المحدد ليس لمدير مستشفى.');
        }

        $hospitals = Hospital::select('id', 'hospital_name')->orderBy('hospital_name')->get();

        return view('admin.hospital_admins.edit', compact('hospital_admin', 'hospitals'));
    }

    /**
     * 5. معالجة طلب تحديث مسؤول مستشفى (Update Web).
     * PUT/PATCH /admin/hospital-admins/{hospital_admin}
     */
    public function updateWeb(HospitalAdminRequest $request, User $hospital_admin)
    {
        // 1. التحقق من البيانات
        $data = $request->validated();
        
        // 2. معالجة كلمة المرور: إذا كانت فارغة، لا نغيرها
        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }
        
        // 🚨 حفظ البيانات الأصلية للتوثيق قبل التحديث
        $originalData = $hospital_admin->getOriginal();
        $oldHospitalId = $originalData['hospital_id'] ?? 'غير محدد';
        $oldFullName = $originalData['full_name'] ?? 'غير محدد';

        // 3. تحديث البيانات
        $hospital_admin->update($data);
        
        // 🚨 4. التوثيق في سجل النظام (System Log) - معرب بالكامل 🚨 
        $newHospitalId = $hospital_admin->hospital_id ?? 'غير محدد';
        $newFullName = $hospital_admin->full_name;
        
        $details = "تم تحديث بيانات مسؤول المستشفى: {$newFullName} (الهوية: {$hospital_admin->id}). ";

        if ($oldHospitalId !== $newHospitalId) {
            $details .= "تم تغيير هوية المستشفى المرتبط من ({$oldHospitalId}) إلى ({$newHospitalId}). ";
        }
        if ($oldFullName !== $newFullName) {
             $details .= "تم تغيير الاسم من '{$oldFullName}' إلى '{$newFullName}'.";
        }
        if (isset($data['password'])) {
             $details .= " تم إعادة تعيين كلمة المرور.";
        }

        SystemLog::log(
            Auth::id(),
            'Hospital Admin Update', 
            $details
        );

        return redirect()->route('admin.hospital_admins.index')->with('success', 'تم تحديث بيانات مسؤول المستشفى بنجاح.');
    }

    /**
     * 6. معالجة طلب حذف مسؤول مستشفى (Destroy Web).
     * DELETE /admin/hospital-admins/{hospital_admin}
     */
    public function destroyWeb(User $hospital_admin)
    {
        // التحقق من الدور
        if ($hospital_admin->user_role !== User::ROLE_HOSPITAL_ADMIN) {
            return back()->with('error', 'لا يمكن حذف هذا المستخدم لأنه ليس مدير مستشفى.');
        }
        
        // 🚨 1. التوثيق في سجل النظام (قبل الحذف) - معرب بالكامل 🚨
        SystemLog::log(
            Auth::id(), 
            'Hospital Admin Deletion', 
            'تم حذف مسؤول مستشفى: ' . $hospital_admin->full_name . ' (الهوية: ' . $hospital_admin->id . ').'
        );
        
        // 2. Soft Delete
        $hospital_admin->delete();

        return redirect()->route('admin.hospital_admins.index')->with('success', 'تم حذف مسؤول المستشفى بنجاح (حذف ناعم).');
    }
}
