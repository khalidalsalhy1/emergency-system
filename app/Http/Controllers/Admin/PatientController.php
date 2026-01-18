<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Disease;
use App\Models\MedicalRecord;
use App\Models\SystemLog; // 🚨 تم إضافة موديل سجل النظام
use App\Http\Requests\PatientRequest; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; // 🚨 تم إضافة Auth لتحديد هوية المدير
use Symfony\Component\HttpFoundation\Response;

// ******************************************************
// هذا الكنترولر مخصص حصرياً للإدارة الويب (Admin)
// ******************************************************

class PatientController extends Controller
{
    /**
     * 1. عرض قائمة المرضى (Index Web View).
     * GET /admin/patients
     */
    public function indexWeb()
    {
        $patients = User::where('user_role', User::ROLE_PATIENT)
                              ->with('medicalRecord')
                              ->orderBy('full_name')
                              ->paginate(15);

        return view('admin.patients.index', compact('patients'));
    }

    /**
     * 2. عرض نموذج إنشاء مريض جديد (Create Web View).
     * GET /admin/patients/create
     */
    public function createWeb()
    {
        $diseases = Disease::orderBy('disease_name')->get(); 
        
        return view('admin.patients.create', compact('diseases'));
    }

    /**
     * 3. معالجة بيانات إنشاء مريض (Store Web) - تتضمن المعاملات.
     * POST /admin/patients
     */
    public function storeWeb(PatientRequest $request)
    {
        DB::beginTransaction();

        try {
            // 1. إنشاء سجل المستخدم (المريض)
            $userData = $request->only(['full_name', 'phone', 'email', 'national_id', 'status']);
            $userData['user_role'] = User::ROLE_PATIENT;
            $userData['password'] = Hash::make($request->password);
            $patient = User::create($userData);

            // 2. حفظ السجل الطبي
            $medicalRecordData = $request->only([
                'birth_date', 'gender', 'blood_type', 'emergency_contact', 
                'medical_history', 'allergies', 'current_medications', 'notes'
            ]);
            $patient->medicalRecord()->create($medicalRecordData); 

            // 3. حفظ علاقة الأمراض المزمنة
            $diseaseIds = $request->input('diseases_ids', []);
            $patient->diseases()->attach($diseaseIds); 
            
            // 🚨🚨 4. التوثيق في سجل النظام (إنشاء مريض) 🚨🚨
            SystemLog::log(
                Auth::id(), 
                'إنشاء مريض جديد', 
                'تم إنشاء ملف مريض جديد: ' . $patient->full_name . ' (الهوية: ' . $patient->id . ') بواسطة المدير.'
            );
            // ----------------------------------------------------
            
            DB::commit();

            return redirect()->route('admin.patients.index')->with('success', 'تم تسجيل المريض وملفه الطبي بنجاح.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'حدث خطأ أثناء حفظ البيانات: ' . $e->getMessage());
        }
    }

    /**
     * 4. عرض نموذج تعديل بيانات مريض (Edit Web View).
     * GET /admin/patients/{patient}/edit
     */
    public function editWeb(User $patient)
    {
        if ($patient->user_role !== User::ROLE_PATIENT) {
            return redirect()->route('admin.patients.index')->with('error', 'السجل المحدد ليس لمريض.');
        }

        $diseases = Disease::orderBy('disease_name')->get();
        // جلب معرفات الأمراض المرتبطة بهذا المريض
        $patientDiseases = $patient->diseases()->pluck('disease_id')->toArray(); 
        $patient->load('medicalRecord'); 

        return view('admin.patients.edit', compact('patient', 'diseases', 'patientDiseases'));
    }

    /**
     * 5. معالجة طلب تحديث بيانات مريض (Update Web) - تتضمن المعاملات.
     * PUT/PATCH /admin/patients/{patient}
     */
    public function updateWeb(PatientRequest $request, User $patient)
    {
         DB::beginTransaction();

        try {
            // حفظ البيانات الأصلية للتوثيق قبل التحديث
            $originalPatientData = $patient->getOriginal();
            $originalMedicalData = $patient->medicalRecord ? $patient->medicalRecord->getOriginal() : [];
            $originalDiseases = $patient->diseases()->pluck('disease_id')->toArray();

            // 1. تحديث بيانات المستخدم الأساسية
            $userData = $request->only(['full_name', 'phone', 'email', 'national_id', 'status']);
            
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            } else {
                unset($userData['password']);
            }
            $patient->update($userData);

            // 2. تحديث السجل الطبي
            $medicalRecordFields = [
                'birth_date', 'gender', 'blood_type', 'emergency_contact', 
                'medical_history', 'allergies', 'current_medications', 'notes'
            ];
            
            $medicalRecordData = $request->only($medicalRecordFields);

            $dataToUpdate = array_filter($medicalRecordData, function($value) {
                return !is_null($value) && $value !== '';
            });

            $medicalRecord = null;
            if (!empty($dataToUpdate)) {
                $medicalRecord = $patient->medicalRecord()->updateOrCreate(
                    ['user_id' => $patient->id],
                    $dataToUpdate           
                );
            }
            
            // 3. تحديث علاقة الأمراض المزمنة
            $diseaseIds = $request->input('diseases_ids', []); 
            $patient->diseases()->sync($diseaseIds); 
            
            // 🚨🚨 4. التوثيق في سجل النظام (تحديث بيانات مريض) 🚨🚨
            
            // تتبع التغييرات في بيانات المستخدم
            $patientChanges = array_diff_assoc($patient->getChanges(), $originalPatientData);
            
            // تتبع التغييرات في بيانات السجل الطبي
            $medicalChanges = ($medicalRecord && $medicalRecord->wasRecentlyCreated) ? $medicalRecord->toArray() : array_diff_assoc($medicalRecord ? $medicalRecord->getChanges() : [], $originalMedicalData);
            
            // تتبع التغييرات في الأمراض
            $diseaseChanges = (count($originalDiseases) !== count($diseaseIds)) || (array_diff($originalDiseases, $diseaseIds) || array_diff($diseaseIds, $originalDiseases));

            $logDetails = "تم تحديث بيانات المريض: {$patient->full_name} (الهوية: {$patient->id}). ";
            
            if (!empty($patientChanges) || !empty($medicalChanges) || $diseaseChanges) {
                $logDetails .= "التغييرات المسجلة: ";
                
                if (!empty($patientChanges)) {
                    $logDetails .= "في بيانات المستخدم (" . json_encode($patientChanges, JSON_UNESCAPED_UNICODE) . "). ";
                }
                if (!empty($medicalChanges)) {
                    $logDetails .= "في السجل الطبي (" . json_encode($medicalChanges, JSON_UNESCAPED_UNICODE) . "). ";
                }
                if ($diseaseChanges) {
                    $logDetails .= "تم تحديث الأمراض المزمنة.";
                }
            } else {
                $logDetails .= "لم يتم تسجيل أي تغييرات فعلية.";
            }

            SystemLog::log(
                Auth::id(), 
                'تحديث بيانات مريض', 
                $logDetails
            );
            // ----------------------------------------------------
            
            DB::commit();

            return redirect()->route('admin.patients.index')->with('success', 'تم تحديث بيانات وسجل المريض بنجاح.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'حدث خطأ أثناء تحديث البيانات: ' . $e->getMessage());
        }
    }
    
    /**
     * 6. معالجة طلب حذف مريض (Destroy Web).
     * DELETE /admin/patients/{patient}
     */
    public function destroyWeb(User $patient)
    {
        if ($patient->user_role !== User::ROLE_PATIENT) {
            return back()->with('error', 'لا يمكن حذف هذا المستخدم لأنه ليس مريضاً.');
        }
        
        // 🚨🚨 1. التوثيق في سجل النظام (قبل الحذف) 🚨🚨
        SystemLog::log(
            Auth::id(), 
            'حذف مريض', 
            'تم حذف المريض: ' . $patient->full_name . ' (الهوية: ' . $patient->id . ') بواسطة المدير (حذف ناعم).'
        );
        // ----------------------------------------------------
        
        $patient->delete();

        return redirect()->route('admin.patients.index')->with('success', 'تم حذف المريض بنجاح (حذف ناعم).');
    }
}
