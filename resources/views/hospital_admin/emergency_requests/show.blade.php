{{-- resources/views/hospital_admin/emergency_requests/show.blade.php --}}

@extends('layouts.hospital') {{-- 🚨 التعديل 1: استخدام Layout المستشفى --}}

@section('title', 'تفاصيل طلب الطوارئ #' . $emergencyRequest->id)

@section('content_header')
    <h1><i class="fas fa-search-location"></i> تفاصيل ومتابعة طلب الطوارئ #{{ $emergencyRequest->id }}</h1>
@stop

@section('content')
@php
$statusMapping = [
    'pending' => 'قيد الانتظار',
    'in_progress' => 'قيد التنفيذ',
    'completed' => 'مكتمل',
    'canceled' => 'ملغى', // 🚨 مهم جدًا لمربع سبب الإلغاء
];
@endphp
    <div class="row" dir="rtl">
        {{-- عرض رسائل النجاح/الخطأ --}}
        @if (session('success'))
            <div class="col-12 alert alert-success text-right">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="col-12 alert alert-danger text-right">
                يرجى تصحيح الأخطاء التالية قبل المتابعة:
                <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif
        
        {{-- مصفوفة الترجمة المستخدمة في الـ View --}}
        @php

            $requestTypeMapping = [
                'DISPATCH' => 'طلب إرسال إسعاف',
                'NOTIFY' => 'إبلاغ/إشعار بحالة',
            ];
            $displayRequestType = $requestTypeMapping[$emergencyRequest->request_type] ?? 'غير معروف';
        @endphp

        {{-- 1. بطاقة بيانات الطلب الأساسية والمريض --}}
        <div class="col-md-7">
            <div class="card card-info">
                <div class="card-header text-right">
                    <h3 class="card-title float-right">بيانات الطلب والمريض</h3>
                </div>
                <div class="card-body">
                    <div class="row text-right">
                        {{-- بيانات المريض --}}
                        <div class="col-md-6 border-left" style="text-align: right;">
                            <h4><i class="fas fa-user-injured"></i> المريض</h4>
                            <p><strong>الاسم:</strong> {{ $emergencyRequest->patient->full_name ?? 'مستخدم محذوف' }}</p>
                            <p><strong>الهاتف:</strong> {{ $emergencyRequest->patient->phone ?? 'غير متوفر' }}</p>
                            
                            {{-- عرض الأمراض المزمنة --}}
                            @if ($emergencyRequest->patient && $emergencyRequest->patient->diseases->isNotEmpty())
                                <p><strong>أمراض مزمنة:</strong> 
                                    @foreach($emergencyRequest->patient->diseases as $disease)
                                        <span class="badge badge-danger">{{ $disease->disease_name }}</span>
                                    @endforeach
                                </p>
                            @endif
                            <hr>
                            
                            <h4><i class="fas fa-file-medical-alt"></i> السجل الطبي</h4>
                            @if ($emergencyRequest->patient && $emergencyRequest->patient->medicalRecord)
                                @php $record = $emergencyRequest->patient->medicalRecord; @endphp
                                <p><strong>فصيلة الدم:</strong> {{ $record->blood_type ?? 'غير محدد' }}</p>
                                <p><strong>حساسيات:</strong> {{ $record->allergies ?? 'لا توجد' }}</p>
                                <p><strong>أدوية حالية:</strong> {{ $record->current_medications ?? 'لا توجد' }}</p>
                            @else
                                <p class="text-danger">السجل الطبي غير متوفر لهذا المريض.</p>
                            @endif
                        </div>

                        {{-- تفاصيل الطلب --}}
                        <div class="col-md-6" style="text-align: right;">
                            <h4><i class="fas fa-clipboard-list"></i> تفاصيل الطوارئ</h4>
                            
                            <p><strong>نوع الطلب:</strong> <span class="badge badge-primary">{{ $displayRequestType }}</span></p>

                            <p><strong>تاريخ الإنشاء:</strong> {{ $emergencyRequest->created_at->format('Y-m-d H:i') }}</p>
                            
                            <p><strong>نوع الإصابة:</strong> {{ $emergencyRequest->injuryType->name ?? 'غير محدد' }}</p>
                            
                            <p><strong>وصف المريض:</strong> {{ $emergencyRequest->description ?? 'لا يوجد وصف' }}</p>
                            
                            <p>
                                <strong>الحالة الحالية:</strong> 
                                @include('admin.emergency_requests.partials.status_badge', ['status' => $emergencyRequest->status])
                            </p>
                            
                            <hr>

                            {{-- عرض سبب الإلغاء/الرفض النهائي --}}
                            @if($emergencyRequest->rejection_reason) 
                                <p class="text-danger font-weight-bold"><strong>سبب الإلغاء/الرفض:</strong> {{ $emergencyRequest->rejection_reason }}</p>
                                <hr>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- 2. بطاقة تحديث حالة الطلب --}}
        <div class="col-md-5">
            <div class="card card-warning shadow">
                <div class="card-header text-right">
                    <h3 class="card-title float-right text-dark">تحديث حالة الطلب</h3>
                </div>
                <form action="{{ route('hospital.requests.update_status', $emergencyRequest->id) }}" method="POST" id="statusUpdateForm">
                    @csrf
                    @method('PUT')
                    <div class="card-body text-right" style="text-align: right;">
                        
                        @if($emergencyRequest->status === 'completed' || $emergencyRequest->status === 'canceled')
                            <div class="alert alert-info">هذا الطلب في حالة نهائية ({{ $statusMapping[$emergencyRequest->status] ?? $emergencyRequest->status }}).</div>
                        @elseif(empty($allowedTransitions))
                             <div class="alert alert-warning">لا توجد حالات متاحة للتحديث حالياً.</div>
                        @else
                            {{-- تغيير الحالة --}}
                            <div class="form-group">
                                <label for="status">الحالة التالية</label>
                                <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                                    <option value="">-- اختر الحالة الجديدة --</option>
                                    @foreach($allowedTransitions as $status)
                                        <option value="{{ $status }}">
                                            {{ $statusMapping[$status] ?? ucfirst(str_replace('_', ' ', $status)) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            
                            {{-- سبب الإلغاء - يظهر دائماً لحل مشكلة الجافاسكريبت --}}
                            <div class="form-group" id="reason-field">
                                <label for="rejection_reason" class="text-danger font-weight-bold">سبب إلغاء الطلب (إلزامي في حال اختيار "ملغى") *</label>
                                <textarea name="rejection_reason" id="rejection_reason" class="form-control border-danger @error('rejection_reason') is-invalid @enderror" rows="2" placeholder="اكتب سبب الرفض هنا..." style="text-align: right;">{{ old('rejection_reason') }}</textarea>
                                @error('rejection_reason') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        @endif
                        
                    </div>
                    <div class="card-footer">
                        @if(!($emergencyRequest->status === 'completed' || $emergencyRequest->status === 'canceled') && !empty($allowedTransitions))
                            <button type="submit" class="btn btn-warning float-right font-weight-bold text-dark shadow-sm">تحديث الحالة</button>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        {{-- 3. سجل تاريخ حالة الطلب --}}
        <div class="col-md-12">
            <div class="card card-secondary">
                <div class="card-header text-right">
                    <h3 class="card-title float-right"><i class="fas fa-history"></i> سجل تغييرات حالة الطلب</h3>
                </div>
                <div class="card-body p-0 text-right">
                    <ul class="products-list product-list-in-card pl-2 pr-2">
                        @forelse ($emergencyRequest->statusHistory as $history)
                            <li class="item">
                                <div class="product-info" style="margin-right: 20px; text-align: right;">
                                    <span class="product-title">
                                        {{ $statusMapping[$history->status] ?? ucfirst(str_replace('_', ' ', $history->status)) }}
                                        <span class="badge badge-secondary float-left">{{ $history->created_at->format('Y-m-d H:i:s') }}</span>
                                    </span>
                                    <span class="product-description">
                                        <strong>بواسطة:</strong> {{ $history->changedBy->full_name ?? 'النظام/المريض' }}
                                        @if($history->reason)
                                            | <strong>الملاحظات:</strong> {{ $history->reason }}
                                        @endif
                                    </span>
                                </div>
                            </li>
                        @empty
                             <p class="p-3 text-center">لا يوجد سجل تاريخ لهذا الطلب بعد.</p>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        {{-- 4. الموقع الجغرافي بشكل ممتد في الأسفل --}}
        <div class="col-md-12 mt-3">
            <div class="card card-primary card-outline shadow">
                <div class="card-header text-right">
                    <h3 class="card-title float-right"><i class="fas fa-map-marked-alt"></i> موقع الحالة وتفاصيل العنوان</h3>
                </div>
                <div class="card-body text-right">
                    <div class="row">
                        <div class="col-md-4 border-left text-right" style="text-align: right;">
                             <h5><i class="fas fa-info-circle"></i> معلومات العنوان</h5>
                             @if ($emergencyRequest->location)
                                <p class="mb-1"><strong>الإحداثيات:</strong> {{ $emergencyRequest->location->latitude }}, {{ $emergencyRequest->location->longitude }}</p>
                                <p><strong>العنوان التوضيحي:</strong> {{ $emergencyRequest->location->address ?? 'غير متوفر' }}</p>
                                <hr>
                                <div class="form-group text-right">
                                    <label class="text-primary"><i class="fas fa-copy"></i> رابط الموقع  :</label>
                                    <input type="text" class="form-control font-weight-bold" readonly 
                                           value="https://www.google.com/maps?q={{ $emergencyRequest->location->latitude }},{{ $emergencyRequest->location->longitude }}" 
                                           style="background-color: #f8f9fa; border: 1px solid #007bff; color: #007bff; text-align: left;" dir="ltr">
                                </div>
                                <a href="https://www.google.com/maps?q={{ $emergencyRequest->location->latitude }},{{ $emergencyRequest->location->longitude }}" 
                                   target="_blank" class="btn btn-success btn-block mt-3 shadow-sm font-weight-bold">
                                   <i class="fas fa-external-link-alt ml-1"></i> فتح في تطبيق الخرائط
                                </a>
                            @else
                                <p class="text-danger">بيانات الموقع غير متوفرة لهذا الطلب.</p>
                            @endif
                        </div>
                        <div class="col-md-8">
                            @if ($emergencyRequest->location)
                                <div id="map-container" style="height: 350px; border-radius: 8px; overflow: hidden; border: 1px solid #ddd;">
                                    <iframe width="100%" height="100%" frameborder="0" style="border:0" 
                                        src="https://maps.google.com/maps?q={{ $emergencyRequest->location->latitude }},{{ $emergencyRequest->location->longitude }}&hl=ar&z=15&output=embed" 
                                        allowfullscreen>
                                    </iframe>
                                </div>
                            @else
                                <div class="d-flex align-items-center justify-content-center" style="height: 350px; background-color: #f8f9fa;">
                                    <p class="text-muted">الخريطة غير متاحة.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
<script>
    // الحقل يظهر بشكل دائم لتجنب مشاكل تعارض الجافاسكريبت مع القالب
</script>
@stop
