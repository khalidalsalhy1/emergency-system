{{-- resources/views/admin/emergency_requests/show.blade.php --}}

@extends('layouts.admin') 

@section('title', 'تفاصيل طلب الطوارئ #' . $emergencyRequest->id)

@section('content_header')
    <h1><i class="fas fa-search-location"></i> تفاصيل ومراجعة طلب الطوارئ #{{ $emergencyRequest->id }}</h1>
@stop

@section('content')
    <div class="row">
        {{-- عرض رسائل النجاح/الخطأ --}}
        @if (session('success'))
            <div class="col-12 alert alert-success">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="col-12 alert alert-danger">
                يرجى تصحيح الأخطاء التالية قبل المتابعة:
                <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif
        
        {{-- مصفوفة الترجمة المستخدمة في الـ View --}}
        @php
            $requestTypeMapping = [
                'DISPATCH' => 'طلب إرسال إسعاف',
                'NOTIFY' => 'إبلاغ/بحالة طارئة ',
            ];
            $displayRequestType = $requestTypeMapping[$emergencyRequest->request_type] ?? 'غير معروف';
        @endphp

        {{-- 1. بطاقة بيانات الطلب الأساسية والمريض --}}
        <div class="col-md-7">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">بيانات الطلب والمريض</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        {{-- بيانات المريض --}}
                        <div class="col-md-6">
                            <h4><i class="fas fa-user-injured"></i> المريض</h4>
                            <p><strong>الاسم:</strong> {{ $emergencyRequest->user->full_name ?? 'مستخدم محذوف' }}</p>
                            <p><strong>الهاتف:</strong> {{ $emergencyRequest->user->phone ?? 'غير متوفر' }}</p>
                            <hr>
                            
                            <h4><i class="fas fa-file-medical-alt"></i> السجل الطبي</h4>
                            @if ($emergencyRequest->user && $emergencyRequest->user->medicalRecord)
                                @php $record = $emergencyRequest->user->medicalRecord; @endphp
                                <p><strong>فصيلة الدم:</strong> {{ $record->blood_type ?? 'غير محدد' }}</p>
                                <p><strong>حساسيات:</strong> {{ $record->allergies ?? 'لا توجد' }}</p>
                                <p><strong>أدوية حالية:</strong> {{ $record->current_medications ?? 'لا توجد' }}</p>
                            @else
                                <p class="text-danger">السجل الطبي غير متوفر لهذا المريض.</p>
                            @endif
                        </div>

                        {{-- تفاصيل الطلب --}}
                        <div class="col-md-6">
                            <h4><i class="fas fa-clipboard-list"></i> تفاصيل الطوارئ</h4>
                            
                            <p><strong>نوع الطلب:</strong> <span class="badge badge-primary">{{ $displayRequestType }}</span></p>

                            <p><strong>تاريخ الإنشاء:</strong> {{ $emergencyRequest->created_at->format('Y-m-d H:i') }}</p>
                            
                            <p><strong>نوع الإصابة:</strong> {{ $emergencyRequest->injuryType->injury_name ?? 'غير محدد' }}</p>
                            
                            <p><strong>وصف المريض:</strong> {{ $emergencyRequest->description ?? 'لا يوجد وصف' }}</p>
                            
                            {{-- تم تعديل عرض الحالة هنا ليصبح بالعربي --}}
<p><strong>الحالة الحالية:</strong> 
    @include('admin.emergency_requests.partials.status_badge', ['status' => $emergencyRequest->status])
