@extends('layouts.admin')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">إضافة مسؤول مستشفى جديد</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-left">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.hospital_admins.index') }}">مسؤولو المستشفيات</a></li>
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
                        <h3 class="card-title">تعبئة بيانات المسؤول</h3>
                    </div>
                    
                    <form action="{{ route('admin.hospital_admins.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            {{-- الاسم الكامل --}}
                            <div class="form-group">
                                <label for="full_name">الاسم الكامل</label>
                                <input type="text" name="full_name" class="form-control @error('full_name') is-invalid @enderror" id="full_name" value="{{ old('full_name') }}">
                                @error('full_name') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span> @enderror
                            </div>

                            {{-- رقم الهاتف (للتسجيل والدخول) --}}
                            <div class="form-group">
                                <label for="phone">رقم الهاتف</label>
                                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" id="phone" value="{{ old('phone') }}" placeholder="مثال: 0096777xxxxxxx">
                                @error('phone') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span> @enderror
                            </div>
                            
                            {{-- البريد الإلكتروني (غير أساسي للدخول لكن مطلوب لبعض العمليات) --}}
                            <div class="form-group">
                                <label for="email">البريد الإلكتروني (اختياري)</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email" value="{{ old('email') }}">
                                @error('email') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span> @enderror
                            </div>
                            
                            {{-- الرقم الوطني --}}
                            <div class="form-group">
                                <label for="national_id">الرقم الوطني</label>
                                <input type="text" name="national_id" class="form-control @error('national_id') is-invalid @enderror" id="national_id" value="{{ old('national_id') }}">
                                @error('national_id') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span> @enderror
                            </div>

                            {{-- اختيار المستشفى --}}
                            <div class="form-group">
                                <label for="hospital_id">المستشفى المرتبط</label>
                                <select name="hospital_id" id="hospital_id" class="form-control @error('hospital_id') is-invalid @enderror">
                                    <option value="">-- اختر مستشفى --</option>
                                    @foreach ($hospitals as $hospital)
                                        <option value="{{ $hospital->id }}" {{ old('hospital_id') == $hospital->id ? 'selected' : '' }}>
                                            {{ $hospital->hospital_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('hospital_id') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span> @enderror
                            </div>
                            
                            {{-- الحالة --}}
                            <div class="form-group">
                                <label for="status">حالة الحساب</label>
                                <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>نشط</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                                </select>
                                @error('status') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span> @enderror
                            </div>

                            <hr>
                            
                            {{-- كلمة المرور --}}
                            <div class="form-group">
                                <label for="password">كلمة المرور</label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password">
                                @error('password') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span> @enderror
                            </div>
                            
                            {{-- تأكيد كلمة المرور --}}
                            <div class="form-group">
                                <label for="password_confirmation">تأكيد كلمة المرور</label>
                                <input type="password" name="password_confirmation" class="form-control" id="password_confirmation">
                            </div>

                        </div>
                        
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">حفظ وإضافة</button>
                            <a href="{{ route('admin.hospital_admins.index') }}" class="btn btn-default float-left">إلغاء</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
