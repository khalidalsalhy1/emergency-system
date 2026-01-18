<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller; 

class LocationController extends Controller
{
    /**
     * 1. عرض جميع المواقع المحفوظة للمريض الحالي.
     * GET /api/patient/locations
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $locations = Location::where('user_id', $user->id)
                             ->whereNull('hospital_id') // عرض مواقع المريض فقط
                             ->orderBy('created_at', 'desc')
                             ->get();

        return response()->json([
            'status' => true,
            'message' => 'User locations retrieved successfully.',
            'locations' => $locations
        ]);
    }

    /**
     * 2. حفظ موقع جديد للمريض.
     * POST /api/patient/locations
     */
    public function store(Request $request)
    {
        $user = $request->user();

        // 1. التحقق من صحة المُدخلات
        $validator = Validator::make($request->all(), [
            'name'       => 'required|string|max:255',
            'latitude'   => 'required|numeric',
            'longitude'  => 'required|numeric',
            'address'    => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        // 2. إنشاء الموقع وربطه بالمستخدم الحالي
        $location = Location::create([
            'user_id'     => $user->id,
            'hospital_id' => null, 
            'name'        => $request->name,
            'latitude'    => $request->latitude,
            'longitude'   => $request->longitude,
            'address'     => $request->address,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Location saved successfully.',
            'location' => $location
        ], 201);
    }

    /**
     * 3. تحديث موقع محفوظ موجود.
     * PUT /api/patient/locations/{id}
     */
    public function update(Request $request, $id)
    {
        $user = $request->user();
        
        // 1. العثور على الموقع والتأكد من ملكيته (الأمان)
        $location = Location::where('id', $id)
                            ->where('user_id', $user->id)
                            ->whereNull('hospital_id')
                            ->first();

        if (!$location) {
            return response()->json(['status' => false, 'message' => 'Location not found or access denied.'], 404);
        }

        // 2. التحقق من المُدخلات
        $validator = Validator::make($request->all(), [
            'name'       => 'nullable|string|max:255',
            'latitude'   => 'nullable|numeric',
            'longitude'  => 'nullable|numeric',
            'address'    => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        // 3. التحديث
        $location->fill($request->only('name', 'latitude', 'longitude', 'address'));
        $location->save();

        return response()->json([
            'status' => true,
            'message' => 'Location updated successfully.',
            'location' => $location
        ]);
    }

    /**
     * 4. حذف موقع محفوظ.
     * DELETE /api/patient/locations/{id}
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->user();

        // 1. العثور على الموقع والتأكد من ملكيته (الأمان)
        $location = Location::where('id', $id)
                            ->where('user_id', $user->id)
                            ->whereNull('hospital_id')
                            ->first();

        if (!$location) {
            return response()->json(['status' => false, 'message' => 'Location not found or access denied.'], 404);
        }

        $location->delete();

        return response()->json([
            'status' => true,
            'message' => 'Location deleted successfully.'
        ]);
    }
}
