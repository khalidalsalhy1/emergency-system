{{-- resources/views/admin/diseases/index.blade.php --}}

@extends('layouts.admin')

@section('title', 'إدارة الأمراض المزمنة')

@section('content_header')
    <h1><i class="fas fa-virus"></i> إدارة الأمراض المزمنة</h1>
@stop

@section('content')
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
                    <h3 class="card-title">قائمة الأمراض المزمنة</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.diseases.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> إضافة مرض جديد
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>اسم المرض</th>
                                    <th>الوصف</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($diseases as $disease)
                                    <tr>
                                        <td>{{ $disease->id }}</td>
                                        <td>{{ $disease->disease_name }}</td>
                                        <td>{{ Str::limit($disease->description, 70) ?? 'لا يوجد وصف' }}</td>
                                        <td>
                                            <a href="{{ route('admin.diseases.edit', $disease->id) }}" class="btn btn-xs btn-warning" title="تعديل">
                                                <i class="fas fa-edit"></i> تعديل
                                            </a>
                                            
                                            <button type="button" class="btn btn-xs btn-danger delete-btn" data-id="{{ $disease->id }}" title="حذف دائم">
                                                <i class="fas fa-trash"></i> حذف
                                            </button>
                                            
                                            <form id="delete-form-{{ $disease->id }}" 
                                                  action="{{ route('admin.diseases.destroy', $disease->id) }}" 
                                                  method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">لا توجد أمراض مزمنة مسجلة حالياً.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer clearfix">
                    {{ $diseases->links() }}
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // منطق حذف SweetAlert2
        $(document).ready(function() {
            $('.delete-btn').on('click', function(e) {
                e.preventDefault();
                var itemId = $(this).data('id');
                var formId = '#delete-form-' + itemId;
                
                Swal.fire({
                    title: 'هل أنت متأكد؟',
                    text: "لن تتمكن من التراجع عن حذف هذا المرض!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'نعم، قم بالحذف!',
                    cancelButtonText: 'إلغاء'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $(formId).submit();
                    }
                });
            });
        });
    </script>
@stop
