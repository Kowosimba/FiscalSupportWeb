@extends('layouts.app')

@section('title', 'Newsletter Campaigns')

@section('content')
<div class="dashboard-container">
    {{-- Compact Header --}}
    <div class="dashboard-header mb-2">
        <div class="header-content">
            <h1 class="dashboard-title">
                <i class="fas fa-bullhorn me-2"></i>
                Newsletter Campaigns
            </h1>
            <div class="header-meta">
                <span class="badge bg-secondary">{{ $campaigns->total() }} campaigns</span>
                <small class="text-muted">Updated: {{ now()->format('g:i a') }}</small>
            </div>
        </div>
        <div class="header-actions">
            <button onclick="window.location.href='{{ route('admin.newsletters.create') }}'" class="btn btn-sm btn-success me-2">
                <i class="fas fa-plus me-1"></i>
                Create Campaign
            </button>
            <button id="refreshCampaigns" class="btn btn-sm btn-secondary">
                <i class="fas fa-sync-alt me-1"></i>
                Refresh
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

    {{-- Campaigns Table --}}
    <div class="content-card">
        <div class="content-card-header">
            <div class="header-content">
                <h4 class="card-title">
                    <i class="fas fa-list me-2"></i>
                    Newsletter Campaigns
                </h4>
                @if($campaigns->count() > 0)
                <p class="card-subtitle mb-0">
                    Showing {{ $campaigns->firstItem() }} to {{ $campaigns->lastItem() }} of {{ $campaigns->total() }} campaigns
                </p>
                @endif
            </div>
            <div class="header-actions">
                <div class="d-flex gap-2">
                    <span class="badge sent-badge">
                        <i class="fas fa-check-circle me-1"></i>
                        {{ $campaigns->whereNotNull('sent_at')->count() }} Sent
                    </span>
                    <span class="badge draft-badge">
                        <i class="fas fa-edit me-1"></i>
                        {{ $campaigns->whereNull('sent_at')->count() }} Drafts
                    </span>
                </div>
            </div>
        </div>
        
        <div class="content-card-body">
            <div class="table-responsive">
                <table class="compact-table">
                    <thead>
                        <tr>
                            <th>Campaign</th>
                            <th>Status</th>
                            <th>Sent On</th>
                            <th>Recipients</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($campaigns as $campaign)
                        <tr class="{{ !$campaign->sent_at ? 'draft-row' : '' }}">
                            <td>
                                <div class="campaign-info">
                                    <div class="campaign-subject">
                                        {{ Str::limit($campaign->subject, 50) }}
                                    </div>
                                    <div class="campaign-meta">
                                        <i class="fas fa-calendar me-1"></i>
                                        Created {{ $campaign->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </td>
                            <td>
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
                            </td>
                            <td>
                                <div class="sent-info">
                                    @if($campaign->sent_at)
                                        <div class="sent-date">{{ $campaign->sent_at->format('M d, Y') }}</div>
                                        <small class="text-muted">{{ $campaign->sent_at->format('g:i A') }}</small>
                                    @elseif($campaign->scheduled_at)
                                        <div class="scheduled-date">{{ $campaign->scheduled_at->format('M d, Y') }}</div>
                                        <small class="text-muted">{{ $campaign->scheduled_at->format('g:i A') }} (scheduled)</small>
                                    @else
                                        <span class="text-muted">Not sent yet</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="recipients-info">
                                    <span class="recipient-count">{{ number_format($campaign->sent_count ?? 0) }}</span>
                                    @if($campaign->sent_count > 0)
                                        <small class="text-muted d-block">recipients</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button onclick="window.location.href='{{ route('admin.newsletters.show', $campaign) }}'" 
                                       class="action-btn view-btn" 
                                       title="View Campaign">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    
                                    @if(!$campaign->sent_at)
                                        <button onclick="window.location.href='{{ route('admin.newsletters.edit', $campaign) }}'" 
                                           class="action-btn edit-btn" 
                                           title="Edit Campaign">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        
                                        <button onclick="sendCampaign({{ $campaign->id }}, '{{ addslashes(Str::limit($campaign->subject, 30)) }}')" 
                                                class="action-btn send-btn" 
                                                title="Send Campaign">
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    @endif
                                    
                                    <button onclick="deleteCampaign({{ $campaign->id }}, '{{ addslashes(Str::limit($campaign->subject, 30)) }}')" 
                                            class="action-btn delete-btn" 
                                            title="Delete Campaign">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="empty-state">
                                <div class="empty-content">
                                    <i class="fas fa-bullhorn"></i>
                                    <h6>No Newsletter Campaigns Found</h6>
                                    <p>No newsletter campaigns have been created yet.</p>
                                    <button onclick="window.location.href='{{ route('admin.newsletters.create') }}'" 
                                            class="btn btn-success btn-sm mt-2">
                                        <i class="fas fa-plus me-1"></i>
                                        Create First Campaign
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($campaigns->hasPages())
            <div class="pagination-wrapper">
                {{ $campaigns->onEachSide(1)->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Send Campaign Confirmation Modal --}}
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
                    <strong id="campaignSubject" class="text-primary"></strong>
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
                <form id="sendCampaignForm" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-success" id="confirmSendBtn">
                        <i class="fas fa-paper-plane me-2"></i>Send Campaign
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Delete Campaign Confirmation Modal --}}
<div class="modal fade" id="deleteCampaignModal" tabindex="-1" aria-labelledby="deleteCampaignModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: var(--danger); color: white;">
                <h5 class="modal-title" id="deleteCampaignModalLabel">
                    <i class="fas fa-trash me-2"></i>
                    Delete Campaign
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <i class="fas fa-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">Are you sure?</h5>
                    <p class="text-muted">You are about to delete the campaign:</p>
                    <strong id="deleteCampaignSubject" class="text-danger"></strong>
                    <p class="text-muted mt-2">This action cannot be undone!</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancel
                </button>
                <form id="deleteCampaignForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" id="confirmDeleteBtn">
                        <i class="fas fa-trash me-2"></i>Delete Campaign
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Newsletter Campaigns Index Styles */
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

