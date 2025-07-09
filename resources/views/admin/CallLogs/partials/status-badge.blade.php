@php
    $statusClasses = [
        'pending' => 'badge-warning',
        'assigned' => 'badge-info',
        'in_progress' => 'badge-primary',
        'completed' => 'badge-success',
        'cancelled' => 'badge-danger'
    ];
    
    $statusIcons = [
        'pending' => 'fas fa-clock',
        'assigned' => 'fas fa-user-check',
        'in_progress' => 'fas fa-tools',
        'completed' => 'fas fa-check-circle',
        'cancelled' => 'fas fa-times-circle'
    ];
@endphp

<span class="badge {{ $statusClasses[$status] ?? 'badge-secondary' }}">
    <i class="{{ $statusIcons[$status] ?? 'fas fa-question' }}"></i>
    {{ ucfirst(str_replace('_', ' ', $status)) }}
</span>
