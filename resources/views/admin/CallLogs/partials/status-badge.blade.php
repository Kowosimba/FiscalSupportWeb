@php
    $statusClasses = [
        'pending' => 'status-pending',
        'assigned' => 'status-assigned',
        'in_progress' => 'status-in-progress',
        'complete' => 'status-complete',
        'cancelled' => 'status-cancelled'
    ];
    
    $statusIcons = [
        'pending' => 'fa fa-clock',
        'assigned' => 'fa fa-user-check',
        'in_progress' => 'fa fa-cog fa-spin',
        'complete' => 'fa fa-check-circle',
        'cancelled' => 'fa fa-times-circle'
    ];
@endphp

@php
    $safeStatus = is_string($status ?? null) ? $status : 'unknown';
@endphp
<span class="status-badge {{ $statusClasses[$safeStatus] ?? 'status-unknown' }}">
    <i class="{{ $statusIcons[$safeStatus] ?? 'fa fa-question' }} me-1"></i>
    {{ ucfirst(str_replace('_', ' ', $safeStatus)) }}
</span>

<style>
    .status-badge {
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

    .status-pending {
        background: #FFFBEB;
        color: #D97706;
        border-color: #FDE68A;
    }

    .status-assigned {
        background: #EFF6FF;
        color: #2563EB;
        border-color: #BFDBFE;
    }

    .status-in-progress {
        background: #F0F9FF;
        color: #0284C7;
        border-color: #BAE6FD;
    }

    .status-complete {
        background: var(--ultra-light-green);
        color: var(--primary-green);
        border-color: var(--secondary-green);
    }

    .status-cancelled {
        background: #FEF2F2;
        color: #DC2626;
        border-color: #FECACA;
    }

    .status-unknown {
        background: #F3F4F6;
        color: #6B7280;
        border-color: #D1D5DB;
    }

    .status-badge i {
        font-size: 0.7rem;
    }

    .fa-spin {
        animation: fa-spin 2s infinite linear;
    }

    @keyframes fa-spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(359deg); }
    }
</style>
