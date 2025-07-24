@php
    $typeConfig = [
        'normal' => [
            'class' => 'secondary',
            'icon' => 'clipboard-list',
            'text' => 'Normal'
        ],
        'emergency' => [
            'class' => 'danger',
            'icon' => 'exclamation-triangle',
            'text' => 'Emergency'
        ],
        'maintenance' => [
            'class' => 'warning',
            'icon' => 'tools',
            'text' => 'Maintenance'
        ],
        'repair' => [
            'class' => 'info',
            'icon' => 'wrench',
            'text' => 'Repair'
        ],
        'installation' => [
            'class' => 'success',
            'icon' => 'cogs',
            'text' => 'Installation'
        ],
        'consultation' => [
            'class' => 'primary',
            'icon' => 'comments',
            'text' => 'Consultation'
        ]
    ];
    
    $type = isset($type) && is_string($type) ? $type : 'normal';
    $config = $typeConfig[$type] ?? $typeConfig['normal'];
@endphp

<span class="badge bg-{{ $config['class'] }} type-badge-enhanced">
    <i class="fas fa-{{ $config['icon'] }} me-1"></i>
    {{ $config['text'] }}
</span>

<style>
.type-badge-enhanced {
    padding: 0.4rem 0.8rem;
    font-size: 0.8rem;
    font-weight: 500;
    border-radius: 15px;
    display: inline-flex;
    align-items: center;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}
</style>
