@extends('layouts.admin') 

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">إدارة مسؤولي المستشفيات</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-left">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item active">مسؤولو المستشفيات</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">قائمة مسؤولي المستشفيات</h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.hospital_admins.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> إضافة مسؤول جديد
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($hospitalAdmins->isEmpty())
                            <div class="alert alert-info text-center">
                                لا توجد حسابات لمسؤولي المستشفيات مسجلة حالياً.
                            </div>
                        @else
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">#</th>
                                        <th>الاسم الكامل</th>
                                        <th>المستشفى المرتبط</th>
                                        <th>رقم الهاتف</th>
                                        <th>البريد الإلكتروني</th>
                                        <th>الحالة</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($hospitalAdmins as $admin)
                                    <tr>
                                        <td>{{ $loop->iteration + ($hospitalAdmins->perPage() * ($hospitalAdmins->currentPage() - 1)) }}.</td>
                                        <td>{{ $admin->full_name }}</td>
                                        <td>{{ $admin->hospital->hospital_name ?? 'غير مرتبط' }}</td>
                                        <td>{{ $admin->phone }}</td>
                                        <td>{{ $admin->email ?? '-' }}</td>
                                        <td>
                                            @if($admin->status === 'active')
                                                <span class="badge badge-success">نشط</span>
                                            @else
                                                <span class="badge badge-danger">غير نشط</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.hospital_admins.edit', $admin->id) }}" class="btn btn-warning btn-sm" title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger btn-sm delete-admin" data-id="{{ $admin->id }}" title="حذف">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                    @if($hospitalAdmins->hasPages())
                        <div class="card-footer clearfix">
                            {{ $hospitalAdmins->links('pagination::bootstrap-4') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    {{-- **** نموذج الحذف المخفي --}}
    <form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
</section>
@endsection

@section('scripts')
<script>
    // كود JavaScript لتفعيل زر الحذف
    $(document).ready(function() {
        const deleteForm = document.getElementById('deleteForm');
        
        $('.delete-admin').on('click', function(e) {
            e.preventDefault(); 

            const adminId = $(this).data('id'); 
            
            if (confirm('هل أنت متأكد من أنك تريد حذف هذا المسؤول؟ سيتم حذفه حذفاً ناعماً. لا يمكن التراجع عن هذه العملية.')) {
                // بناء مسار الحذف الديناميكي
                const deleteUrl = "{{ route('admin.hospital_admins.destroy', ['hospital_admin' => ':id']) }}";
                deleteForm.action = deleteUrl.replace(':id', adminId);
                
                // إرسال طلب DELETE
                deleteForm.submit();
            }
        });
    });
</script>
@endsection
