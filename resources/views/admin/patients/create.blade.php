{{-- resources/views/admin/patients/create.blade.php --}}

@extends('layouts.admin')

@section('title', 'إضافة مريض جديد')

@section('content_header')
    <h1><i class="fas fa-user-plus"></i> إضافة مريض جديد للنظام</h1>
@stop

@section('content')
<div class="container-fluid">
    {{-- عرض تنبيهات الأخطاء العامة --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible shadow-sm">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-exclamation-triangle"></i> يرجى تصحيح الأخطاء التالية:</h5>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card card-primary card-outline shadow">
        <form action="{{ route('admin.patients.store') }}" method="POST" novalidate>
            @csrf
            
            <div class="card-header p-2">
                <ul class="nav nav-pills" id="patientTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="user-tab" data-toggle="tab" href="#userData" role="tab">
                            <i class="fas fa-id-card"></i> البيانات الأساسية
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="medical-tab" data-toggle="tab" href="#medicalData" role="tab">
                            <i class="fas fa-file-medical"></i> السجل الطبي
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="diseases-tab" data-toggle="tab" href="#chronicDiseases" role="tab">
                            <i class="fas fa-procedures"></i> الأمراض المزمنة
                        </a>
                    </li>
                </ul>
            </div>

            <div class="card-body">
                <div class="tab-content" id="patientTabsContent">
                    
                    {{-- 1. تبويبة البيانات الأساسية --}}
                    <div class="tab-pane fade show active" id="userData" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="full_name">الاسم الكامل <span class="text-danger">*</span></label>
                                <input type="text" name="full_name" id="full_name" class="form-control @error('full_name') is-invalid @enderror" value="{{ old('full_name') }}" placeholder="أدخل الاسم الرباعي">
                                @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="phone">رقم الهاتف <span class="text-danger">*</span></label>
                                <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="7xxxxxxxxx">
                                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="email">البريد الإلكتروني</label>
                                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="example@mail.com">
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="national_id">الهوية الوطنية</label>
                                <input type="text" name="national_id" id="national_id" class="form-control @error('national_id') is-invalid @enderror" value="{{ old('national_id') }}">
                                @error('national_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="password">كلمة المرور <span class="text-danger">*</span></label>
                                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror">
                                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="password_confirmation">تأكيد كلمة المرور <span class="text-danger">*</span></label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="status">حالة الحساب <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-control select2 @error('status') is-invalid @enderror">
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>نشط</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>غير نشط/محظور</option>
                                </select>
                                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    {{-- 2. تبويبة السجل الطبي --}}
                    <div class="tab-pane fade" id="medicalData" role="tabpanel">
                        <div class="row mt-2">
                            <div class="col-md-4 form-group">
                                <label for="birth_date">تاريخ الميلاد <span class="text-danger">*</span></label>
                                <input type="date" name="birth_date" id="birth_date" class="form-control @error('birth_date') is-invalid @enderror" value="{{ old('birth_date') }}">
                                @error('birth_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="gender">الجنس <span class="text-danger">*</span></label>
                                <select name="gender" id="gender" class="form-control @error('gender') is-invalid @enderror">
                                    <option value="">-- اختر --</option>
                                    <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>ذكر</option>
                                    <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>أنثى</option>
                                </select>
                                @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="blood_type">فصيلة الدم <span class="text-danger">*</span></label>
                                <select name="blood_type" id="blood_type" class="form-control @error('blood_type') is-invalid @enderror">
                                    <option value="">-- اختر --</option>
                                    @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $type)
                                        <option value="{{ $type }}" {{ old('blood_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                                    @endforeach
                                </select>
                                @error('blood_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-12 form-group">
                                <label for="emergency_contact">رقم قريب للتواصل عند الطوارئ <span class="text-danger">*</span></label>
                                <input type="text" name="emergency_contact" id="emergency_contact" class="form-control @error('emergency_contact') is-invalid @enderror" value="{{ old('emergency_contact') }}">
                                @error('emergency_contact')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-12 form-group">
                                <label for="medical_history">تاريخ طبي سابق (عمليات/إصابات) <span class="text-danger">*</span></label>
                                <textarea name="medical_history" id="medical_history" class="form-control" rows="2">{{ old('medical_history') }}</textarea>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="allergies">الحساسيات <span class="text-danger">*</span></label>
                                <textarea name="allergies" id="allergies" class="form-control" rows="2" placeholder="أدوية، أطعمة...">{{ old('allergies') }}</textarea>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="current_medications">الأدوية الحالية <span class="text-danger">*</span></label>
                                <textarea name="current_medications" id="current_medications" class="form-control" rows="2">{{ old('current_medications') }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- 3. تبويبة الأمراض المزمنة --}}
                    <div class="tab-pane fade" id="chronicDiseases" role="tabpanel">
                        <div class="alert alert-info mt-2">
                            <i class="fas fa-info-circle"></i> يرجى تحديد الأمراض المزمنة التي يعاني منها المريض إن وجدت.
                        </div>
                        <input type="hidden" name="diseases_ids" value="">
                        <div class="row p-3">
                            @forelse($diseases as $disease)
                                <div class="col-md-4 mb-2">
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" name="diseases_ids[]" value="{{ $disease->id }}" id="disease_{{ $disease->id }}" 
                                            {{ in_array($disease->id, old('diseases_ids', [])) ? 'checked' : '' }}>
                                        <label class="custom-control-label font-weight-normal" for="disease_{{ $disease->id }}">
                                            {{ $disease->disease_name }}
                                        </label>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center py-4">
                                    <p class="text-muted">لا يوجد أمراض مزمنة معرفة في النظام.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer text-right">
                <a href="{{ route('admin.patients.index') }}" class="btn btn-secondary mr-2">إلغاء</a>
                <button type="submit" class="btn btn-success px-5 shadow">
                    <i class="fas fa-save"></i> حفظ بيانات المريض
                </button>
            </div>
        </form>
    </div>
</div>
@stop
