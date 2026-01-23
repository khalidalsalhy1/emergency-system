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
                                {{-- تعريف مصفوفات المطابقة للتعريب والألوان --}}
                                @php
                                    $statusMapping = [
                                        'pending' => 'قيد الانتظار',
                                        'in_progress' => 'قيد التنفيذ',
                                        'completed' => 'مكتملة',
                                        'canceled' => 'ملغاة',
                                    ];
                                    $statusClasses = [
                                        'pending' => 'badge-danger',
                                        'in_progress' => 'badge-success',
                                        'completed' => 'badge-primary',
                                        'canceled' => 'badge-secondary',
                                    ];
                                @endphp

                                @forelse ($histories as $history)
                                    <tr>
                                        <td>{{ $history->id }}</td>
                                        <td>
                                            @if($history->emergencyRequest)
                                                {{-- تعديل لون رقم الطلب للأسود باستخدام text-dark --}}
                                                <a href="{{ route('admin.emergency_requests.show', $history->emergencyRequest->id) }}" class="text-dark font-weight-bold">
                                                    #{{ $history->emergencyRequest->id }}
                                                </a>
                                            @else
                                                <span class="text-muted">طلب محذوف</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{-- تطبيق الألوان والمسميات الموحدة --}}
                                            <span class="badge {{ $statusClasses[$history->status] ?? 'badge-info' }}">
                                                {{ $statusMapping[$history->status] ?? $history->status }}
                                            </span>
                                        </td>
                                        <td>
                                            {{-- الخط الطبيعي للاسم --}}
                                            {{ $history->changedBy->full_name ?? 'النظام/المريض' }}
                                        </td>
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
