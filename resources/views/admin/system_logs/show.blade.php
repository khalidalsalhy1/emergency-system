@extends('layouts.admin')

@section('title', 'تفاصيل سجل النظام: ' . $log->id)

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">تفاصيل سجل النظام #{{ $log->id }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">الرئيسية</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.system_logs.index') }}">سجل النظام</a></li>
                        <li class="breadcrumb-item active">تفاصيل</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-10 offset-md-1">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-info-circle"></i> معلومات السجل الأساسية</h3>
                            <div class="card-tools">
                                <span class="badge badge-primary">{{ $log->action }}</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <strong><i class="fas fa-calendar-alt mr-1"></i> تاريخ ووقت الحدث:</strong>
                                    <p class="text-muted">{{ $log->created_at->format('Y-m-d H:i:s') }} ({{ $log->created_at->diffForHumans() }})</p>
                                </div>
                                <div class="col-12 mb-3">
                                    <strong><i class="fas fa-user-shield mr-1"></i> المستخدم الذي قام بالحدث:</strong>
                                    <p class="text-muted">
                                        {{ $log->user->full_name ?? $log->user->name ?? 'مستخدم محذوف' }} (ID: {{ $log->user_id }})
                                    </p>
                                </div>
                            </div>
                            <hr>
                            <strong><i class="fas fa-file-alt mr-1"></i> التفاصيل الكاملة (Details):</strong>
                            <pre class="bg-light p-3 rounded" style="white-space: pre-wrap; word-wrap: break-word;">{{ $log->details }}</pre>
                            
                            {{-- هذا الشرط يحاول تنسيق حقل التفاصيل إذا كان بصيغة JSON --}}
                            @if (@json_decode($log->details) !== null)
                                <strong><i class="fas fa-code mr-1"></i> التفاصيل المنسقة (JSON):</strong>
                                <pre class="bg-light p-3 rounded"><code class="json">{{ json_encode(json_decode($log->details, true), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                            @endif
                        </div>
                        </div>
                </div>
            </div>
        </div>
    </section>
    </div>
@endsection
