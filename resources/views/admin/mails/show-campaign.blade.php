@extends('layouts.app')

@section('title', 'Newsletter Campaign - ' . $campaign->subject)

@section('content')
<div class="dashboard-container">
    {{-- Compact Header --}}
    <div class="dashboard-header mb-2">
        <div class="header-content">
            <h1 class="dashboard-title">
                <i class="fas fa-bullhorn me-2"></i>
                Newsletter Campaign
            </h1>
            <div class="header-meta">
                <small class="text-muted">{{ $campaign->subject }}</small>
            </div>
        </div>
        <div class="header-actions">
            <button onclick="window.location.href='{{ route('admin.newsletters.index') }}'" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>
                Back to Campaigns
            </button>
        </div>
    </div>

    {{-- Success & Error Alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-2">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-2">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Campaign Details --}}
    <div class="content-card">
        <div class="content-card-header">
            <div class="header-content">
                <h4 class="card-title">
                    <i class="fas fa-info-circle me-2"></i>
                    Campaign Details
                </h4>
                <p class="card-subtitle mb-0">
                    View and manage newsletter campaign
                </p>
            </div>
            <div class="header-actions">
                @if($campaign->sent_at)
                    <span class="status-badge status-sent">
                        <i class="fas fa-check-circle me-1"></i>
                        Sent
                    </span>
                @elseif($campaign->scheduled_at && $campaign->scheduled_at->isFuture())
                    <span class="status-badge status-scheduled">
                        <i class="fas fa-clock me-1"></i>
                        Scheduled
                    </span>
                @else
                    <span class="status-badge status-draft">
                        <i class="fas fa-edit me-1"></i>
                        Draft
                    </span>
                @endif
            </div>
        </div>
        
        <div class="content-card-body" style="padding: 1.5rem;">
            <div class="row g-4">
                {{-- Campaign Information --}}
                <div class="col-lg-8">
                    <div class="campaign-section">
                        <h6 class="section-title">
                            <i class="fas fa-envelope me-2"></i>
                            Campaign Information
                        </h6>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="info-label">Subject Line</label>
                                    <div class="info-value">{{ $campaign->subject }}</div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="info-label">Created</label>
                                    <div class="info-value">
                                        {{ $campaign->created_at->format('M d, Y \a\t g:i A') }}
                                        <small class="text-muted d-block">{{ $campaign->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </div>
                            
                            @if($campaign->sent_at)
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="info-label">Sent Date</label>
                                    <div class="info-value">
                                        {{ $campaign->sent_at->format('M d, Y \a\t g:i A') }}
                                        <small class="text-muted d-block">{{ $campaign->sent_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="info-label">Recipients</label>
                                    <div class="info-value">
                                        <span class="recipient-count">{{ number_format($campaign->sent_count ?? 0) }}</span>
                                        <small class="text-muted">subscribers</small>
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            @if($campaign->scheduled_at)
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="info-label">Scheduled For</label>
                                    <div class="info-value">
                                        {{ $campaign->scheduled_at->format('M d, Y \a\t g:i A') }}
                                        <small class="text-muted d-block">{{ $campaign->scheduled_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Campaign Content --}}
                    <div class="campaign-section mt-4">
                        <h6 class="section-title">
                            <i class="fas fa-file-text me-2"></i>
                            Campaign Content
                        </h6>
                        
                        <div class="content-preview">
                            <div class="content-body">
                                {!! $campaign->content !!}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Actions Sidebar --}}
                <div class="col-lg-4">
                    <div class="actions-section">
                        <h6 class="section-title">
                            <i class="fas fa-cog me-2"></i>
                            Campaign Actions
                        </h6>
                        
                        <div class="action-cards">
                            @if(!$campaign->sent_at)
                                {{-- Send Campaign --}}
                                <div class="action-card send-card">
                                    <div class="action-icon">
                                        <i class="fas fa-paper-plane"></i>
                                    </div>
                                    <div class="action-content">
                                        <h6 class="action-title">Send Campaign</h6>
                                        <p class="action-description">Send this campaign to all active subscribers</p>
                                        <button onclick="sendCampaign()" class="btn btn-success btn-sm w-100">
                                            <i class="fas fa-paper-plane me-2"></i>
                                            Send Now
                                        </button>
                                    </div>
                                </div>
                                
                                {{-- Edit Campaign --}}
                                <div class="action-card edit-card">
                                    <div class="action-icon">
                                        <i class="fas fa-edit"></i>
                                    </div>
                                    <div class="action-content">
                                        <h6 class="action-title">Edit Campaign</h6>
                                        <p class="action-description">Modify campaign content and settings</p>
                                        <button onclick="window.location.href='{{ route('admin.newsletters.edit', $campaign) }}'" class="btn btn-secondary btn-sm w-100">
                                            <i class="fas fa-edit me-2"></i>
                                            Edit Draft
                                        </button>
                                    </div>
                                </div>
                            @else
                                {{-- Campaign Sent Info --}}
                                <div class="action-card success-card">
                                    <div class="action-icon success-icon">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <div class="action-content">
                                        <h6 class="action-title">Campaign Sent</h6>
                                        <p class="action-description">This campaign has been successfully sent to {{ number_format($campaign->sent_count ?? 0) }} subscribers</p>
                                        <div class="sent-stats">
                                            <div class="stat-item">
                                                <strong>{{ number_format($campaign->sent_count ?? 0) }}</strong>
                                                <small>Recipients</small>
                                            </div>
                                            <div class="stat-item">
                                                <strong>{{ $campaign->sent_at->format('M d') }}</strong>
                                                <small>Sent Date</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            {{-- Preview Campaign --}}
                            <div class="action-card preview-card">
                                <div class="action-icon">
                                    <i class="fas fa-eye"></i>
                                </div>
                                <div class="action-content">
                                    <h6 class="action-title">Preview Campaign</h6>
                                    <p class="action-description">View how the campaign appears to subscribers</p>
                                    <button onclick="openPreviewModal()" class="btn btn-outline-secondary btn-sm w-100">
                                        <i class="fas fa-eye me-2"></i>
                                        Open Preview
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Send Campaign Confirmation Modal --}}
@if(!$campaign->sent_at)
<div class="modal fade" id="sendCampaignModal" tabindex="-1" aria-labelledby="sendCampaignModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: var(--success); color: white;">
                <h5 class="modal-title" id="sendCampaignModalLabel">
                    <i class="fas fa-paper-plane me-2"></i>
                    Send Campaign
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <i class="fas fa-bullhorn text-success" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">Ready to Send?</h5>
                    <p class="text-muted">You are about to send the campaign:</p>
                    <strong class="text-primary">{{ $campaign->subject }}</strong>
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Note:</strong> This campaign will be sent to all active subscribers. This action cannot be undone!
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancel
                </button>
                <form action="{{ route('admin.newsletters.send', $campaign) }}" method="POST" style="display: inline;" id="sendForm">
                    @csrf
                    <button type="submit" class="btn btn-success" id="confirmSendBtn">
                        <i class="fas fa-paper-plane me-2"></i>Send Campaign
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Preview Modal --}}
<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: var(--secondary); color: white;">
                <h5 class="modal-title" id="previewModalLabel">
                    <i class="fas fa-eye me-2"></i>
                    Campaign Preview
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="email-preview">
                    <div class="email-header">
                        <div class="row">
                            <div class="col-sm-3"><strong>Subject:</strong></div>
                            <div class="col-sm-9">{{ $campaign->subject }}</div>
                        </div>
                        @if($campaign->sent_at)
                        <div class="row mt-2">
                            <div class="col-sm-3"><strong>Sent:</strong></div>
                            <div class="col-sm-9">{{ $campaign->sent_at->format('M d, Y \a\t g:i A') }}</div>
                        </div>
                        @endif
                    </div>
                    <hr>
                    <div class="email-content">
                        {!! $campaign->content !!}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Close
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Newsletter Campaign Show Styles */
:root {
    --primary: #059669;
    --primary-dark: #047857;
    --success: #059669;
    --warning: #F59E0B;
    --danger: #DC2626;
    --secondary: #6B7280;
    --secondary-dark: #4B5563;
    --info: #0EA5E9;
    --white: #FFFFFF;
    --gray-50: #F9FAFB;
    --gray-100: #F3F4F6;
    --gray-200: #E5E7EB;
    --gray-300: #D1D5DB;
    --gray-400: #9CA3AF;
    --gray-500: #6B7280;
    --gray-600: #4B5563;
    --gray-700: #374151;
    --gray-800: #1F2937;
    --border-radius: 8px;
    --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --transition: all 0.2s ease;
}

