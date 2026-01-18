@extends('layouts.admin')

@section('title', 'عرض الموقع')

@section('content_header')
    <h1><i class="fas fa-map-marker-alt"></i> تفاصيل الموقع الجغرافي</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline shadow">
                <div class="card-body">
                    <div class="row">
                        {{-- القسم الأيمن: الأسماء وروابط النسخ --}}
                        <div class="col-md-5 border-right">
                            <div class="py-3">
                                {{-- عرض اسم المستشفى إذا وجد --}}
                                @if($location->hospital)
                                    <div class="mb-4">
                                        <label class="text-muted small"><i class="fas fa-hospital mr-1"></i> اسم المستشفى:</label>
                                        <h4 class="text-primary font-weight-bold">{{ $location->hospital->hospital_name }}</h4>
                                    </div>
                                @endif

                                {{-- عرض اسم المريض إذا وجد --}}
                                @if($location->user)
                                    <div class="mb-4">
                                        <label class="text-muted small"><i class="fas fa-user mr-1"></i> اسم المريض:</label>
                                        <h4 class="text-success font-weight-bold">{{ $location->user->full_name ?? $location->user->name }}</h4>
                                    </div>
                                @endif

                                {{-- في حال عدم وجود ارتباط بمريض أو مستشفى --}}
                                @if(!$location->hospital && !$location->user)
                                    <div class="alert alert-light border mb-4">
                                        <i class="fas fa-exclamation-circle text-warning"></i> هذا الموقع غير مرتبط بمستشفى أو مريض حالياً.
                                    </div>
                                @endif

                                <hr>

                                {{-- 🔗 قسم رابط النسخ اليدوي وزر الخرائط --}}
                                <div class="mt-3">
                                    <div class="form-group bg-light p-2 border rounded">
                                        <label class="text-primary small font-weight-bold"><i class="fas fa-copy"></i> رابط الموقع للنسخ اليدوي:</label>
                                        <input type="text" class="form-control form-control-sm" readonly 
                                               value="https://www.google.com/maps?q={{ $location->latitude }},{{ $location->longitude }}" 
                                               style="background-color: #fff; border: 1px solid #007bff; color: #007bff; font-weight: bold;">
                                    </div>
                                    
                                    <a href="https://www.google.com/maps?q={{ $location->latitude }},{{ $location->longitude }}" 
                                       target="_blank" class="btn btn-success btn-sm btn-block shadow-sm mb-3">
                                       <i class="fas fa-external-link-alt"></i> فتح في تطبيق خرائط جوجل
                                    </a>
                                </div>

                                <div class="mt-4">
                                    <a href="{{ route('admin.locations.index') }}" class="btn btn-secondary shadow-sm">
                                        <i class="fas fa-arrow-right"></i> العودة للقائمة
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- القسم الأيسر: الخريطة (بنفس الأسلوب الذي يعمل لديك) --}}
                        <div class="col-md-7">
                            <label class="small text-muted"><i class="fas fa-map mr-1"></i> المعاينة الجغرافية:</label>
                            <div style="height: 450px; border-radius: 8px; overflow: hidden; border: 1px solid #ddd; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                                <iframe 
                                    width="100%" 
                                    height="100%" 
                                    frameborder="0" 
                                    style="border:0" 
                                    src="https://maps.google.com/maps?q={{ $location->latitude }},{{ $location->longitude }}&hl=ar&z=15&output=embed" 
                                    allowfullscreen>
                                </iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
