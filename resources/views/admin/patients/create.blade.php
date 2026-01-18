@extends('layouts.admin')

@section('title', 'إضافة مريض جديد')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">إضافة مريض جديد</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">بيانات المريض والسجل الطبي</h6>
        </div>
        <div class="card-body">
            {{-- تم إضافة "novalidate" لمنع تحذيرات المتصفح بسبب التبويبات المخفية --}}
            <form action="{{ route('admin.patients.store') }}" method="POST" novalidate>
                @csrf
                
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <ul class="nav nav-tabs" id="patientTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="user-tab" data-toggle="tab" href="#userData" role="tab">البيانات الأساسية</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="medical-tab" data-toggle="tab" href="#medicalData" role="tab">السجل الطبي</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="diseases-tab" data-toggle="tab" href="#chronicDiseases" role="tab">الأمراض المزمنة</a>
                    </li>
                </ul>

                <div class="tab-content" id="patientTabsContent">
                    {{-- 1. تبويبة البيانات الأساسية --}}
                    <div class="tab-pane fade show active" id="userData" role="tabpanel">
                        <div class="row mt-3">
                            <div class="col-md-6 form-group">
                                <label for="full_name">الاسم الكامل <span class="text-danger">*</span></label>
                                <input type="text" name="full_name" id="full_name" class="form-control @error('full_name') is-invalid @enderror" value="{{ old('full_name') }}" required>
                                @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="phone">رقم الهاتف <span class="text-danger">*</span></label>
                                <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" required>
                                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="email">البريد الإلكتروني (اختياري)</label>
                                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="national_id">الهوية الوطنية (اختياري)</label>
                                <input type="text" name="national_id" id="national_id" class="form-control @error('national_id') is-invalid @enderror" value="{{ old('national_id') }}">
                                @error('national_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="password">كلمة المرور <span class="text-danger">*</span></label>
                                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required>
                                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="password_confirmation">تأكيد كلمة المرور <span class="text-danger">*</span></label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" required>
                                @error('password_confirmation')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="status">حالة الحساب <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>نشط</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>غير نشط/محظور</option>
                                </select>
                                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    {{-- 2. تبويبة السجل الطبي (جميع الحقول مطلوبة عند الإنشاء) --}}
                    <div class="tab-pane fade" id="medicalData" role="tabpanel">
                        <div class="row mt-3">
                            <div class="col-md-4 form-group">
                                <label for="birth_date">تاريخ الميلاد <span class="text-danger">*</span></label>
                                <input type="date" name="birth_date" id="birth_date" class="form-control @error('birth_date') is-invalid @enderror" value="{{ old('birth_date') }}" required>
                                @error('birth_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="gender">الجنس <span class="text-danger">*</span></label>
                                <select name="gender" id="gender" class="form-control @error('gender') is-invalid @enderror" required>
                                    <option value="">-- اختر --</option>
                                    <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>ذكر</option>
                                    <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>أنثى</option>
                                </select>
                                @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="blood_type">فصيلة الدم <span class="text-danger">*</span></label>
                                <select name="blood_type" id="blood_type" class="form-control @error('blood_type') is-invalid @enderror" required>
                                    <option value="">-- اختر --</option>
                                    @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $type)
                                        <option value="{{ $type }}" {{ old('blood_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                                    @endforeach
                                </select>
                                @error('blood_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-12 form-group">
                                <label for="emergency_contact">رقم التواصل في حالة الطوارئ <span class="text-danger">*</span></label>
                                <input type="text" name="emergency_contact" id="emergency_contact" class="form-control @error('emergency_contact') is-invalid @enderror" value="{{ old('emergency_contact') }}" required>
                                @error('emergency_contact')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-12 form-group">
                                <label for="medical_history">تاريخ طبي سابق (عمليات، إصابات خطيرة) <span class="text-danger">*</span></label>
                                <textarea name="medical_history" id="medical_history" class="form-control @error('medical_history') is-invalid @enderror" rows="3" required>{{ old('medical_history') }}</textarea>
                                @error('medical_history')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-12 form-group">
                                <label for="allergies">حساسيات معروفة (أدوية، أطعمة، بيئة) <span class="text-danger">*</span></label>
                                <textarea name="allergies" id="allergies" class="form-control @error('allergies') is-invalid @enderror" rows="3" required>{{ old('allergies') }}</textarea>
                                @error('allergies')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-12 form-group">
                                <label for="current_medications">الأدوية الحالية التي يتناولها <span class="text-danger">*</span></label>
                                <textarea name="current_medications" id="current_medications" class="form-control @error('current_medications') is-invalid @enderror" rows="3" required>{{ old('current_medications') }}</textarea>
                                @error('current_medications')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-12 form-group">
                                <label for="notes">ملاحظات إضافية للملف الطبي (اختياري)</label>
                                <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes') }}</textarea>
                                @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    {{-- 3. تبويبة الأمراض المزمنة (هنا تم إضافة الحقل المخفي) --}}
                    <div class="tab-pane fade" id="chronicDiseases" role="tabpanel">
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <label>الأمراض المزمنة (اختياري):</label>
                                
                                {{-- 💡 الحل: الحقل المخفي يضمن إرسال قيمة فارغة إذا لم يتم اختيار أي مربع اختيار --}}
                                <input type="hidden" name="diseases_ids" value="">

                                <div class="row">
                                    @forelse($diseases as $disease)
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="diseases_ids[]" value="{{ $disease->id }}" id="disease_{{ $disease->id }}" 
                                                    {{ in_array($disease->id, old('diseases_ids', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="disease_{{ $disease->id }}">
                                                    {{ $disease->disease_name }}
                                                </label>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-md-12">
                                            <p class="text-danger">لا يوجد أمراض مزمنة معرفة في النظام بعد.</p>
                                        </div>
                                    @endforelse
                                </div>
                                @error('diseases_ids')<div class="text-danger mt-2">{{ $message }}</div>@enderror
                                @error('diseases_ids.*')<div class="text-danger mt-2">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">حفظ بيانات المريض</button>
                    <a href="{{ route('admin.patients.index') }}" class="btn btn-secondary">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