.dashboard-header {
    background: var(--white);
    border-radius: var(--border-radius);
    padding: 0.5rem 0.75rem;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--gray-200);
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.dashboard-title {
    font-size: 0.95rem;
    font-weight: 600;
    color: var(--gray-800);
    margin: 0;
    display: flex;
    align-items: center;
}

.header-meta small {
    font-size: 0.8rem;
    color: var(--gray-500);
    display: block;
    margin-top: 0.125rem;
}

.header-actions .btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    height: 28px;
}

.content-card {
    background: var(--white);
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--gray-200);
}

.content-card-header {
    padding: 0.75rem 1rem;
    border-bottom: 1px solid var(--gray-200);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-title {
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--gray-800);
    margin: 0;
    display: flex;
    align-items: center;
}

.card-subtitle {
    color: var(--gray-500);
    font-size: 0.75rem;
    margin-top: 0.25rem;
}

/* Section Titles */
.section-title {
    color: var(--gray-700);
    font-weight: 600;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid var(--gray-200);
}

.section-title i {
    color: var(--secondary);
}

/* Campaign Section */
.campaign-section {
    background: var(--gray-50);
    border-radius: var(--border-radius);
    padding: 1.5rem;
    border: 1px solid var(--gray-200);
}

/* Info Items */
.info-item {
    margin-bottom: 1rem;
}

