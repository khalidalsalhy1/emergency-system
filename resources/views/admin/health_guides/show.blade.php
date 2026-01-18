{{-- resources/views/admin/health_guides/show.blade.php --}}

@extends('layouts.admin')

@section('title', 'تفاصيل الإرشاد الصحي')

@section('content_header')
    <h1><i class="fas fa-eye"></i> تفاصيل الإرشاد الصحي</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-10">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">{{ $healthGuide->title }}</h3>
                </div>
                <div class="card-body">
                    
                    {{-- الصورة التوضيحية --}}
                    @if ($healthGuide->image)
                        <div class="text-center mb-4">
                            <img src="{{ Storage::url($healthGuide->image) }}" alt="{{ $healthGuide->title }}" style="max-width: 100%; height: auto; border-radius: 8px;">
                            <hr>
                        </div>
                    @endif

                    <div class="row">
                        {{-- العنوان والتصنيف --}}
                        <div class="col-md-6">
                            <strong><i class="fas fa-heading mr-1"></i> العنوان</strong>
                            <p class="text-muted">{{ $healthGuide->title }}</p>
                            <hr>
                        </div>
                        <div class="col-md-6">
                            <strong><i class="fas fa-tag mr-1"></i> التصنيف</strong>
                            <p class="text-muted">{{ $healthGuide->category ?? 'عام' }}</p>
                            <hr>
                        </div>
                        
                        {{-- المحتوى --}}
                        <div class="col-12">
                            <strong><i class="fas fa-file-alt mr-1"></i> محتوى الإرشاد</strong>
                            {{-- استخدام raw أو {!! !!} لعرض محتوى قد يكون HTML إذا تم استخدام محرر نصوص غني (Rich Editor) --}}
                            <div class="p-3 border rounded" style="background-color: #f8f9fa;">
                                {!! nl2br(e($healthGuide->content)) !!}
                            </div>
                            <hr>
                        </div>
                        
                        {{-- التواريخ --}}
                        <div class="col-md-6">
                            <strong><i class="far fa-clock mr-1"></i> تاريخ الإنشاء</strong>
                            <p class="text-muted">{{ $healthGuide->created_at->format('Y-m-d H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong><i class="fas fa-sync-alt mr-1"></i> تاريخ آخر تعديل</strong>
                            <p class="text-muted">{{ $healthGuide->updated_at->format('Y-m-d H:i') }}</p>
                        </div>

                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.health_guides.edit', $healthGuide->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> تعديل
                    </a>
                    <a href="{{ route('admin.health_guides.index') }}" class="btn btn-default float-right">
                        <i class="fas fa-arrow-right"></i> العودة إلى القائمة
                    </a>
                </div>
            </div>
        </div>
    </div>
@stop