/* Compact Dashboard Styles */
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

.header-meta {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-top: 0.125rem;
}

.header-meta .badge {
    font-size: 0.65rem;
    padding: 0.2rem 0.4rem;
    border-radius: 4px;
}

.bg-secondary {
    background: var(--secondary) !important;
    color: white;
}

.header-meta small {
    font-size: 0.7rem;
    color: var(--gray-500);
}

.header-actions {
    display: flex;
    gap: 0.5rem;
}

.header-actions .btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    height: 28px;
}

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

/* Content Card */
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

.content-card-body {
    padding: 0;
}

/* Header Action Badges */
.sent-badge {
    background: #F0FDF4 !important;
    color: #166534 !important;
    border: 1px solid #BBF7D0;
    font-size: 0.7rem;
    padding: 0.3rem 0.6rem;
}

.draft-badge {
    background: #FFFBEB !important;
    color: #D97706 !important;
    border: 1px solid #FDE68A;
    font-size: 0.7rem;
    padding: 0.3rem 0.6rem;
}

/* Compact Table Styles */
.compact-table {
    width: 100%;
    font-size: 0.85rem;
    border-collapse: separate;
    border-spacing: 0;
}

.compact-table th {
    background: var(--gray-50);
    color: var(--gray-700);
    font-weight: 600;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 0.5rem 0.75rem;
    border-bottom: 1px solid var(--gray-200);
}

.compact-table td {
    padding: 0.5rem 0.75rem;
    vertical-align: middle;
    border-bottom: 1px solid var(--gray-100);
}

.compact-table tr:last-child td {
    border-bottom: none;
}

.compact-table tr:hover {
    background: var(--gray-50);
}

/* Draft Row Styling */
.draft-row {
    background: #FFFBEB !important;
    opacity: 0.8;
}

.draft-row:hover {
    background: #FEF3C7 !important;
    opacity: 0.9;
}

/* Campaign Info */
.campaign-info {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.campaign-subject {
    font-weight: 500;
    color: var(--gray-800);
    font-size: 0.85rem;
}

.campaign-meta {
    color: var(--gray-500);
    font-size: 0.75rem;
}

/* Sent Info */
.sent-info {
    font-size: 0.8rem;
}

.sent-date,
.scheduled-date {
    font-weight: 500;
    color: var(--gray-800);
}

.sent-info small {
    display: block;
    margin-top: 0.15rem;
}

/* Recipients Info */
.recipients-info {
    text-align: center;
}

.recipient-count {
    font-size: 1rem;
    font-weight: 700;
    color: var(--gray-800);
    display: block;
}

.recipients-info small {
    font-size: 0.7rem;
    color: var(--gray-500);
}

/* Status badges */
.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
    white-space: nowrap;
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

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 0.25rem;
    align-items: center;
}

.action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    border-radius: 4px;
    transition: var(--transition);
    border: none;
    cursor: pointer;
    background: none;
    padding: 0;
}

.view-btn {
    background: #F3F4F6;
    color: #4B5563;
    border: 1px solid #D1D5DB;
}

.view-btn:hover {
    background: #4B5563;
    color: white;
}

.edit-btn {
    background: #EFF6FF;
    color: #1D4ED8;
    border: 1px solid #BFDBFE;
}

.edit-btn:hover {
    background: #1D4ED8;
    color: white;
}

.send-btn {
    background: #F0FDF4;
    color: #059669;
    border: 1px solid #BBF7D0;
}

.send-btn:hover {
    background: #059669;
    color: white;
}

.delete-btn {
    background: #FEF2F2;
    color: #DC2626;
    border: 1px solid #FECACA;
}

.delete-btn:hover {
    background: #DC2626;
    color: white;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 2rem 1rem;
}

.empty-content i {
    font-size: 1.5rem;
    color: var(--gray-300);
    margin-bottom: 0.5rem;
}

.empty-content h6 {
    font-size: 0.9rem;
    color: var(--gray-600);
    margin-bottom: 0.25rem;
}

