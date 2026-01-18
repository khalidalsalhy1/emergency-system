@extends('layouts.admin') 

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">إدارة أنواع الإصابات</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-left">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item active">أنواع الإصابات</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                {{-- **** رسائل التنبيه (نجاح/خطأ) **** --}}
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">قائمة أنواع الإصابات</h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.injury_types.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> إضافة نوع جديد
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($injuryTypes->isEmpty())
                            <div class="alert alert-info text-center">
                                لا توجد أنواع إصابات مسجلة حالياً.
                            </div>
                        @else
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">#</th>
                                        <th>اسم الإصابة</th>
                                        <th>الوصف</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($injuryTypes as $injuryType)
                                    <tr>
                                        <td>{{ $loop->iteration + ($injuryTypes->perPage() * ($injuryTypes->currentPage() - 1)) }}.</td>
                                        <td>{{ $injuryType->injury_name }}</td>
                                        <td>{{ Str::limit($injuryType->description, 50) ?? '-' }}</td>
                                        <td>
                                            {{-- رابط التعديل --}}
                                            <a href="{{ route('admin.injury_types.edit', $injuryType->id) }}" class="btn btn-warning btn-sm" title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            {{-- زر الحذف --}}
                                            <button type="button" class="btn btn-danger btn-sm delete-injury-type" data-id="{{ $injuryType->id }}" title="حذف">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                    {{-- مكان التصفح (Pagination) --}}
                    @if($injuryTypes->hasPages())
                        <div class="card-footer clearfix">
                            {{ $injuryTypes->links('pagination::bootstrap-4') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    {{-- **** نموذج الحذف المخفي (لإرسال طلب DELETE) **** --}}
    <form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
</section>
@endsection

@section('scripts')
<script>
    // كود JavaScript لتفعيل زر الحذف (باستخدام jQuery)
    $(document).ready(function() {
        
        const deleteForm = document.getElementById('deleteForm');
        
        $('.delete-injury-type').on('click', function(e) {
            e.preventDefault(); 

            const injuryTypeId = $(this).data('id'); 
            
            if (confirm('هل أنت متأكد من أنك تريد حذف هذا النوع من الإصابات؟ قد يؤثر على بيانات الطوارئ المرتبطة.')) {
                // بناء مسار الحذف الديناميكي
                const deleteUrl = "{{ route('admin.injury_types.destroy', ['injuryType' => ':id']) }}";
                deleteForm.action = deleteUrl.replace(':id', injuryTypeId);
                
                // إرسال طلب DELETE
                deleteForm.submit();
            }
        });
    });
</script>
@endsection
