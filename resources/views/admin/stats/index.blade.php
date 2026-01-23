@extends('layouts.admin')

@section('title', 'لوحة الإحصائيات ومؤشرات الأداء')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">لوحة الإحصائيات والأداء (مؤشرات رئيسية)</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">الرئيسية</a></li>
                        <li class="breadcrumb-item active">الإحصائيات</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            
            {{-- ---------------------------------------------------- --}}
            {{-- 1. بطاقات الإحصائيات - الصف الأول (المؤشرات اليومية) --}}
            {{-- ---------------------------------------------------- --}}
            <h3 class="mt-4 mb-3">
                <i class="fas fa-chart-line"></i> مؤشرات الأداء اليومية ({{ now()->format('Y-m-d') }})
            </h3>
            <div class="row">
                
                {{-- 1. إجمالي عدد الطلبات (اليوم) --}}
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $totalRequestsToday }}</h3>
                            <p>إجمالي طلبات الطوارئ اليوم</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-ambulance"></i>
                        </div>
                        {{-- 🎯 ربط بطلبات اليوم (يحتاج فلتر زمني في الكنترولر للتطبيق الكامل) --}}
                        <a href="{{ route('admin.emergency_requests.index', ['date' => now()->format('Y-m-d')]) }}" class="small-box-footer">
                            عرض التفاصيل <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                
                {{-- 2. عدد الطلبات قيد المعالجة (اليوم) --}}
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $inProgressRequestsToday }}</h3>
                            <p>طلبات قيد المعالجة اليوم</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                        {{-- 🎯 ربط بطلبات قيد المعالجة لليوم --}}
                        <a href="{{ route('admin.emergency_requests.index', ['status' => 'in_progress', 'date' => now()->format('Y-m-d')]) }}" class="small-box-footer">
                            التتبع المباشر <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                
                {{-- 3. الطلبات بانتظار قبول  (اليوم) --}}
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ $pendingRequestsToday }}</h3>
                            <p>بانتظار القبول اليوم</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        {{-- 🎯 ربط بطلبات بانتظار الإسناد لليوم --}}
                        <a href="{{ route('admin.emergency_requests.index', ['status' => 'pending', 'date' => now()->format('Y-m-d')]) }}" class="small-box-footer">
                            مراجعة فورية <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                
            </div>
            
            {{-- ---------------------------------------------------- --}}
            {{-- 2. بطاقات الإحصائيات - الصف الثاني (المؤشرات الشهرية والكلية) --}}
            {{-- ---------------------------------------------------- --}}
            <h3 class="mt-4 mb-3">
                 <i class="fas fa-calendar-alt"></i> تحليل الأداء الشهري والإجمالي
            </h3>
            <div class="row">
                
                {{-- 4. إجمالي الطلبات المكتملة (الكلي) --}}
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $totalCompletedRequests }}</h3> 
                            <p>إجمالي الطلبات المكتملة (كلي)</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-check-double"></i>
                        </div>
                        {{-- 🎯 الربط بجميع الطلبات المكتملة (كلي) --}}
                        <a href="{{ route('admin.emergency_requests.index', ['status' => 'completed']) }}" class="small-box-footer">
                            تحليل الإنجاز <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                
                {{-- 5. أكثر الإصابات شيوعاً (شهرياً) --}}
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3>{{ $topInjuryMonthly->count ?? 0 }}</h3>
                            <p>أكثر إصابة شيوعاً (شهرياً): **{{ $topInjuryMonthly->name ?? 'غير متوفر' }}**</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-briefcase-medical"></i>
                        </div>
                        {{-- 🎯 الربط بالتصفية حسب نوع الإصابة (injury_name) --}}
                        <a href="{{ route('admin.emergency_requests.index', ['injury_name' => $topInjuryMonthly->name ?? '']) }}" class="small-box-footer">
                            عرض التفاصيل <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                
                {{-- 6. المستشفى الأكثر رفضاً (شهرياً) --}}
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-secondary">
                        <div class="inner">
                            <h3>{{ $mostRejectingHospital->count ?? 0 }}</h3>
                            <p>أكثر مستشفى رفضاً (شهرياً): **{{ $mostRejectingHospital->name ?? 'غير متوفر' }}**</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-hospital-alt"></i>
                        </div>
                        {{-- 🎯 الربط بالتصفية حسب المستشفى و حالة الإلغاء/الرفض --}}
                        <a href="{{ route('admin.emergency_requests.index', ['hospital_name' => $mostRejectingHospital->name ?? '', 'status' => 'canceled']) }}" class="small-box-footer">
                            مراجعة حالات الرفض <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                
            </div>
            
            
            {{-- ---------------------------------------------------- --}}
            {{-- 3. جدول تحليل أداء المستشفيات (شهرياً) --}}
            {{-- ---------------------------------------------------- --}}
            <div class="row mt-4">
                <div class="col-lg-12">
                    <div class="card card-warning">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-tachometer-alt"></i> تحليل متوسط زمن إكمال الطلبات حسب المستشفى (شهرياً)
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-striped table-valign-middle">
                                <thead>
                                    <tr>
                                        <th style="width: 5%;">#</th>
                                        <th style="width: 45%;">المستشفى</th>
                                        <th style="width: 30%;">متوسط زمن الإكمال (س:د:ث)</th>
                                        <th style="width: 20%;">متوسط الثواني</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($lowestPerformingHospital)
                                    <tr>
                                        <td colspan="4">
                                            <p class="text-danger mb-1 font-weight-bold">
                                                <i class="fas fa-exclamation-circle"></i> ملاحظة: المستشفى الأقل أداءً (أطول زمن إكمال) هو: 
                                                <span class="text-bold">{{ $lowestPerformingHospital->hospital_name }}</span> 
                                                بمتوسط زمن قدره: <span class="badge badge-danger">{{ $lowestPerformingHospital->avg_completion_time }}</span>
                                            </p>
                                        </td>
                                    </tr>
                                    @endif
                                    
                                    @forelse ($hospitalPerformanceMonthly as $index => $performance)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <a href="{{ route('admin.emergency_requests.index', ['hospital_name' => $performance->hospital_name, 'status' => 'completed']) }}">
                                                {{ $performance->hospital_name }}
                                            </a>
                                        </td>
                                        <td>
                                            <span class="text-bold">{{ $performance->avg_completion_time }}</span>
                                        </td>
                                        <td>
                                            {{ round($performance->avg_seconds) }} ثانية
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">لا توجد طلبات مكتملة خلال الشهر الجاري لتحليل الأداء.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- 🚨 هنا نهاية الـ Dashboard 🚨 --}}

        </div>
    </section>
</div>

@endsection

@section('js')
@endsection
