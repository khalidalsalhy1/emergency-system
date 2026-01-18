{{-- resources/views/admin/diseases/edit.blade.php --}}

@extends('layouts.admin')

@section('title', 'تعديل مرض: ' . $disease->disease_name)

@section('content_header')
    <h1><i class="fas fa-edit"></i> تعديل بيانات المرض المزمن</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">تعديل بيانات المرض: {{ $disease->disease_name }}</h3>
                </div>
                
                <form action="{{ route('admin.diseases.update', $disease->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        
                        {{-- اسم المرض --}}
                        <div class="form-group">
                            <label for="disease_name">اسم المرض</label>
                            <input type="text" name="disease_name" id="disease_name" 
                                   class="form-control @error('disease_name') is-invalid @enderror" 
                                   value="{{ old('disease_name', $disease->disease_name) }}" placeholder="مثال: داء السكري، ارتفاع ضغط الدم" required>
                            @error('disease_name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- الوصف --}}
                        <div class="form-group">
                            <label for="description">وصف موجز (اختياري)</label>
                            <textarea name="description" id="description" rows="4" 
                                      class="form-control @error('description') is-invalid @enderror" 
                                      placeholder="وصف مختصر للمرض وخطورته.">{{ old('description', $disease->description) }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        
                    </div>
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-warning">حفظ التعديلات</button>
                        <a href="{{ route('admin.diseases.index') }}" class="btn btn-default float-right">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
