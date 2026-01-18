@extends('layouts.admin')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">إضافة نوع إصابة جديد</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-left">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.injury_types.index') }}">أنواع الإصابات</a></li>
                    <li class="breadcrumb-item active">إضافة</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">تعبئة بيانات نوع الإصابة</h3>
                    </div>
                    
                    <form action="{{ route('admin.injury_types.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            {{-- حقل اسم الإصابة --}}
                            <div class="form-group">
                                <label for="injury_name">اسم الإصابة</label>
                                <input type="text" name="injury_name" class="form-control @error('injury_name') is-invalid @enderror" id="injury_name" value="{{ old('injury_name') }}" placeholder="مثال: حروق من الدرجة الأولى">
                                @error('injury_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            {{-- حقل الوصف --}}
                            <div class="form-group">
                                <label for="description">الوصف (اختياري)</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" id="description" rows="3" placeholder="وصف موجز لنوع الإصابة">{{ old('description') }}</textarea>
                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                        </div>
                        
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">حفظ وإضافة</button>
                            <a href="{{ route('admin.injury_types.index') }}" class="btn btn-default float-left">إلغاء</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
