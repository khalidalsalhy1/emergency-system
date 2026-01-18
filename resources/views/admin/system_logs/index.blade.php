@extends('layouts.admin')

@section('title', 'سجل النظام الأمني')

{{-- 🚨 الحل النهائي: إضافة CSS مخصص مباشرة في الـ View 🚨 --}}
@section('css')
<style>
    /* 1. إصلاح تمدد الجدول وضمان التغليف */
    .table-fixed-layout-custom table {
        table-layout: fixed !important; /* إجبار التنسيق */
        width: 100% !important;
    }
    .table-fixed-layout-custom td {
        word-wrap: break-word; /* تغليف الكلمات الطويلة جداً */
        white-space: normal !important; /* السماح بالسطور المتعددة */
    }
    
    /* 2. إصلاح حجم زر العرض ليعود إلى حجم btn-xs القياسي (صغير وليس صغيراً جداً) */
    .btn-xs-fix {
        padding: 0.25rem 0.5rem !important; /* حجم صغير (Slightly larger than 1px 5px) */
        font-size: 0.75rem !important; /* حجم الخط القياسي لـ btn-sm تقريباً */
        line-height: 1.5 !important;
        border-radius: 0.2rem !important;
    }
</style>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">سجل النظام (الأمني)</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">الرئيسية</a></li>
                        <li class="breadcrumb-item active">سجل النظام</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">قائمة بجميع الأنشطة الإدارية والأمنية الحساسة</h3>
                        </div>
                        <div class="card-body p-0">
                            {{-- إضافة الكلاس الحاوي للتنسيق --}}
                            <div class="table-responsive table-fixed-layout-custom">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th style="width: 5%;">#</th>
                                            <th style="width: 15%;">الحدث (Action)</th>
                                            <th style="width: 15%;">قام به المستخدم</th>
                                            <th style="width: 30%;">التفاصيل الموجزة</th> 
                                            <th style="width: 15%;">تاريخ ووقت الحدث</th>
                                            <th style="width: 20%;">الإجراء</th> 
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($logs as $log)
                                            <tr>
                                                <td>{{ $log->id }}</td>
                                                <td>
                                                    <span class="badge badge-info">{{ $log->action }}</span>
                                                </td>
                                                <td>
                                                    {{ $log->user->full_name ?? $log->user->name ?? 'مستخدم محذوف' }}
                                                    <small class="text-muted d-block">ID: {{ $log->user_id }}</small>
                                                </td>
                                                
                                                {{-- عرض السطر الأول فقط --}}
                                                <td>
                                                    @php
                                                        $details = $log->details;
                                                        $firstSentence = explode('. ', $details, 2)[0];
                                                        $firstSentence = trim($firstSentence);
                                                    @endphp
                                                    
                                                    @if (!empty($firstSentence))
                                                        {{ $firstSentence }}. 
                                                    @else
                                                        {{ \Illuminate\Support\Str::limit($log->details, 100, '...') }}
                                                    @endif
                                                </td>
                                                
                                                <td>
                                                    {{ $log->created_at->format('Y-m-d H:i:s') }}
                                                    <small class="text-muted d-block">{{ $log->created_at->diffForHumans() }}</small>
                                                </td>
                                                
                                                <td class="text-center">
                                                    {{-- استخدام الكلاس الجديد المعدل للحجم --}}
                                                    <a href="{{ route('admin.system_logs.show', $log->id) }}" 
                                                       class="btn btn-info btn-xs-fix" 
                                                       title="التفاصيل الكاملة">
                                                        <i class="fas fa-eye"></i> عرض
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">لا توجد سجلات نظام متاحة حالياً.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer clearfix">
                            {{ $logs->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