.info-label {
    font-weight: 600;
    color: var(--gray-600);
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 0.25rem;
    display: block;
}

.info-value {
    color: var(--gray-800);
    font-size: 0.875rem;
    font-weight: 500;
}

.recipient-count {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--success);
}

/* Content Preview */
.content-preview {
    background: var(--white);
    border: 1px solid var(--gray-200);
    border-radius: var(--border-radius);
    overflow: hidden;
}

.content-body {
    padding: 1.5rem;
    line-height: 1.6;
    color: var(--gray-700);
    max-height: 400px;
    overflow-y: auto;
}

/* Status Badges */
.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.375rem 0.75rem;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 600;
}

.status-sent {
    background: #F0FDF4;
    color: #047857;
    border: 1px solid #BBF7D0;
}

.status-scheduled {
    background: #EFF6FF;
    color: #1D4ED8;
    border: 1px solid #BFDBFE;
}

.status-draft {
    background: #FFFBEB;
    color: #D97706;
    border: 1px solid #FDE68A;
}

/* Actions Section */
.actions-section {
    background: var(--gray-50);
    border-radius: var(--border-radius);
    padding: 1.5rem;
    border: 1px solid var(--gray-200);
}

.action-cards {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.action-card {
    background: var(--white);
    border: 1px solid var(--gray-200);
    border-radius: var(--border-radius);
    padding: 1rem;
    display: flex;
    gap: 1rem;
    transition: var(--transition);
}

.action-card:hover {
    box-shadow: var(--shadow-sm);
    transform: translateY(-1px);
}

.action-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    background: var(--gray-100);
    color: var(--gray-600);
}

