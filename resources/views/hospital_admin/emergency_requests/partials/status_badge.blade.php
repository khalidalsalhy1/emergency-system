{{-- resources/views/hospital_admin/emergency_requests/partials/status_badge.blade.php --}}

@switch($status)
    @case('pending')
        @php $class = 'badge-warning'; @endphp {{-- أصفر للطلبات المعلقة/الجديدة --}}
        @break

    @case('accepted')
        @php $class = 'badge-primary'; @endphp {{-- أزرق للمقبولة --}}
        @break

    @case('dispatched')
        @php $class = 'badge-info'; @endphp {{-- سماوي للإرسال --}}
        @break
        
    @case('arrived')
        @php $class = 'badge-info'; @endphp {{-- سماوي للوصول --}}
        @break

    @case('completed')
        @php $class = 'badge-success'; @endphp {{-- أخضر للمكتملة --}}
        @break

    @case('canceled')
        @php $class = 'badge-secondary'; @endphp {{-- رمادي للملغاة --}}
        @break

    @default
        @php $class = 'badge-light'; @endphp
@endswitch

<span class="badge {{ $class }}">
    {{ ucfirst(str_replace('_', ' ', $status)) }}
</span>
