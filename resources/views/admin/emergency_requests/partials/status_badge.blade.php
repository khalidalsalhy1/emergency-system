{{-- resources/views/admin/emergency_requests/partials/status_badge.blade.php --}}

@switch($status)
    @case('pending')
        @php $class = 'badge-danger'; @endphp {{-- أحمر للطلبات المعلقة --}}
        @break

    @case('in_progress')
        @php $class = 'badge-warning'; @endphp {{-- أصفر للطلبات قيد المعالجة --}}
        @break

    @case('completed')
        @php $class = 'badge-success'; @endphp {{-- أخضر للطلبات المكتملة --}}
        @break

    @case('canceled')
        @php $class = 'badge-secondary'; @endphp {{-- رمادي للطلبات الملغاة --}}
        @break

    @default
        @php $class = 'badge-info'; @endphp
@endswitch

<span class="badge {{ $class }}">
    {{ ucfirst(str_replace('_', ' ', $status)) }}
</span>
