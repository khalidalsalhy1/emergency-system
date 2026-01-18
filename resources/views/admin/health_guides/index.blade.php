{{-- resources/views/admin/health_guides/index.blade.php --}}

@extends('layouts.admin')

@section('title', 'إدارة الإرشادات الصحية')

@section('content_header')
    <h1><i class="fas fa-book-medical"></i> إدارة الإرشادات الصحية</h1>
@stop

@section('content')

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">قائمة الإرشادات الصحية</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.health_guides.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> إضافة إرشاد جديد
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>العنوان</th>
                                    <th>التصنيف</th>
                                    {{-- 🛑 تم حذف عمود "صورة" --}}
                                    <th>تاريخ الإنشاء</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($guides as $guide)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $guide->title }}</td>
                                        <td><span class="badge badge-secondary">{{ $guide->category ?? 'عام' }}</span></td>
                                        
                                        {{-- 🛑 تم حذف الخلية الخاصة بالصورة هنا بالكامل --}}
                                        
                                        <td>{{ $guide->created_at->format('Y-m-d') }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('admin.health_guides.show', $guide->id) }}" class="btn btn-info" title="عرض التفاصيل">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.health_guides.edit', $guide->id) }}" class="btn btn-warning" title="تعديل">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.health_guides.destroy', $guide->id) }}" method="POST" style="display:inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" onclick="return confirm('هل أنت متأكد من حذف هذا الإرشاد؟')" title="حذف">
                                                        <i class="fas fa-trash"></i> 
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        {{-- تم تغيير عدد الأعمدة إلى 5 بعد حذف عمود الصورة --}}
                                        <td colspan="5" class="text-center">لا يوجد إرشادات صحية مسجلة حاليًا.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer clearfix">
                    {{ $guides->links() }}
                </div>
            </div>
        </div>
    </div>
@stop
