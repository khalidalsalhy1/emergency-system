@extends('layouts.hospital') {{-- 🌟 1. التعديل الأول: استخدام Layout المستشفى --}}

@section('title', 'سجل إشعارات المستشفى')

@section('content')
    <div class="row">
        <div class="col-12">
            
            {{-- عرض رسائل النجاح أو التنبيه --}}
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('warning'))
                <div class="alert alert-warning">{{ session('warning') }}</div>
            @endif
            @if (session('info'))
                <div class="alert alert-info">{{ session('info') }}</div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">إشعاراتي الواردة ({{ $notifications->total() ?? 0 }})</h3>
                    <div class="card-tools">
                        {{-- 🌟 2. التعديل الثاني: تحديث اسم المسار إلى hospital.notifications.markAllAsRead --}}
                        <form action="{{ route('hospital.notifications.markAllAsRead') }}" method="POST" style="display: inline;">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-sm btn-info" onclick="return confirm('هل أنت متأكد من وضع علامة مقروء على جميع الإشعارات غير المقروءة؟')">
                                <i class="fas fa-check-double"></i> وضع الكل كمقروء
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th style="width: 20%">العنوان</th>
                                <th>نص الرسالة</th>
                                <th style="width: 10%">الحالة</th>
                                <th style="width: 15%">تاريخ الإرسال</th>
                                <th style="width: 10%">الإجراء</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($notifications as $notification)
                                {{-- تمييز الصفوف غير المقروءة بلون مختلف --}}
                                <tr @if(!$notification->is_read) class="table-warning font-weight-bold" @endif>
                                    <td>{{ $notifications->firstItem() + $loop->index }}</td>
                                    <td>{{ $notification->title }}</td>
                                    <td>
                                        @if($notification->type === 'emergency' && is_string($notification->message))
                                            {{-- رسالة بلاغ الطوارئ الجديد تكون JSON، لذا نعرض رسالة عامة --}}
                                            إشعار بلاغ طوارئ جديد ({{ \Illuminate\Support\Str::limit($notification->message, 80) }})
                                        @else
                                            {{ \Illuminate\Support\Str::limit($notification->message, 120) }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($notification->is_read)
                                            <span class="badge badge-success">مقروء</span>
                                        @else
                                            <span class="badge badge-danger">غير مقروء</span>
                                        @endif
                                    </td>
                                    <td>{{ $notification->created_at->diffForHumans() }}</td>
                                    <td>
                                        {{-- 🌟 3. التعديل الثالث: تحديث اسم المسار إلى hospital.notifications.update --}}
                                        <form action="{{ route('hospital.notifications.update', $notification->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" 
                                                    class="btn btn-sm @if($notification->is_read) btn-secondary disabled @else btn-primary @endif" 
                                                    @if($notification->is_read) disabled @endif
                                                    title="عرض وتأكيد الإشعار">
                                                <i class="fas fa-eye"></i> عرض
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">لا توجد إشعارات لعرضها حالياً.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    {{ $notifications->links() }}
                </div>
            </div>
            </div>
    </div>
@endsection
