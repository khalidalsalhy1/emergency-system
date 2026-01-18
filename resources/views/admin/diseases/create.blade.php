{{-- resources/views/admin/diseases/create.blade.php --}}

@extends('layouts.admin')

@section('title', 'إضافة مرض مزمن جديد')

@section('content_header')
    <h1><i class="fas fa-plus-circle"></i> إضافة مرض مزمن جديد</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">إدخال بيانات المرض</h3>
                </div>
                
                <form action="{{ route('admin.diseases.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        
                        {{-- اسم المرض --}}
                        <div class="form-group">
                            <label for="disease_name">اسم المرض</label>
                            <input type="text" name="disease_name" id="disease_name" 
                                   class="form-control @error('disease_name') is-invalid @enderror" 
                                   value="{{ old('disease_name') }}" placeholder="مثال: داء السكري، ارتفاع ضغط الدم" required>
                            @error('disease_name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- الوصف --}}
                        <div class="form-group">
                            <label for="description">وصف موجز (اختياري)</label>
                            <textarea name="description" id="description" rows="4" 
                                      class="form-control @error('description') is-invalid @enderror" 
                                      placeholder="وصف مختصر للمرض وخطورته.">{{ old('description') }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        
                    </div>
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">حفظ المرض</button>
                        <a href="{{ route('admin.diseases.index') }}" class="btn btn-default float-right">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
