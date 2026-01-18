{{-- resources/views/admin/request_history/show.blade.php --}}

@extends('layouts.admin')

@section('title', 'تفاصيل سجل الطلب #' . $requestStatusHistory->id)

@section('content_header')
    <h1><i class="fas fa-eye"></i> تفاصيل سجل الطلب #{{ $requestStatusHistory->id }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">بيانات سجل حالة الطلب</h3>
                </div>
                <div class="card-body">
                    
                    <div class="row">
                        {{-- رقم الطلب --}}
                        <div class="col-md-6">
                            <strong><i class="fas fa-ambulance mr-1"></i> رقم الطلب المرتبط</strong>
                            <p class="text-muted">
                                <a href="{{ route('admin.emergency_requests.show', $requestStatusHistory->emergencyRequest->id) }}">
                                    #{{ $requestStatusHistory->emergencyRequest->id }}
                                </a>
                            </p>
                            <hr>
                        </div>

                        {{-- الحالة الجديدة --}}
                        <div class="col-md-6">
                            <strong><i class="fas fa-sync-alt mr-1"></i> الحالة الجديدة</strong>
                            <p class="text-muted">
                                <span class="badge badge-lg badge-{{ $requestStatusHistory->isCompleted() ? 'success' : ($requestStatusHistory->isPending() ? 'warning' : 'info') }}">
                                    {{ $requestStatusHistory->status }}
                                </span>
                            </p>
                            <hr>
                        </div>
                        
                        {{-- المستخدم المُغيّر --}}
                        <div class="col-md-6">
                            <strong><i class="fas fa-user-tag mr-1"></i> تم التغيير بواسطة</strong>
                            <p class="text-muted">{{ $requestStatusHistory->changedBy->full_name ?? $requestStatusHistory->changedBy->name ?? 'غير محدد' }}</p>
                            <hr>
                        </div>

                        {{-- تاريخ التغيير --}}
                        <div class="col-md-6">
                            <strong><i class="fas fa-calendar-alt mr-1"></i> تاريخ ووقت التغيير</strong>
                            {{-- 🎯 التصحيح النهائي: استخدام changed_at وفي حال كان null نعتمد على created_at --}}
                            <p class="text-muted">
                                {{ ($requestStatusHistory->changed_at ?? $requestStatusHistory->created_at) ? ($requestStatusHistory->changed_at ?? $requestStatusHistory->created_at)->format('Y-m-d H:i:s') : 'غير مسجل' }}
                            </p>
                            <hr>
                        </div>

                        {{-- سبب التغيير --}}
                        <div class="col-12">
                            <strong><i class="fas fa-clipboard-list mr-1"></i> ملاحظات/سبب التغيير</strong>
                            <p class="text-muted">{{ $requestStatusHistory->reason ?? 'لا يوجد سبب محدد مسجل.' }}</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.request_history.index') }}" class="btn btn-default">
                        <i class="fas fa-arrow-right"></i> العودة إلى القائمة
                    </a>
                </div>
            </div>
        </div>
    </div>
@stop
