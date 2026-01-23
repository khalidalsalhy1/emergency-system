@extends('layouts.hospital') {{-- افترض أن هذا هو التخطيط الأساسي لديك --}}

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>تغيير كلمة المرور</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('hospital.dashboard') }}">الرئيسية</a></li>
                        <li class="breadcrumb-item active">تغيير كلمة المرور</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-warning">
                    <div class="card-header d-flex justify-content-between align-items-center ">
                        <h3 class="card-title">تحديث كلمة مرور مسؤول المستشفى</h3>
                    </div>
                    
                    <form method="POST" action="{{ route('hospital.profile.update_password') }}">
                        @csrf
                        
                        <div class="card-body">
                            
                            {{-- رسائل النجاح (Success Message) --}}
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            {{-- حقل كلمة المرور الحالية --}}
                            <div class="form-group">
                                <label for="current_password">كلمة المرور الحالية</label>
                                <input id="current_password" type="password" 
                                       class="form-control @error('current_password') is-invalid @enderror" 
                                       name="current_password" required autocomplete="current-password">

                                @error('current_password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <hr> {{-- فاصل بين القديمة والجديدة --}}

                            {{-- حقل كلمة المرور الجديدة --}}
                            <div class="form-group">
                                <label for="password">كلمة المرور الجديدة</label>
                                <input id="password" type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       name="password" required autocomplete="new-password">
                                <small class="form-text text-muted">يجب أن تكون 8 أحرف على الأقل.</small>

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            {{-- حقل تأكيد كلمة المرور الجديدة --}}
                            <div class="form-group">
                                <label for="password-confirm">تأكيد كلمة المرور الجديدة</label>
                                <input id="password-confirm" type="password" 
                                       class="form-control" 
                                       name="password_confirmation" required autocomplete="new-password">
                            </div>

                        </div>
                        
                        <div class="card-footer">
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save"></i> تحديث كلمة المرور
                            </button>
                            {{-- زر إلغاء اختياري --}}
                            <a href="{{ route('hospital.dashboard') }}" class="btn btn-default float-right">إلغاء</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