.empty-content p {
    font-size: 0.8rem;
    color: var(--gray-500);
    margin: 0;
}

/* Pagination */
.pagination-wrapper {
    padding: 0.75rem;
    border-top: 1px solid var(--gray-200);
}

/* Override Bootstrap pagination colors */
.pagination .page-link {
    color: var(--secondary);
    border-color: var(--gray-300);
}

.pagination .page-link:hover {
    color: var(--secondary-dark);
    background-color: var(--gray-50);
    border-color: var(--gray-300);
}

.pagination .page-item.active .page-link {
    background-color: var(--secondary);
    border-color: var(--secondary);
    color: white;
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
        justify-content: flex-end;
    }
    
    .compact-table {
        font-size: 0.8rem;
    }
    
    .compact-table th,
    .compact-table td {
        padding: 0.4rem 0.5rem;
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
    
    .campaign-info {
        width: 100%;
    }
    
    .action-buttons {
        flex-direction: column;
        gap: 0.15rem;
    }
}

@media (max-width: 480px) {
    .campaign-subject {
        font-size: 0.75rem;
    }
    
    .status-badge {
        font-size: 0.7rem;
        padding: 0.2rem 0.4rem;
    }
    
    .recipient-count {
        font-size: 0.85rem;
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
    
    // Refresh button functionality
    const refreshBtn = document.getElementById('refreshCampaigns');
    if (refreshBtn) {
        refreshBtn.addEventListener('click', function() {
            const originalContent = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Refreshing';
            this.disabled = true;
            
            setTimeout(() => {
                window.location.reload();
            }, 800);
        });
    }
});

// Send campaign functionality with toastr
function sendCampaign(campaignId, campaignSubject) {
    document.getElementById('campaignSubject').textContent = campaignSubject;
    document.getElementById('sendCampaignForm').action = `/admin/newsletters/${campaignId}/send`;
    
    const modal = new bootstrap.Modal(document.getElementById('sendCampaignModal'));
    modal.show();
    
    // Handle form submission with loading state and toastr
    const sendForm = document.getElementById('sendCampaignForm');
    const confirmBtn = document.getElementById('confirmSendBtn');
    
    sendForm.onsubmit = function(e) {
        e.preventDefault();
        
        const originalContent = confirmBtn.innerHTML;
        confirmBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Sending...';
        confirmBtn.disabled = true;
        
        // Submit the form with fetch for better control
        fetch(sendForm.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            modal.hide();
            
            if (data.success || response.ok) {
                toastr.success('Campaign sent successfully to all subscribers!', 'Campaign Sent!', {
                    closeButton: true,
                    progressBar: true,
                    timeOut: 5000,
                    positionClass: 'toast-top-right'
                });
                
                // Reload page after short delay
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            } else {
                toastr.error(data.message || 'Error sending campaign. Please try again.', 'Error!', {
                    closeButton: true,
                    progressBar: true,
                    timeOut: 5000,
                    positionClass: 'toast-top-right'
                });
            }
        })
        .catch(error => {
            modal.hide();
            toastr.error('Error sending campaign. Please try again.', 'Error!', {
                closeButton: true,
                progressBar: true,
                timeOut: 5000,
                positionClass: 'toast-top-right'
            });
        })
        .finally(() => {
            // Restore button
            confirmBtn.innerHTML = originalContent;
            confirmBtn.disabled = false;
        });
    };
}

// Delete campaign functionality with toastr
function deleteCampaign(campaignId, campaignSubject) {
    document.getElementById('deleteCampaignSubject').textContent = campaignSubject;
    document.getElementById('deleteCampaignForm').action = `/admin/newsletters/${campaignId}`;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteCampaignModal'));
    modal.show();
    
    // Handle form submission with loading state and toastr
    const deleteForm = document.getElementById('deleteCampaignForm');
    const confirmBtn = document.getElementById('confirmDeleteBtn');
    
    deleteForm.onsubmit = function(e) {
        e.preventDefault();
        
        const originalContent = confirmBtn.innerHTML;
        confirmBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Deleting...';
        confirmBtn.disabled = true;
        
        // Submit the form with fetch for better control
        fetch(deleteForm.action, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            modal.hide();
            
            if (data.success || response.ok) {
                toastr.success('Campaign deleted successfully!', 'Success!', {
                    closeButton: true,
                    progressBar: true,
                    timeOut: 3000,
                    positionClass: 'toast-top-right'
                });
                
                // Reload page after short delay
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                toastr.error('Error deleting campaign. Please try again.', 'Error!', {
                    closeButton: true,
                    progressBar: true,
                    timeOut: 5000,
                    positionClass: 'toast-top-right'
                });
            }
        })
        .catch(error => {
            modal.hide();
            toastr.error('Error deleting campaign. Please try again.', 'Error!', {
                closeButton: true,
                progressBar: true,
                timeOut: 5000,
                positionClass: 'toast-top-right'
            });
        })
        .finally(() => {
            // Restore button
            confirmBtn.innerHTML = originalContent;
            confirmBtn.disabled = false;
        });
    };
}
</script>
@endpush

