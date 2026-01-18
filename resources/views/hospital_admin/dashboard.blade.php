@extends('layouts.hospital') 

@section('title', 'لوحة إحصائيات المستشفى ومؤشرات الأداء')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    {{-- 🌟 2. عرض اسم المستشفى --}}
                    <h1 class="m-0 text-dark">لوحة إحصائيات مستشفى {{ $dashboardStats['hospital_name'] ?? 'غير معروف' }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('hospital.dashboard') }}">الرئيسية</a></li>
                        <li class="breadcrumb-item active">إحصائيات المستشفى</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            
            {{-- ---------------------------------------------------- --}}
            {{-- 1. بطاقات الإحصائيات - الصف الأول (المؤشرات اليومية والكلية) --}}
            {{-- ---------------------------------------------------- --}}
            <h3 class="mt-4 mb-3">
                <i class="fas fa-chart-line"></i> مؤشرات الأداء الحالية والكلية للمستشفى
            </h3>
            <div class="row">
                
                {{-- 1. إجمالي الطلبات التي تم توجيهها للمستشفى (كلي) --}}
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            {{-- 🟢 متغير صحيح --}}
                            <h3>{{ $dashboardStats['total_assigned_requests'] ?? 0 }}</h3> 
                            <p>إجمالي الطلبات المُسندة (كلي)</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-hospital-user"></i>
                        </div>
                        <a href="{{ route('hospital.requests.index') }}" class="small-box-footer">
                            عرض جميع الطلبات <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                
                {{-- 2. الطلبات التي ما زالت "قيد المعالجة" --}}
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                             {{-- 🟢 تصحيح اسم المتغير الذي كان (pending_requests) --}}
                             <h3>{{ $dashboardStats['in_progress_requests'] ?? 0 }}</h3>
                            <p>طلبات قيد المعالجة حالياً</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                        {{-- 🟢 تصحيح الفلتر لاستخدام filter=live_tracking --}}
                        <a href="{{ route('hospital.requests.index', ['filter' => 'live_tracking']) }}" class="small-box-footer">
                            التتبع المباشر <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                
                {{-- 3. الطلبات المنجزة (الكلية) --}}
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $dashboardStats['completed_requests'] ?? 0 }}</h3>
                            <p>إجمالي الطلبات المكتملة</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-check-double"></i>
                        </div>
                        {{-- 🟢 تصحيح الفلتر لاستخدام filter=completed --}}
                        <a href="{{ route('hospital.requests.index', ['filter' => 'completed']) }}" class="small-box-footer">
                            تحليل الإنجاز <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                
                {{-- 4. الطلبات الواردة اليوم فقط --}}
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ $dashboardStats['today_requests'] ?? 0 }}</h3>
                            <p>طلبات الطوارئ الواردة اليوم</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-ambulance"></i>
                        </div>
                        {{-- 🟢 تصحيح الفلتر لاستخدام filter=today --}}
                        <a href="{{ route('hospital.requests.index', ['filter' => 'today']) }}" class="small-box-footer">
                            عرض تفاصيل اليوم <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                
            </div>
            
            {{-- ⚠️ تم حذف جزء 'إحصائيات الكادر والموارد' هنا بناءً على طلبك. ⚠️ --}}
            
            {{-- ⚠️ تم حذف جزء 'هذه اللوحة خاصة بأداء المستشفى فقط...' هنا بناءً على طلبك. ⚠️ --}}
            
        </div>
    </section>
</div>
@endsection
