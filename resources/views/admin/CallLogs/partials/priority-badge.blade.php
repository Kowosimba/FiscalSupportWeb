@php
    $priorityClasses = [
        'low' => 'badge-success',
        'medium' => 'badge-warning',
        'high' => 'badge-danger',
        'urgent' => 'badge-dark'
    ];
    
    $priorityIcons = [
        'low' => 'fas fa-arrow-down',
        'medium' => 'fas fa-minus',
        'high' => 'fas fa-arrow-up',
        'urgent' => 'fas fa-exclamation-triangle'
    ];
@endphp

<span class="badge {{ $priorityClasses[$priority ?? ''] ?? 'badge-secondary' }}">
    <i class="{{ $priorityIcons[$priority ?? ''] ?? 'fas fa-question' }}"></i>
    {{ ucfirst($priority ?? 'unknown') }}
</span>
