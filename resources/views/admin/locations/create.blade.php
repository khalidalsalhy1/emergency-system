{{-- resources/views/admin/locations/create.blade.php --}}

@extends('layouts.admin')

@section('title', 'إضافة موقع جديد')

@section('content_header')
    <h1><i class="fas fa-plus"></i> إضافة موقع جغرافي جديد</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">بيانات الموقع</h3>
                </div>
                <form action="{{ route('admin.locations.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        
                        {{-- خط العرض --}}
                        <div class="form-group">
                            <label for="latitude">خط العرض (Latitude) <span class="text-danger">*</span></label>
                            <input type="text" name="latitude" class="form-control @error('latitude') is-invalid @enderror" id="latitude" value="{{ old('latitude') }}" placeholder="أدخل خط العرض (مثل 30.0000)">
                            @error('latitude')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- خط الطول --}}
                        <div class="form-group">
                            <label for="longitude">خط الطول (Longitude) <span class="text-danger">*</span></label>
                            <input type="text" name="longitude" class="form-control @error('longitude') is-invalid @enderror" id="longitude" value="{{ old('longitude') }}" placeholder="أدخل خط الطول (مثل 31.0000)">
                            @error('longitude')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        {{-- العنوان التقديري --}}
                        <div class="form-group">
                            <label for="address">العنوان التقديري/الوصف (اختياري)</label>
                            <textarea name="address" class="form-control @error('address') is-invalid @enderror" id="address" rows="3" placeholder="وصف تقديري للموقع">{{ old('address') }}</textarea>
                            @error('address')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- الربط بمستشفى (اختياري) --}}
                        <div class="form-group">
                            <label for="hospital_id">ربط الموقع بمستشفى (إذا كان موقع فرع)</label>
                            <select name="hospital_id" id="hospital_id" class="form-control select2 @error('hospital_id') is-invalid @enderror">
                                <option value="">-- لا يوجد مستشفى مرتبط --</option>
                                @foreach($hospitals as $id => $name)
                                    <option value="{{ $id }}" {{ old('hospital_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                            @error('hospital_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- ملاحظة: لا يتم تسجيل user_id هنا يدوياً --}}

                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">حفظ الموقع</button>
                        <a href="{{ route('admin.locations.index') }}" class="btn btn-default float-right">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

{{-- إضافة مكتبة Select2 إذا كانت مستخدمة لتجميل قائمة المستشفيات --}}
@section('js')
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                dir: "rtl"
            });
        });
    </script>
@endsection
