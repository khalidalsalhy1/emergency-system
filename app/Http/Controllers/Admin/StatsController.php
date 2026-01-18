<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; // 🚨 استيراد Carbon
use App\Models\EmergencyRequest;
use App\Models\Hospital;

class StatsController extends Controller
{
    /**
     * عرض لوحة الإحصائيات (Dashboard) وسحب جميع المؤشرات.
     * تم تحديث الكود ليعتمد على التصفية الزمنية (يومي، شهري، كلي) والمؤشرات الجديدة.
     */
    public function index()
    {
        // ----------------------------------------------------
        // 1. تحديد النطاق الزمني
        // ----------------------------------------------------
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // ----------------------------------------------------
        // 2. حساب مؤشرات الأداء اليومية (Today's Metrics)
        // ----------------------------------------------------
        
        $requestsQueryToday = EmergencyRequest::whereDate('created_at', $today);
        
        $totalRequestsToday = $requestsQueryToday->count();
        
        $pendingRequestsToday = (clone $requestsQueryToday)->where('status', 'pending')->count();
        
        $inProgressRequestsToday = (clone $requestsQueryToday)->where('status', 'in_progress')->count();

        // ----------------------------------------------------
        // 3. حساب مؤشرات الأداء الشهرية (Monthly Metrics)
        // ----------------------------------------------------
        
        $requestsQueryMonth = EmergencyRequest::whereBetween('emergency_requests.created_at', [$startOfMonth, $endOfMonth]);
        
        // أ. أكثر الإصابات شيوعاً (شهرياً)
        $topInjuryMonthly = (clone $requestsQueryMonth)
            ->join('injury_types', 'emergency_requests.injury_type_id', '=', 'injury_types.id')
            ->select('injury_types.injury_name as name', DB::raw('count(*) as count'))
            ->groupBy('injury_types.injury_name')
            ->orderByDesc('count')
            ->first(); 
            
        // ب. أكثر المستشفيات رفضاً للطلبات (شهرياً)
        // نفترض أن الرفض يتم تسجيله كـ 'canceled' و Hospital_id موجود.
        $mostRejectingHospital = (clone $requestsQueryMonth)
            ->where('status', 'canceled')
            ->whereNotNull('hospital_id')
            ->join('hospitals', 'emergency_requests.hospital_id', '=', 'hospitals.id')
            ->select('hospitals.hospital_name as name', DB::raw('count(*) as count'))
            ->groupBy('hospitals.hospital_name')
            ->orderByDesc('count')
            ->first();
            
        // ج. المستشفى الأقل أداءً (أطول متوسط زمن إكمال شهرياً)
        $lowestPerformingHospital = $this->calculateAvgCompletionTimeByHospital($startOfMonth, $endOfMonth, true);
        
        // د. تحليل الأداء المقارن للمستشفيات (جدول)
        $hospitalPerformanceMonthly = $this->calculateAvgCompletionTimeByHospital($startOfMonth, $endOfMonth, false);


        // ----------------------------------------------------
        // 4. حساب مؤشرات الأداء الكلية (All-Time Metrics)
        // ----------------------------------------------------
        
        // إجمالي الطلبات المكتملة منذ بداية النظام
        $totalCompletedRequests = EmergencyRequest::where('status', 'completed')->count();


        // ----------------------------------------------------
        // 5. تجميع وإرسال البيانات إلى الـ View
        // ----------------------------------------------------
        
        $statsData = [
            // اليومية
            'totalRequestsToday'        => $totalRequestsToday,
            'pendingRequestsToday'      => $pendingRequestsToday,
            'inProgressRequestsToday'   => $inProgressRequestsToday,
            // الكلية (بديل لنسبة القيد المعالجة)
            'totalCompletedRequests'    => $totalCompletedRequests, 
            // الشهرية
            'topInjuryMonthly'          => $topInjuryMonthly,
            'mostRejectingHospital'     => $mostRejectingHospital,
            'lowestPerformingHospital'  => $lowestPerformingHospital,
            'hospitalPerformanceMonthly'=> $hospitalPerformanceMonthly,
            // متغيرات الكود الأصلي (إن لم يتم استخدامها في الـ view سيتم تجاهلها)
            'totalRequests'             => EmergencyRequest::count(), // للإبقاء على متغيرات الكود القديم إن لزم الأمر
        ];
        
        return view('admin.stats.index', $statsData);
    }
    
    /**
     * حساب متوسط زمن إغلاق الطلب لكل مستشفى مع إمكانية التصفية زمنياً.
     * @param string $startDate
     * @param string $endDate
     * @param bool $isLowestPerformanceCheck (هل نحدد الأقل أداءً فقط)
     * @return mixed
     */
    private function calculateAvgCompletionTimeByHospital($startDate = null, $endDate = null, $isLowestPerformanceCheck = false)
    {
        $hospitalStatsQuery = DB::table('emergency_requests')
            ->select(
                'hospitals.id as hospital_id',
                'hospitals.hospital_name as hospital_name',
                // حساب متوسط الثواني المستغرقة للإكمال
                DB::raw('AVG(TIMESTAMPDIFF(SECOND, emergency_requests.created_at, emergency_requests.completed_at)) as avg_seconds')
            )
            ->join('hospitals', 'emergency_requests.hospital_id', '=', 'hospitals.id')
            ->where('emergency_requests.status', 'completed')
            ->whereNotNull('emergency_requests.hospital_id')
            ->groupBy('hospitals.id', 'hospitals.hospital_name');
            
        // 🚨 تطبيق التصفية الزمنية (شهرياً)
        if ($startDate && $endDate) {
            $hospitalStatsQuery->whereBetween('emergency_requests.created_at', [$startDate, $endDate]);
        }

        $hospitalStatsQuery->orderByDesc('avg_seconds');
        
        // إذا كنا نبحث عن الأقل أداءً فقط (أطول زمن)
        if ($isLowestPerformanceCheck) {
            $result = $hospitalStatsQuery->first();
            // نمرر النتيجة إلى دالة التنسيق
            return $result ? $this->formatAvgSeconds($result) : null;
        }

        $results = $hospitalStatsQuery->get();

        // تحويل الثواني إلى صيغة ساعة:دقيقة:ثانية للجدول
        return $results->map(function ($item) {
            return $this->formatAvgSeconds($item);
        });
    }
    
    /**
     * دالة مساعدة لتنسيق متوسط الثواني إلى ساعات:دقائق:ثواني.
     * @param object $item
     * @return object
     */
    private function formatAvgSeconds($item)
    {
        $avgSeconds = round($item->avg_seconds ?? 0);
        $hours = floor($avgSeconds / 3600);
        $minutes = floor(($avgSeconds % 3600) / 60);
        $seconds = $avgSeconds % 60;
        
        // إضافة حقل جديد بالصيغة المنسقة (مثلاً: 01:35:12)
        $item->avg_completion_time = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds); 
        return $item;
    }
}
