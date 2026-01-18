{{-- resources/views/hospital_admin/emergency_requests/index.blade.php --}}

@extends('layouts.hospital') {{-- 🚨 التعديل 1: استخدام Layout المستشفى --}}

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
            @if (session('warning'))
                <div class="alert alert-warning">{{ session('warning') }}</div>
            @endif

            {{-- 1. لوحة الفلترة والبحث المتقدم --}}
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title">خيارات البحث والفلترة</h3>
                </div>
                <div class="card-body">
                    {{-- 🚨 التعديل 2: استخدام مسار المستشفى 'hospital.requests.index' --}}
                    <form action="{{ route('hospital.requests.index') }}" method="GET">
                        <div class="row">
                            {{-- حقل فلترة حسب الحالة --}}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="status">الحالة</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="">جميع الحالات</option>
                                        {{-- $statuses تم تمريرها من الكنترولر --}}
                                        @foreach($statuses as $status) 
                                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                                {{ match($status) {
                                                    'pending' => 'قيد الانتظار',
                                                    'accepted' => 'مقبولة',
                                                    'dispatched' => 'أُرسلت',
                                                    'arrived' => 'وصلت',
                                                    'completed' => 'مكتملة',
                                                    'canceled' => 'ملغاة',
                                                    default => $status,
                                                } }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            {{-- 🚨 التعديل 3: إزالة فلترة المستشفيات (غير ضرورية هنا) --}}
                            
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary mb-3"><i class="fas fa-filter"></i> فلترة</button>
                                {{-- 🚨 التعديل 4: استخدام مسار المستشفى 'hospital.requests.index' --}}
                                <a href="{{ route('hospital.requests.index') }}" class="btn btn-secondary mb-3 mr-2">إعادة تعيين</a>
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
                                    <th>نوع الطوارئ</th>
                                    <th>المريض</th>
                                    <th>نوع الطلب</th> {{-- عرض نوع الطلب (DISPATCH/NOTIFY) --}}
                                    <th>الحالة</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($requests as $request)
                                    @php
                                        // يمكن تعريف دوال مساعدة لتعيين لون الصف هنا بناءً على الحالة
                                        $rowClass = ''; 
                                        if ($request->status === 'pending') {
                                            $rowClass = 'table-warning';
                                        } elseif ($request->status === 'canceled') {
                                            $rowClass = 'table-danger text-muted';
                                        } 
                                    @endphp
                                    <tr class="{{ $rowClass }}">
                                        <td>{{ $request->id }}</td>
                                        <td>{{ $request->created_at->format('Y-m-d H:i') }}</td>
                                        <td>
                                            {{-- يجب التأكد أن العلاقة injuryType محملة في الكنترولر --}}
                                            {{ $request->injuryType->name ?? 'غير محدد' }} 
                                        </td>
                                        <td>{{ $request->patient->full_name ?? 'مستخدم محذوف' }}</td>
                                        <td>
                                            @if($request->request_type === 'DISPATCH')
                                                <span class="badge badge-danger">إرسال فريق</span>
                                            @else
                                                <span class="badge badge-info">إشعار فقط</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{-- 🚨 التعديل 5: تضمين الـ Partial الخاص بالمستشفى --}}
                                            @include('hospital_admin.emergency_requests.partials.status_badge', ['status' => $request->status])
                                        </td>
                                        <td>
                                            {{-- 🚨 التعديل 6: استخدام مسار المستشفى 'hospital.requests.show' --}}
                                            <a href="{{ route('hospital.requests.show', $request->id) }}" class="btn btn-xs btn-info" title="التفاصيل والتدخل">
                                                <i class="fas fa-eye"></i> تفاصيل
                                            </a>
                                            
                                            {{-- 🚨 تم حذف زر ونموذج الحذف الطارئ، فهو غير منطقي لمسؤول المستشفى في هذا السياق --}}
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

{{-- لا حاجة لأي كود JS خاص بالحذف هنا --}}
