@extends('layouts.admin') 

@section('title', 'إدارة المرضى')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">قائمة المرضى</h1>
        <a href="{{ route('admin.patients.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> إضافة مريض جديد
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>الاسم الكامل</th>
                            <th>الهاتف</th>
                            <th>البريد الإلكتروني</th>
                            <th>الهوية الوطنية</th>
                            <th>الحالة</th>
                            <th>فصيلة الدم</th>
                            <th>تاريخ التسجيل</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($patients as $patient)
                        <tr>
                            <td>{{ $patient->full_name }}</td>
                            <td>{{ $patient->phone }}</td>
                            <td>{{ $patient->email ?? 'N/A' }}</td>
                            <td>{{ $patient->national_id ?? 'N/A' }}</td>
                            <td>
                                <span class="badge badge-{{ $patient->status === 'active' ? 'success' : 'danger' }}">
                                    {{ $patient->status === 'active' ? 'نشط' : 'غير نشط' }}
                                </span>
                            </td>
                            <td>{{ $patient->medicalRecord->blood_type ?? 'N/A' }}</td>
                            <td>{{ $patient->created_at->format('Y-m-d') }}</td>
                            <td>
                                {{-- تم تغيير الكلاس هنا من btn-primary إلى btn-warning فقط --}}
                                <a href="{{ route('admin.patients.edit', $patient) }}"  class="btn btn-xs btn-warning btn-sm" >
                                    <i class="fas fa-edit "></i>
                                </a>

                                {{-- نموذج الحذف (أحمر/تحذيري) --}}
                                <form action="{{ route('admin.patients.destroy', $patient) }}" method="POST" style="display:inline;" onsubmit="return confirm('هل أنت متأكد من حذف هذا المريض؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm mx-1 shadow-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">لا يوجد مرضى مسجلين حالياً.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center">
                {{ $patients->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
