{{-- resources/views/admin/request_history/index.blade.php --}}

@extends('layouts.admin')

@section('title', 'سجل حالة الطلبات')

@section('content_header')
    <h1><i class="fas fa-history"></i> سجل حالة الطلبات</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">جميع التغييرات في حالات طلبات الطوارئ</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>رقم السجل</th>
                                    <th>رقم الطلب</th>
                                    <th>الحالة الجديدة</th>
                                    <th>تم التغيير بواسطة</th>
                                    <th>تاريخ التغيير</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($histories as $history)
                                    <tr>
                                        <td>{{ $history->id }}</td>
                                        <td>
                                            <a href="{{ route('admin.emergency_requests.show', $history->emergencyRequest->id) }}">
                                                #{{ $history->emergencyRequest->id }}
                                            </a>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $history->isCompleted() ? 'success' : ($history->isPending() ? 'warning' : 'info') }}">
                                                {{ $history->status }}
                                            </span>
                                        </td>
                                        <td>{{ $history->changedBy->full_name ?? 'غير محدد' }}</td>
                                        {{-- 🎯 التصحيح النهائي: استخدام changed_at وفي حال كان null نعتمد على created_at --}}
                                        <td>
                                            {{ ($history->changed_at ?? $history->created_at) ? ($history->changed_at ?? $history->created_at)->format('Y-m-d H:i') : 'غير مسجل' }}
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.request_history.show', $history->id) }}" class="btn btn-xs btn-info" title="عرض التفاصيل">
                                                <i class="fas fa-eye"></i> تفاصيل
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">لا يوجد سجل لتغيير حالات الطلبات حتى الآن.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer clearfix">
                    {{ $histories->links() }}
                </div>
            </div>
        </div>
    </div>
@stop
