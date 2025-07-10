@php
    $typeClasses = [
        'normal' => 'type-normal',
        'maintenance' => 'type-maintenance',
        'repair' => 'type-repair',
        'installation' => 'type-installation',
        'consultation' => 'type-consultation',
        'emergency' => 'type-emergency'
    ];
    
    $typeIcons = [
        'normal' => 'fa fa-clipboard',
        'maintenance' => 'fa fa-wrench',
        'repair' => 'fa fa-tools',
        'installation' => 'fa fa-download',
        'consultation' => 'fa fa-comments',
        'emergency' => 'fa fa-exclamation-triangle'
    ];
@endphp

<span class="type-badge {{ $typeClasses[$type ?? ''] ?? 'type-unknown' }}">
    <i class="{{ $typeIcons[$type ?? ''] ?? 'fa fa-question' }} me-1"></i>
    {{ ucfirst($type ?? 'unknown') }}
</span>

<style>
    .type-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border: 1px solid;
    }

    .type-normal {
        background: var(--ultra-light-green);
        color: var(--primary-green);
        border-color: var(--secondary-green);
    }

    .type-maintenance {
        background: #EFF6FF;
        color: #2563EB;
        border-color: #BFDBFE;
    }

    .type-repair {
        background: #FFFBEB;
        color: #D97706;
        border-color: #FDE68A;
    }

    .type-installation {
        background: #F0F9FF;
        color: #0284C7;
        border-color: #BAE6FD;
    }

    .type-consultation {
        background: #F5F3FF;
        color: #7C3AED;
        border-color: #C4B5FD;
    }

    .type-emergency {
        background: #FEF2F2;
        color: #DC2626;
        border-color: #FECACA;
    }

    .type-unknown {
        background: #F3F4F6;
        color: #6B7280;
        border-color: #D1D5DB;
    }

    .type-badge i {
        font-size: 0.7rem;
    }
</style>
