@php
    $statusConfig = [
        'pending' => [
            'class' => 'warning',
            'icon' => 'clock',
            'text' => 'Pending'
        ],
        'assigned' => [
            'class' => 'info',
            'icon' => 'user-check',
            'text' => 'Assigned'
        ],
        'in_progress' => [
            'class' => 'primary',
            'icon' => 'play-circle',
            'text' => 'In Progress'
        ],
        'complete' => [
            'class' => 'success',
            'icon' => 'check-circle',
            'text' => 'Complete'
        ],
        'cancelled' => [
            'class' => 'danger',
            'icon' => 'times-circle',
            'text' => 'Cancelled'
        ]
    ];
    
    $config = $statusConfig[(string) $status] ?? $statusConfig['pending'];
@endphp

<span class="badge bg-{{ $config['class'] }} status-badge-enhanced">
    <i class="fas fa-{{ $config['icon'] }} me-1"></i>
    {{ $config['text'] }}
</span>

<style>
.status-badge-enhanced {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    font-weight: 600;
    border-radius: 20px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: inline-flex;
    align-items: center;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
</style>
