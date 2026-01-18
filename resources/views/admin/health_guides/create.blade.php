{{-- resources/views/admin/health_guides/create.blade.php --}}

@extends('layouts.admin')

@section('title', 'إضافة إرشاد صحي جديد')

@section('content_header')
    <h1><i class="fas fa-plus"></i> إضافة إرشاد صحي جديد</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-10">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">بيانات الإرشاد</h3>
                </div>
                {{-- مهم: يجب إضافة enctype لرفع الملفات (الصور) --}}
                <form action="{{ route('admin.health_guides.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        
                        {{-- العنوان --}}
                        <div class="form-group">
                            <label for="title">عنوان الإرشاد <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" id="title" value="{{ old('title') }}" placeholder="مثال: كيفية التعامل مع حالات الإغماء">
                            @error('title')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        {{-- التصنيف (اختياري) --}}
                        <div class="form-group">
                            <label for="category">التصنيف (اختياري)</label>
                            <input type="text" name="category" class="form-control @error('category') is-invalid @enderror" id="category" value="{{ old('category') }}" placeholder="مثال: إسعافات أولية، وقاية، تغذية">
                            @error('category')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- المحتوى (الوصف التفصيلي) --}}
                        <div class="form-group">
                            <label for="content">محتوى الإرشاد (الخطوات التفصيلية) <span class="text-danger">*</span></label>
                            <textarea name="content" class="form-control @error('content') is-invalid @enderror" id="content" rows="8" placeholder="أدخل الخطوات والإرشادات هنا...">{{ old('content') }}</textarea>
                            @error('content')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- الصورة التوضيحية --}}
                        <div class="form-group">
                            <label for="image">صورة توضيحية (اختياري)</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" name="image" class="custom-file-input @error('image') is-invalid @enderror" id="image">
                                    <label class="custom-file-label" for="image" data-browse="استعراض">اختر ملف صورة</label>
                                </div>
                            </div>
                            @error('image')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">حفظ الإرشاد</button>
                        <a href="{{ route('admin.health_guides.index') }}" class="btn btn-default float-right">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('js')
    {{-- لتفعيل اسم الملف في واجهة رفع الملفات في Bootstrap --}}
    <script>
        document.getElementById('image').addEventListener('change', function(e) {
            var fileName = e.target.files[0].name;
            var nextSibling = e.target.nextElementSibling;
            nextSibling.innerText = fileName;
        });
    </script>
@endsection