</p>
                            <p><strong>المستشفى المسند:</strong> {{ $emergencyRequest->hospital->hospital_name ?? 'لم يتم الإسناد' }}</p>
                            
                            <hr>

                            @if($emergencyRequest->rejection_reason) 
                                <p class="text-danger"><strong>سبب الرفض النهائي:</strong> {{ $emergencyRequest->rejection_reason }}</p>
                                <hr>
                            @endif
                            
                            @php 
                                $lastHistory = $emergencyRequest->statusHistory->first(); 
                            @endphp
                            @if($lastHistory && $lastHistory->reason && $lastHistory->reason !== 'Admin manual update')
                                <div class="alert alert-info p-2 mt-2">
                                    <strong>آخر ملاحظة إدارية:</strong> {{ $lastHistory->reason }}
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- 2. بطاقة التدخل الإداري وتغيير الحالة --}}
        <div class="col-md-5">
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">التدخل الإداري وتحديث الحالة</h3>
                </div>
                <form action="{{ route('admin.emergency_requests.update', $emergencyRequest->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="status">تغيير الحالة يدوياً</label>
                            <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                                @foreach($allowedStatuses as $status)
                                    @php $translatedStatus = $statusMapping[$status] ?? ucfirst(str_replace('_', ' ', $status)); @endphp
                                    <option value="{{ $status }}" {{ old('status', $emergencyRequest->status) == $status ? 'selected' : '' }}>
                                        {{ $translatedStatus }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label for="hospital_id">إعادة إسناد إلى مستشفى آخر</label>
                            <select name="hospital_id" id="hospital_id" class="form-control @error('hospital_id') is-invalid @enderror">
                                <option value="">لا تغيير</option>
                                @foreach($hospitals as $hospital)
                                    <option value="{{ $hospital->id }}" {{ old('hospital_id', $emergencyRequest->hospital_id) == $hospital->id ? 'selected' : '' }}>
                                        {{ $hospital->hospital_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('hospital_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="reason">سبب التعديل الإداري (يظهر في سجل التاريخ)</label>
                            <textarea name="reason" id="reason" class="form-control @error('reason') is-invalid @enderror" rows="2">{{ old('reason') }}</textarea>
                            @error('reason') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-warning float-right">تطبيق التعديل الإداري</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- 3. سجل تاريخ حالة الطلب (Status History) --}}
        <div class="col-md-7">
            <div class="card card-secondary">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-history"></i> سجل تغييرات حالة الطلب</h3>
                </div>
                <div class="card-body p-0">
                    <ul class="products-list product-list-in-card pl-2 pr-2">
                        @forelse ($emergencyRequest->statusHistory as $history)
                            <li class="item">
                                <div class="product-info">
                                    <span class="product-title">
                                        {{-- تم تعديل الترجمة هنا --}}
                                        {{ $statusMapping[$history->status] ?? $history->status }}
                                        <span class="badge badge-secondary float-right">{{ $history->created_at->format('Y-m-d H:i:s') }}</span>
                                    </span>
                                    <span class="product-description">
                                        <strong>بواسطة:</strong> {{ $history->changedBy->full_name ?? 'النظام/المريض' }}
                                        @if($history->reason)
                                            | <strong>السبب/الملاحظات:</strong> {{ $history->reason }}
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

        {{-- 4. الموقع الجغرافي (Map/Location) --}}
        <div class="col-md-5">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-map-marker-alt"></i> موقع الطوارئ</h3>
                </div>
                <div class="card-body p-0">
                    @if ($emergencyRequest->location)
                        <div class="p-2 border-bottom bg-light">
                            <small class="d-block"><strong>العنوان:</strong> {{ $emergencyRequest->location->address ?? 'غير متوفر' }}</small>
                            <small class="d-block"><strong>الإحداثيات:</strong> {{ $emergencyRequest->location->latitude }}, {{ $emergencyRequest->location->longitude }}</small>
                        </div>
                        
                        <div style="width: 100%; height: 350px;">
                            <iframe 
                                width="100%" 
                                height="100%" 
                                frameborder="0" 
                                style="border:0;" 
                                src="https://maps.google.com/maps?q={{ $emergencyRequest->location->latitude }},{{ $emergencyRequest->location->longitude }}&hl=ar&z=17&output=embed">
                            </iframe>
                        </div>
                        
                        <div class="p-2">
                            <a href="https://www.google.com/maps/search/?api=1&query={{ $emergencyRequest->location->latitude }},{{ $emergencyRequest->location->longitude }}" 
                               target="_blank" class="btn btn-primary btn-sm btn-block">
                                <i class="fas fa-external-link-alt"></i> الانتقال إلى خرائط جوجل
                            </a>
                        </div>
                    @else
                        <div class="p-5 text-center">
                            <p class="text-danger font-weight-bold">بيانات الموقع غير متوفرة لهذا الطلب.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
@stop
