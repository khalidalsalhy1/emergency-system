{{-- resources/views/admin/ratings/show.blade.php --}}

@extends('layouts.admin')

@section('title', 'تفاصيل التقييم #' . $feedback->id)

@section('content_header')
    <h1><i class="fas fa-eye"></i> تفاصيل التقييم #{{ $feedback->id }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">بيانات التقييم والملاحظة</h3>
                </div>
                <div class="card-body">
                    
                    <div class="row">
                        {{-- قيمة التقييم --}}
                        <div class="col-md-6">
                            <strong><i class="fas fa-star mr-1"></i> قيمة التقييم</strong>
                            <p class="text-muted">
                                <span class="badge badge-lg badge-info">{{ $feedback->rating }} / 5</span>
                            </p>
                            <hr>
                        </div>

                        {{-- تاريخ التقييم --}}
                        <div class="col-md-6">
                            <strong><i class="fas fa-calendar-alt mr-1"></i> تاريخ التقييم</strong>
                            <p class="text-muted">{{ $feedback->created_at->format('Y-m-d H:i') }}</p>
                            <hr>
                        </div>
                        
                        {{-- المستخدم المُقيّم --}}
                        <div class="col-md-6">
                            <strong><i class="fas fa-user-circle mr-1"></i> المستخدم المُقيّم</strong>
                            <p class="text-muted">{{ $feedback->user->full_name ?? $feedback->user->name ?? 'مستخدم محذوف' }}</p>
                            <hr>
                        </div>

                        {{-- المستشفى --}}
                        <div class="col-md-6">
                            <strong><i class="fas fa-hospital mr-1"></i> المستشفى المُقيّمة</strong>
                            <p class="text-muted">{{ $feedback->hospital->hospital_name ?? 'غير محدد' }}</p>
                            <hr>
                        </div>

                        {{-- طلب الطوارئ --}}
                        <div class="col-12">
                            <strong><i class="fas fa-ambulance mr-1"></i> طلب الطوارئ المرتبط</strong>
                            <p class="text-muted">
                                <a href="{{ route('admin.emergency_requests.show', $feedback->emergencyRequest->id) }}">
                                    #{{ $feedback->emergencyRequest->id }} (الحالة: {{ $feedback->emergencyRequest->status }})
                                </a>
                            </p>
                            <hr>
                        </div>

                        {{-- الملاحظات --}}
                        <div class="col-12">
                            <strong><i class="fas fa-comment-dots mr-1"></i> الملاحظات/التعليقات</strong>
                            <p class="text-muted">{{ $feedback->comments ?? 'لا توجد ملاحظات مكتوبة.' }}</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.ratings.index') }}" class="btn btn-default">
                        <i class="fas fa-arrow-right"></i> العودة إلى القائمة
                    </a>
                    <form action="{{ route('admin.ratings.destroy', $feedback->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger float-right" onclick="return confirm('هل أنت متأكد من حذف هذا التقييم؟')">
                            <i class="fas fa-trash"></i> حذف التقييم
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