.send-card .action-icon {
    background: linear-gradient(135deg, #059669, #047857);
    color: white;
}

.edit-card .action-icon {
    background: linear-gradient(135deg, #6B7280, #4B5563);
    color: white;
}

.preview-card .action-icon {
    background: linear-gradient(135deg, #0EA5E9, #0284C7);
    color: white;
}

.success-card {
    border-color: #BBF7D0;
    background: linear-gradient(135deg, #F0FDF4, #DCFCE7);
}

.success-card .action-icon {
    background: linear-gradient(135deg, #059669, #047857);
    color: white;
}

.action-content {
    flex: 1;
    min-width: 0;
}

.action-title {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--gray-800);
    margin: 0 0 0.25rem 0;
}

.action-description {
    font-size: 0.75rem;
    color: var(--gray-500);
    margin: 0 0 0.75rem 0;
    line-height: 1.4;
}

/* Sent Stats */
.sent-stats {
    display: flex;
    gap: 1rem;
    margin-top: 0.75rem;
}

.stat-item {
    text-align: center;
}

.stat-item strong {
    display: block;
    font-size: 1rem;
    font-weight: 700;
    color: var(--success);
}

.stat-item small {
    font-size: 0.7rem;
    color: var(--gray-500);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

/* Button Styles */
.btn-secondary {
    background: var(--secondary);
    border-color: var(--secondary);
    color: white;
}

.btn-secondary:hover {
    background: var(--secondary-dark);
    border-color: var(--secondary-dark);
    color: white;
}

.btn-outline-secondary {
    color: var(--secondary);
    border-color: var(--secondary);
}

.btn-outline-secondary:hover {
    background: var(--secondary);
    border-color: var(--secondary);
    color: white;
}

/* Alert Styles */
.alert {
    border-radius: var(--border-radius);
    padding: 0.75rem 1rem;
    font-size: 0.85rem;
}

.alert-success {
    background: #F0FDF4;
    color: #166534;
    border: 1px solid #BBF7D0;
}

.alert-danger {
    background: #FEF2F2;
    color: #DC2626;
    border: 1px solid #FECACA;
}

.alert-info {
    background: #EFF6FF;
    color: #1E40AF;
    border: 1px solid #BFDBFE;
}

/* Email Preview */
.email-preview {
    font-family: Arial, sans-serif;
    line-height: 1.6;
}

.email-header {
    background: var(--gray-50);
    padding: 1rem;
    border-radius: var(--border-radius);
    margin-bottom: 1rem;
    font-size: 0.875rem;
}

.email-content {
    color: var(--gray-700);
}

/* Modal Styles */
.modal-content {
    border-radius: var(--border-radius);
    border: none;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

/* Responsive */
@media (max-width: 768px) {
    .dashboard-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
        padding: 0.5rem;
    }
    
    .header-actions {
        width: 100%;
        display: flex;
        justify-content: flex-end;
    }
    
    .content-card-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .content-card-header .header-actions {
        width: 100%;
        justify-content: flex-start;
    }
    
    .campaign-section,
    .actions-section {
        padding: 1rem;
    }
    
    .action-card {
        flex-direction: column;
        text-align: center;
        gap: 0.75rem;
    }
    
    .action-icon {
        margin: 0 auto;
    }
    
    .sent-stats {
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .info-value {
        font-size: 0.8rem;
    }
    
    .action-title {
        font-size: 0.8rem;
    }
    
    .action-description {
        font-size: 0.7rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Check for success message and show toastr
    @if(session('success'))
        toastr.success("{{ session('success') }}", "Success!", {
            closeButton: true,
            progressBar: true,
            timeOut: 5000,
            extendedTimeOut: 2000,
            positionClass: 'toast-top-right'
        });
    @endif
    
    // Check for error message and show toastr
    @if(session('error'))
        toastr.error("{{ session('error') }}", "Error!", {
            closeButton: true,
            progressBar: true,
            timeOut: 5000,
            extendedTimeOut: 2000,
            positionClass: 'toast-top-right'
        });
    @endif
    
    // Handle send form submission with loading state
    @if(!$campaign->sent_at)
    const sendForm = document.getElementById('sendForm');
    const confirmSendBtn = document.getElementById('confirmSendBtn');
    
    if (sendForm && confirmSendBtn) {
        sendForm.addEventListener('submit', function() {
            const originalContent = confirmSendBtn.innerHTML;
            confirmSendBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Sending...';
            confirmSendBtn.disabled = true;
            
            // Re-enable if form validation fails (client-side)
            setTimeout(() => {
                if (confirmSendBtn.disabled) {
                    confirmSendBtn.innerHTML = originalContent;
                    confirmSendBtn.disabled = false;
                }
            }, 5000);
        });
    }
    @endif
});

// Send campaign function
@if(!$campaign->sent_at)
function sendCampaign() {
    const modal = new bootstrap.Modal(document.getElementById('sendCampaignModal'));
    modal.show();
}
@endif

// Open preview modal function
function openPreviewModal() {
    const modal = new bootstrap.Modal(document.getElementById('previewModal'));
    modal.show();
}
</script>
@endpush

