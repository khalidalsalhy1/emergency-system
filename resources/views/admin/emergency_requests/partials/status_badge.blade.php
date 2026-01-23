{{-- resources/views/admin/emergency_requests/partials/status_badge.blade.php --}}
@php
    // مصفوفة ترجمة الحالات للعربية
    $statusMapping = [
        'pending' => 'قيد الانتظار',
        'in_progress' => 'قيد التنفيذ',
        'completed' => 'مكتملة',
        'canceled' => 'ملغاة',
    ];

    // تحديد لون البادج ولون الصف (الظل الخفيف) بناءً على الحالة
    switch($status) {
        case 'pending':
            $badgeClass = 'badge-danger';    // البادج أحمر
            $rowClass = 'table-danger';      // الصف ظل أحمر
            break;
        case 'in_progress':
            $badgeClass = 'badge-warning';   // البادج أصفر
            $rowClass = 'table-warning';     // الصف ظل أصفر
            break;
        case 'completed':
            $badgeClass = 'badge-success';   // البادج أخضر
            $rowClass = 'table-success';     // الصف ظل أخضر
            break;
        case 'canceled':
            $badgeClass = 'badge-secondary'; // البادج رمادي
            $rowClass = 'table-secondary';   // الصف ظل رمادي
            break;
        default:
            $badgeClass = 'badge-info';
            $rowClass = '';
            break;
    }

    // نص الحالة بالعربية
    $displayStatus = $statusMapping[$status] ?? ucfirst(str_replace('_', ' ', $status));
@endphp

{{-- 
    ملاحظة: لكي يعمل تلوين الصف بالكامل، يجب استدعاء الـ partial في ملف الـ index 
    داخل وسم الـ <tr> باستخدام @include مع تمرير متغير 'row' => true
--}}

@if(isset($row) && $row === true)
    {{-- هذا الكلاس سيتم استخدامه داخل وسم الـ <tr> في ملف الـ index --}}
    {{ $rowClass }}
@else
    {{-- عرض البادج الملون فقط --}}
    <span class="badge {{ $badgeClass }} shadow-sm px-2 py-1">
        {{ $displayStatus }}
    </span>
@endif
