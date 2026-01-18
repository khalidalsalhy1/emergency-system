{{-- resources/views/admin/locations/index.blade.php --}}

@extends('layouts.admin')

@section('title', 'إدارة المواقع الجغرافية')

@section('content_header')
    <h1><i class="fas fa-map-marked-alt"></i> إدارة المواقع الجغرافية</h1>
@stop

@section('content')

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="icon fas fa-check"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">قائمة بجميع المواقع المسجلة</h3>
                    {{-- تم حذف زر "إضافة موقع جديد" من هنا بناءً على طلبك --}}
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>العنوان التقديري</th>
                                    <th>مرتبط بمستشفى</th>
                                    <th>مسجل للمريض</th>
                                    <th class="text-center">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($locations as $location)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $location->address ?? 'لا يوجد عنوان' }}</td>
                                        <td>
                                            @if($location->hospital)
                                                <span class="badge badge-info">{{ $location->hospital->hospital_name }}</span>
                                            @else
                                                <span class="text-muted small">لا يوجد</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($location->user)
                                                <span class="text-dark">{{ $location->user->full_name ?? $location->user->name }}</span>
                                            @else
                                                <span class="text-muted small">لا يوجد</span>
                                            @endif
                                        </td>
                                        
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm">
                                                {{-- زر العرض متاح دائماً --}}
                                                <a href="{{ route('admin.locations.show', $location->id) }}" class="btn btn-info" title="عرض التفاصيل">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                
                                                {{-- 
                                                    يظهر زر التعديل والحذف فقط إذا كان الموقع:
                                                    1. لا يخص مريض (user_id is null) 
                                                    2. ولا يوجد له طلبات طوارئ مرتبطة 
                                                --}}
                                                @if(!$location->user_id && $location->emergencyRequests->isEmpty())
                                                    {{-- زر التعديل --}}
                                                    <a href="{{ route('admin.locations.edit', $location->id) }}" class="btn btn-warning" title="تعديل">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    
                                                    {{-- زر الحذف --}}
                                                    <form action="{{ route('admin.locations.destroy', $location->id) }}" method="POST" style="display:inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger" onclick="return confirm('هل أنت متأكد من حذف هذا الموقع؟')" title="حذف">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">لا يوجد مواقع جغرافية مسجلة حاليًا.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer clearfix">
                    {{ $locations->links() }}
                </div>
            </div>
        </div>
    </div>
@stop
