{{-- resources/views/admin/hospitals/show.blade.php --}}

@extends('layouts.admin') 

@section('title', 'تفاصيل مستشفى: ' . $hospital->hospital_name)

@section('content_header')
    <h1><i class="fas fa-hospital"></i> تفاصيل ومراجعة مستشفى: {{ $hospital->hospital_name }}</h1>
@stop

@section('content')
    <div class="row">
        {{-- عرض رسائل النجاح/الخطأ --}}
        @if (session('success'))
            <div class="col-12 alert alert-success alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                {{ session('success') }}
            </div>
        @endif
        
        {{-- 1. بطاقة بيانات المستشفى الأساسية والإحصائيات --}}
        <div class="col-md-7">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">بيانات المستشفى العامة</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        {{-- معلومات التواصل --}}
                        <div class="col-md-6 border-right">
                            <h4><i class="fas fa-info-circle text-primary"></i> معلومات التواصل</h4>
                            <p><strong>اسم المستشفى:</strong> {{ $hospital->hospital_name }}</p>
                            <p><strong>رقم الهاتف:</strong> {{ $hospital->phone }}</p>
                            <p><strong>رقم الطوارئ:</strong> <span class="text-danger font-weight-bold">{{ $hospital->emergency_number ?? 'غير متوفر' }}</span></p>
                            <p><strong>البريد الإلكتروني:</strong> {{ $hospital->email ?? 'لا يوجد' }}</p>
                            <hr>
                            
                            <h4><i class="fas fa-chart-line text-success"></i> إحصائيات سريعة</h4>
                            <p><strong>إجمالي الطلبات:</strong> <span class="badge badge-info">{{ $hospital->emergency_requests_count }} طلب</span></p>
                            <p><strong>تاريخ الإضافة:</strong> {{ $hospital->created_at->format('Y-m-d') }}</p>
                        </div>

                        {{-- الوصف والمدينة --}}
                        <div class="col-md-6">
                            <h4><i class="fas fa-map-marked-alt text-info"></i> الموقع الإداري</h4>
                            <p><strong>المدينة:</strong> <span class="badge badge-secondary">{{ $hospital->city ?? 'غير محدد' }}</span></p>
                            <p><strong>العنوان الوصفي:</strong> {{ $hospital->location->address ?? 'لا يوجد عنوان مسجل' }}</p>
                            
                            <hr>
                            
                            <h4><i class="fas fa-align-left text-secondary"></i> الوصف/ملاحظات</h4>
                            <p class="text-muted">{{ $hospital->description ?? 'لا يوجد وصف مضاف لهذا المستشفى.' }}</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.hospitals.edit', $hospital->id) }}" class="btn btn-warning shadow-sm">
                        <i class="fas fa-edit"></i> تعديل بيانات المستشفى
                    </a>
                </div>
            </div>

            {{-- 3. جدول المسؤولين المرتبطين بالمستشفى --}}
            <div class="card card-secondary">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-users-cog"></i> المسؤولين المرتبطين (Admins)</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>الاسم</th>
                                <th>البريد الإلكتروني</th>
                                <th>الحالة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($hospital->admins as $admin)
                                <tr>
                                    <td>{{ $admin->full_name }}</td>
                                    <td>{{ $admin->email }}</td>
                                    <td>
                                        <span class="badge badge-{{ $admin->status == 'active' ? 'success' : 'danger' }}">
                                            {{ $admin->status == 'active' ? 'نشط' : 'غير نشط' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="p-3 text-center text-muted">لا يوجد مسؤولين مرتبطين بهذا المستشفى حالياً.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        {{-- الجانب الأيسر --}}
        <div class="col-md-5">
            {{-- 2. الموقع الجغرافي (Map/Location) - بنفس تنسيق صفحة الطوارئ --}}
            <div class="card card-primary card-outline shadow">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-map-marker-alt"></i> الموقع الجغرافي</h3>
                </div>
                <div class="card-body p-0">
                    @if ($hospital->location)
                        <div class="p-2 border-bottom bg-light text-sm">
                            <span class="d-block"><strong>الإحداثيات:</strong> {{ $hospital->location->latitude }}, {{ $hospital->location->longitude }}</span>
                        </div>
                        
                        {{-- الخريطة تملأ الحاوية --}}
                        <div style="width: 100%; height: 400px;">
                            <iframe 
                                width="100%" 
                                height="100%" 
                                frameborder="0" 
                                style="border:0;" 
                                src="https://maps.google.com/maps?q={{ $hospital->location->latitude }},{{ $hospital->location->longitude }}&hl=ar&z=16&output=embed">
                            </iframe>
                        </div>
                        
                        <div class="p-2">
                            <a href="https://www.google.com/maps/search/?api=1&query={{ $hospital->location->latitude }},{{ $hospital->location->longitude }}" 
                               target="_blank" class="btn btn-primary btn-sm btn-block shadow-sm">
                                <i class="fas fa-external-link-alt"></i> فتح الموقع في خرائط جوجل
                            </a>
                        </div>
                    @else
                        <div class="p-5 text-center text-danger font-weight-bold">
                            <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                            <p>بيانات الموقع الجغرافي (الإحداثيات) غير متوفرة.</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- 4. إجراءات سريعة --}}
            <div class="card card-dark">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-bolt"></i> إجراءات إضافية</h3>
                </div>
                <div class="card-body">
                    <a href="{{ route('admin.hospitals.index') }}" class="btn btn-outline-secondary btn-block">
                        <i class="fas fa-arrow-left"></i> العودة لقائمة المستشفيات
                    </a>
                </div>
            </div>
        </div>
    </div>
@stop
