<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Hospital; // نحتاج لإدارة علاقة المستشفى
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LocationController extends Controller
{
    /**
     * 1. عرض قائمة بجميع المواقع (Index).
     */
    public function indexWeb()
    {
        $locations = Location::with(['user:id,full_name', 'hospital:id,hospital_name'])
                            ->latest()
                            ->paginate(20);

        return view('admin.locations.index', compact('locations'));
    }

    /**
     * 2. عرض نموذج إنشاء موقع جديد (Create).
     */
    public function createWeb()
    {
        // جلب قائمة المستشفيات لاستخدامها في نموذج الربط
        $hospitals = Hospital::pluck('hospital_name', 'id');
        
        return view('admin.locations.create', compact('hospitals'));
    }

    /**
     * 3. حفظ الموقع الجديد في قاعدة البيانات (Store).
     */
    public function storeWeb(Request $request)
    {
        $validatedData = $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'address' => 'nullable|string|max:255',
            'user_id' => 'nullable|exists:users,id', // يُفترض أنك لا تسجله يدويًا ولكن للحماية
            'hospital_id' => 'nullable|exists:hospitals,id',
        ]);

        Location::create($validatedData);

        return redirect()->route('admin.locations.index')
                         ->with('success', 'تم إضافة الموقع الجغرافي بنجاح.');
    }

    /**
     * 4. عرض تفاصيل الموقع (Show) - اختياري، يمكن الدمج مع Edit.
     */
    public function showWeb(Location $location)
    {
        $location->load(['user', 'hospital', 'emergencyRequests']);
        return view('admin.locations.show', compact('location'));
    }

    /**
     * 5. عرض نموذج تعديل الموقع (Edit).
     */
    public function editWeb(Location $location)
    {
        $hospitals = Hospital::pluck('hospital_name', 'id');
        return view('admin.locations.edit', compact('location', 'hospitals'));
    }

    /**
     * 6. تحديث بيانات الموقع في قاعدة البيانات (Update).
     */
    public function updateWeb(Request $request, Location $location)
    {
        $validatedData = $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'address' => 'nullable|string|max:255',
            'user_id' => 'nullable|exists:users,id',
            'hospital_id' => 'nullable|exists:hospitals,id',
        ]);
        
        $location->update($validatedData);

        return redirect()->route('admin.locations.index')
                         ->with('success', 'تم تحديث الموقع الجغرافي بنجاح.');
    }

    /**
     * 7. حذف الموقع (Destroy).
     */
    public function destroyWeb(Location $location)
    {
        // يجب التفكير في تأثير حذف الموقع على طلبات الطوارئ المرتبطة به.
        // افتراضياً، سيتم حذف الموقع فقط.
        $location->delete();

        return redirect()->route('admin.locations.index')
                         ->with('success', 'تم حذف الموقع الجغرافي بنجاح.');
    }
}
