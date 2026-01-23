{{-- resources/views/admin/emergency_requests/index.blade.php --}}

@extends('layouts.admin') 

@section('title', 'مراقبة طلبات الطوارئ')

@section('content_header')
    <h1><i class="fas fa-ambulance"></i> طلبات الطوارئ الواردة</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            {{-- عرض رسائل النجاح أو الأخطاء --}}
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            {{-- 1. لوحة الفلترة والبحث المتقدم --}}
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title">خيارات البحث والفلترة</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.emergency_requests.index') }}" method="GET">
                        <div class="row">
                            
                            {{-- حقل فلترة حسب الحالة --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="status">الحالة</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="">جميع الحالات</option>
                                        @foreach($allowedStatuses as $status)
                                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                                {{ match($status) {
                                                    'pending' => 'قيد الانتظار',
                                                    'in_progress' => 'قيد المعالجة',
                                                    'completed' => 'مكتملة',
                                                    'canceled' => 'ملغاة',
                                                    default => $status,
                                                } }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            {{-- حقل فلترة حسب المستشفى --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="hospital_id">المستشفى المسند إليه</label>
                                    <select name="hospital_id" id="hospital_id" class="form-control">
                                        <option value="">جميع المستشفيات</option>
                                        @foreach($hospitals as $hospital)
                                            <option value="{{ $hospital->id }}" {{ request('hospital_id') == $hospital->id ? 'selected' : '' }}>
                                                {{ $hospital->hospital_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            {{-- 🌟🌟 الإضافة الجديدة: حقل البحث عن المستخدم 🌟🌟 --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="user_search">البحث باسم أو هاتف المستخدم</label>
                                    <input type="text" 
                                           name="user_search" 
                                           id="user_search" 
                                           class="form-control" 
                                           value="{{ request('user_search') }}" 
                                           placeholder="اسم المريض أو رقم الهاتف">
                                </div>
                            </div>
                            {{-- 🌟🌟 نهاية الإضافة الجديدة 🌟🌟 --}}

                            {{-- زر الفلترة وإعادة التعيين --}}
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary mb-3"><i class="fas fa-filter"></i> فلترة/بحث</button>
                                <a href="{{ route('admin.emergency_requests.index') }}" class="btn btn-secondary mb-3 mr-2">إعادة تعيين</a>
                            </div>
                            
                        </div>
                    </form>
                </div>
            </div>

            {{-- 2. جدول عرض الطلبات --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">قائمة الطلبات ({{ $requests->total() }} طلب)</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>تاريخ الطلب</th>
                                    <th>نوع الأصابة</th>
                                    <th>المريض</th>
                                    <th>المستشفى المسند</th>
                                    <th>الحالة</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($requests as $request)
                                    <tr class="@include('admin.emergency_requests.partials.status_badge', ['status' => $request->status, 'row' => true])">

                                        <td>{{ $request->id }}</td>
                                        <td>{{ $request->created_at->format('Y-m-d H:i') }}</td>
                                        <td>
                                            {{-- عرض نوع الإصابة --}}
                                            {{ $request->injuryType->injury_name ?? 'غير محدد' }}
                                        </td>
                                        <td>{{ $request->user->full_name ?? 'مستخدم محذوف' }}</td>
                                        
                                        {{-- عمود المستشفى --}}
                                        <td>
                                            @if($request->hospital)
                                                {{ $request->hospital->hospital_name }}
                                            @else
                                                <span class="badge badge-warning">لم يتم الإسناد بعد</span>
                                            @endif
                                        </td>

                                        {{-- عمود الحالة بالعربية (تم إصلاح التكرار) --}}
<td>
    @include('admin.emergency_requests.partials.status_badge', ['status' => $request->status])
</td>                                        
                                        {{-- عمود الإجراءات --}}
                                        <td>
                                            <a href="{{ route('admin.emergency_requests.show', $request->id) }}" class="btn btn-xs btn-info" title="التفاصيل والتدخل">
                                                <i class="fas fa-eye"></i> تفاصيل
                                            </a>
                                            
                                            <button type="button" class="btn btn-xs btn-danger delete-btn" data-id="{{ $request->id }}" title="حذف دائم">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            
                                            <form id="delete-form-{{ $request->id }}" 
                                                  action="{{ route('admin.emergency_requests.destroy', $request->id) }}" 
                                                  method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">لا توجد طلبات طوارئ حالياً.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- 3. التصفح (Pagination) --}}
                <div class="card-footer clearfix">
                    {{ $requests->links() }}
                </div>
            </div>
        </div>
    </div>
@stop

{{-- 🚨 منطق الحذف JavaScript المصحح (يبقى كما هو) --}}

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
    <script>
        // هنا يمكنك إضافة كود JavaScript الخاص بتأكيد الحذف باستخدام SweetAlert2
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const requestId = this.getAttribute('data-id');
                    Swal.fire({
                        title: 'هل أنت متأكد؟',
                        text: "لن تتمكن من التراجع عن حذف طلب الطوارئ هذا!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'نعم، قم بالحذف!',
                        cancelButtonText: 'إلغاء'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('delete-form-' + requestId).submit();
                        }
                    });
                });
            });
        });
    </script>
@stop
