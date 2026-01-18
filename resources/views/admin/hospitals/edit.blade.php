@extends('layouts.admin') 

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                {{-- عرض اسم المستشفى الحالي في العنوان --}}
                <h1 class="m-0 text-dark">تعديل المستشفى: {{ $hospital->hospital_name }}</h1>
            </div><div class="col-sm-6">
                <ol class="breadcrumb float-sm-left">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.hospitals.index') }}">المستشفيات</a></li>
                    <li class="breadcrumb-item active">تعديل</li>
                </ol>
            </div></div></div></div>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-warning"> 
                    <div class="card-header">
                        <h3 class="card-title">تعديل بيانات المستشفى والموقع</h3>
                    </div>
                    
                    {{-- **** نموذج التعديل الفعلي **** --}}
                    <form action="{{ route('admin.hospitals.update', $hospital->id) }}" method="POST">
                        @csrf
                        @method('PUT') {{-- توجيه الطلب إلى دالة updateWeb --}}
                        
                        <div class="card-body">
                            
                            {{-- رسائل الأخطاء (إذا وجدت) --}}
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            
                            {{-- بيانات المستشفى --}}
                            <fieldset class="mb-4 p-3 border">
                                <legend class="w-auto px-2">معلومات المستشفى الأساسية</legend>
                                <div class="form-group">
                                    <label for="hospital_name">اسم المستشفى</label>
                                    <input type="text" name="hospital_name" class="form-control" id="hospital_name" 
                                           value="{{ old('hospital_name', $hospital->hospital_name) }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="phone">رقم الهاتف الأساسي</label>
                                    <input type="text" name="phone" class="form-control" id="phone" 
                                           value="{{ old('phone', $hospital->phone) }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="emergency_number">رقم الطوارئ</label>
                                    <input type="text" name="emergency_number" class="form-control" id="emergency_number" 
                                           value="{{ old('emergency_number', $hospital->emergency_number) }}">
                                </div>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label for="city">المدينة</label>
                                        <input type="text" name="city" class="form-control" id="city" 
                                               value="{{ old('city', $hospital->city) }}" required>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="district">المنطقة</label>
                                        <input type="text" name="district" class="form-control" id="district" 
                                               value="{{ old('district', $hospital->district) }}">
                                    </div>
                                </div>
                            </fieldset>

                            {{-- بيانات الموقع (تم التصحيح لاستخدام العلاقة) --}}
                            <fieldset class="mb-4 p-3 border">
                                <legend class="w-auto px-2">بيانات الموقع الجغرافي</legend>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label for="latitude">خط العرض (Latitude)</label>
                                        <input type="text" name="latitude" class="form-control" id="latitude" 
                                               {{-- 🚨 التعديل: استخدام optional($hospital->location)->latitude --}}
                                               value="{{ old('latitude', optional($hospital->location)->latitude) }}" required>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="longitude">خط الطول (Longitude)</label>
                                        <input type="text" name="longitude" class="form-control" id="longitude" 
                                               {{-- 🚨 التعديل: استخدام optional($hospital->location)->longitude --}}
                                               value="{{ old('longitude', optional($hospital->location)->longitude) }}" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="address">العنوان التفصيلي</label>
                                    <textarea name="address" class="form-control" id="address">{{ old('address', optional($hospital->location)->address) }}</textarea>
                                </div>
                            </fieldset>
                            
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-warning"><i class="fas fa-edit"></i> تحديث البيانات</button>
                            <a href="{{ route('admin.hospitals.index') }}" class="btn btn-default float-left">إلغاء</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
