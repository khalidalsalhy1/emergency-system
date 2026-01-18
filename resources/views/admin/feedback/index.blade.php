{{-- resources/views/admin/ratings/index.blade.php --}}

@extends('layouts.admin')

@section('title', 'إدارة التقييمات')

@section('content_header')
    <h1><i class="fas fa-star"></i> إدارة التقييمات</h1>
@stop

@section('content')

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">جميع التقييمات والملاحظات من المستخدمين</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>قيمة التقييم</th>
                                    <th>المستخدم</th>
                                    <th>المستشفى المُقيّمة</th>
                                    <th>طلب الطوارئ</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($feedbacks as $feedback)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <span class="badge badge-info">
                                                {{ $feedback->rating }} / 5
                                            </span>
                                        </td>
                                        <td>{{ $feedback->user->full_name ?? 'مستخدم محذوف' }}</td>
                                        <td>{{ $feedback->hospital->hospital_name ?? 'غير محدد' }}</td>
                                        <td>
                                            <a href="{{ route('admin.emergency_requests.show', $feedback->emergencyRequest->id) }}">
                                                #{{ $feedback->emergencyRequest->id }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.ratings.show', $feedback->id) }}" class="btn btn-xs btn-info" title="عرض التفاصيل">
                                                <i class="fas fa-eye"></i> تفاصيل
                                            </a>
                                            <form action="{{ route('admin.ratings.destroy', $feedback->id) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-xs btn-danger" onclick="return confirm('هل أنت متأكد من حذف هذا التقييم؟')" title="حذف">
                                                    <i class="fas fa-trash"></i> حذف
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">لا يوجد تقييمات أو ملاحظات حتى الآن.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer clearfix">
                    {{ $feedbacks->links() }}
                </div>
            </div>
        </div>
    </div>
@stop
