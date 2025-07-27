@extends('layouts.app')

@section('title', 'Newsletter Subscribers')

@section('content')
<div class="dashboard-container">
    {{-- Compact Header --}}
    <div class="dashboard-header mb-2">
        <div class="header-content">
            <h1 class="dashboard-title">
                <i class="fas fa-envelope me-2"></i>
                Newsletter Subscribers
            </h1>
            <div class="header-meta">
                <span class="badge bg-secondary">{{ $subscribers->total() }} subscribers</span>
                <small class="text-muted">Updated: {{ now()->format('g:i a') }}</small>
            </div>
        </div>
        <div class="header-actions">
            <button onclick="openAddSubscriberModal()" class="btn btn-sm btn-success me-2">
                <i class="fas fa-plus me-1"></i>
                Add Subscriber
            </button>
            <button id="refreshSubscribers" class="btn btn-sm btn-secondary">
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

    {{-- Subscribers Table --}}
    <div class="content-card">
        <div class="content-card-header">
            <div class="header-content">
                <h4 class="card-title">
                    <i class="fas fa-users me-2"></i>
                    Newsletter Subscribers
                </h4>
                @if($subscribers->count() > 0)
                <p class="card-subtitle mb-0">
                    Showing {{ $subscribers->firstItem() }} to {{ $subscribers->lastItem() }} of {{ $subscribers->total() }} subscribers
                </p>
                @endif
            </div>
            <div class="header-actions">
                <div class="d-flex gap-2">
                    <span class="badge active-badge">
                        <i class="fas fa-check-circle me-1"></i>
                        {{ $subscribers->where('is_active', true)->count() }} Active
                    </span>
                    <span class="badge inactive-badge">
                        <i class="fas fa-times-circle me-1"></i>
                        {{ $subscribers->where('is_active', false)->count() }} Inactive
                    </span>
                </div>
            </div>
        </div>
        
        <div class="content-card-body">
            <div class="table-responsive">
                <table class="compact-table">
                    <thead>
                        <tr>
                            <th>Email Address</th>
                            <th>Status</th>
                            <th>Subscribed On</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subscribers as $subscriber)
                        <tr class="{{ !$subscriber->is_active ? 'inactive-row' : '' }}">
                            <td>
                                <div class="subscriber-info">
                                    <div class="subscriber-email">
                                        {{ $subscriber->email }}
                                    </div>
                                    @if($subscriber->name)
                                    <div class="subscriber-name">
                                        <i class="fas fa-user me-1"></i>
                                        {{ $subscriber->name }}
                                    </div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($subscriber->is_active)
                                    <span class="status-badge status-open">
                                        <i class="fas fa-check-circle me-1"></i>
                                        Active
                                    </span>
                                @else
                                    <span class="status-badge priority-high">
                                        <i class="fas fa-times-circle me-1"></i>
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="subscription-date">
                                    <div class="date-main">{{ $subscriber->created_at->format('M d, Y') }}</div>
                                    <small class="text-muted">{{ $subscriber->created_at->diffForHumans() }}</small>
                                </div>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button onclick="toggleSubscriber({{ $subscriber->id }}, '{{ $subscriber->email }}', {{ $subscriber->is_active ? 'true' : 'false' }})" 
                                       class="action-btn {{ $subscriber->is_active ? 'deactivate-btn' : 'activate-btn' }}" 
                                       title="{{ $subscriber->is_active ? 'Deactivate' : 'Activate' }} Subscriber">
                                        <i class="fas fa-{{ $subscriber->is_active ? 'ban' : 'check' }}"></i>
                                    </button>
                                    
                                    <button onclick="deleteSubscriber({{ $subscriber->id }}, '{{ $subscriber->email }}')" 
                                            class="action-btn delete-btn" 
                                            title="Delete Subscriber">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="empty-state">
                                <div class="empty-content">
                                    <i class="fas fa-inbox"></i>
                                    <h6>No Subscribers Found</h6>
                                    <p>When someone subscribes to your newsletter, they'll appear here.</p>
                                    <button onclick="openAddSubscriberModal()" 
                                            class="btn btn-success btn-sm mt-2">
                                        <i class="fas fa-plus me-1"></i>
                                        Add First Subscriber
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($subscribers->hasPages())
            <div class="pagination-wrapper">
                {{ $subscribers->onEachSide(1)->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Add Subscriber Modal --}}
<div class="modal fade" id="addSubscriberModal" tabindex="-1" aria-labelledby="addSubscriberModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: var(--success); color: white;">
                <h5 class="modal-title" id="addSubscriberModalLabel">
                    <i class="fas fa-plus me-2"></i>
                    Add New Subscriber
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="addSubscriberForm" method="POST" action="{{ route('admin.subscribers.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="email" class="form-label required">
                                    <i class="fas fa-envelope me-1"></i>
                                    Email Address
                                </label>
                                <input type="email" 
                                       class="form-control" 
                                       id="email" 
                                       name="email" 
                                       required
                                       placeholder="Enter email address">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="name" class="form-label">
                                    <i class="fas fa-user me-1"></i>
                                    Name (Optional)
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="name" 
                                       name="name"
                                       placeholder="Enter subscriber name">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-toggle-on me-1"></i>
                                    Status
                                </label>
                                <div class="form-check form-switch">
                                    <input type="checkbox" 
                                           name="is_active" 
                                           id="is_active" 
                                           class="form-check-input" 
                                           value="1"
                                           checked>
                                    <label for="is_active" class="form-check-label">
                                        Active Subscription
                                    </label>
                                    <small class="form-text text-muted d-block mt-1">
                                        Active subscribers will receive newsletters
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-success" id="submitBtn">
                        <i class="fas fa-plus me-2"></i>Add Subscriber
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Toggle Status Confirmation Modal --}}
<div class="modal fade" id="toggleStatusModal" tabindex="-1" aria-labelledby="toggleStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: var(--info); color: white;">
                <h5 class="modal-title" id="toggleStatusModalLabel">
                    <i class="fas fa-toggle-on me-2"></i>
                    Toggle Subscription Status
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <i class="fas fa-question-circle text-info" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">Are you sure?</h5>
                    <p class="text-muted">You are about to <span id="toggleAction"></span> the subscription for:</p>
                    <strong id="toggleEmail" class="text-primary"></strong>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancel
                </button>
                <form id="toggleStatusForm" method="POST" style="display: inline;">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-info" id="confirmToggleBtn">
                        <i class="fas fa-toggle-on me-2"></i>Update Status
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Delete Subscriber Confirmation Modal --}}
<div class="modal fade" id="deleteSubscriberModal" tabindex="-1" aria-labelledby="deleteSubscriberModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: var(--danger); color: white;">
                <h5 class="modal-title" id="deleteSubscriberModalLabel">
                    <i class="fas fa-trash me-2"></i>
                    Delete Subscriber
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <i class="fas fa-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">Are you sure?</h5>
                    <p class="text-muted">You are about to delete the subscriber:</p>
                    <strong id="deleteEmail" class="text-danger"></strong>
                    <p class="text-muted mt-2">This action cannot be undone!</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancel
                </button>
                <form id="deleteSubscriberForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" id="confirmDeleteBtn">
                        <i class="fas fa-trash me-2"></i>Delete Subscriber
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Newsletter Subscribers Styles */
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
.active-badge {
    background: #F0FDF4 !important;
    color: #166534 !important;
    border: 1px solid #BBF7D0;
    font-size: 0.7rem;
    padding: 0.3rem 0.6rem;
}

.inactive-badge {
    background: #FEF2F2 !important;
    color: #DC2626 !important;
    border: 1px solid #FECACA;
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

/* Inactive Row Styling */
.inactive-row {
    background: #FEF2F2 !important;
    opacity: 0.7;
}

.inactive-row:hover {
    background: #FECACA !important;
    opacity: 0.8;
}

/* Subscriber Info */
.subscriber-info {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.subscriber-email {
    font-weight: 500;
    color: var(--gray-800);
    font-size: 0.85rem;
}

.subscriber-name {
    color: var(--gray-500);
    font-size: 0.75rem;
}

/* Subscription Date */
.subscription-date {
    font-size: 0.8rem;
}

.date-main {
    font-weight: 500;
    color: var(--gray-800);
}

.subscription-date small {
    display: block;
    margin-top: 0.15rem;
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

.status-open {
    background: #F0FDF4;
    color: #047857;
    border: 1px solid #BBF7D0;
}

.priority-high {
    background: #FEF2F2;
    color: #DC2626;
    border: 1px solid #FECACA;
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

.activate-btn {
    background: #F0FDF4;
    color: #059669;
    border: 1px solid #BBF7D0;
}

.activate-btn:hover {
    background: #059669;
    color: white;
}

.deactivate-btn {
    background: #FFFBEB;
    color: #D97706;
    border: 1px solid #FDE68A;
}

.deactivate-btn:hover {
    background: #D97706;
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

/* Form Elements */
.form-group {
    margin-bottom: 1rem;
}

.form-label {
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    font-size: 0.875rem;
}

.form-label.required::after {
    content: ' *';
    color: var(--danger);
}

.form-label i {
    color: var(--secondary);
    width: 16px;
}

.form-control {
    border: 1px solid var(--gray-300);
    border-radius: var(--border-radius);
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
    transition: var(--transition);
}

.form-control:focus {
    border-color: var(--secondary);
    box-shadow: 0 0 0 2px rgba(107, 114, 128, 0.1);
}

.form-control.is-invalid {
    border-color: var(--danger);
}

.form-control.is-invalid:focus {
    border-color: var(--danger);
    box-shadow: 0 0 0 2px rgba(220, 38, 38, 0.1);
}

.invalid-feedback {
    color: var(--danger);
    font-size: 0.75rem;
    margin-top: 0.25rem;
}

/* Form Switch */
.form-check-input:checked {
    background-color: var(--secondary);
    border-color: var(--secondary);
}

.form-check-input:focus {
    box-shadow: 0 0 0 2px rgba(107, 114, 128, 0.1);
}

.form-check-label {
    font-weight: 500;
    color: var(--gray-700);
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
    
    .subscriber-info {
        width: 100%;
    }
    
    .action-buttons {
        flex-direction: column;
        gap: 0.15rem;
    }
}

@media (max-width: 480px) {
    .subscriber-email {
        font-size: 0.75rem;
    }
    
    .status-badge {
        font-size: 0.7rem;
        padding: 0.2rem 0.4rem;
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
    const refreshBtn = document.getElementById('refreshSubscribers');
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
    
    // Handle add subscriber form submission
    const addSubscriberForm = document.getElementById('addSubscriberForm');
    const submitBtn = document.getElementById('submitBtn');
    
    if (addSubscriberForm && submitBtn) {
        addSubscriberForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const originalContent = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Adding...';
            submitBtn.disabled = true;
            
            const formData = new FormData(this);
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('addSubscriberModal'));
                    modal.hide();
                    
                    toastr.success(data.message || 'Subscriber added successfully!', 'Success!', {
                        closeButton: true,
                        progressBar: true,
                        timeOut: 3000,
                        positionClass: 'toast-top-right'
                    });
                    
                    // Reset form
                    addSubscriberForm.reset();
                    document.getElementById('is_active').checked = true;
                    
                    // Reload page after short delay
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    // Handle validation errors
                    if (data.errors) {
                        Object.keys(data.errors).forEach(key => {
                            const input = document.getElementById(key);
                            const feedback = input.nextElementSibling;
                            
                            input.classList.add('is-invalid');
                            if (feedback && feedback.classList.contains('invalid-feedback')) {
                                feedback.textContent = data.errors[key][0];
                            }
                        });
                    }
                    
                    toastr.error(data.message || 'Error adding subscriber.', 'Error!', {
                        closeButton: true,
                        progressBar: true,
                        timeOut: 5000,
                        positionClass: 'toast-top-right'
                    });
                }
            })
            .catch(error => {
                toastr.error('Error adding subscriber. Please try again.', 'Error!', {
                    closeButton: true,
                    progressBar: true,
                    timeOut: 5000,
                    positionClass: 'toast-top-right'
                });
            })
            .finally(() => {
                // Restore button
                submitBtn.innerHTML = originalContent;
                submitBtn.disabled = false;
            });
        });
    }
    
    // Clear validation errors on input
    document.querySelectorAll('.form-control').forEach(input => {
        input.addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
    });
});

// Open add subscriber modal
function openAddSubscriberModal() {
    // Clear form and validation errors
    const form = document.getElementById('addSubscriberForm');
    form.reset();
    document.getElementById('is_active').checked = true;
    
    // Clear validation errors
    document.querySelectorAll('.form-control').forEach(input => {
        input.classList.remove('is-invalid');
    });
    
    const modal = new bootstrap.Modal(document.getElementById('addSubscriberModal'));
    modal.show();
    
    // Focus on email input
    setTimeout(() => {
        document.getElementById('email').focus();
    }, 500);
}

// Toggle subscriber status functionality
function toggleSubscriber(subscriberId, email, isActive) {
    const action = isActive ? 'deactivate' : 'activate';
    document.getElementById('toggleAction').textContent = action;
    document.getElementById('toggleEmail').textContent = email;
    document.getElementById('toggleStatusForm').action = `/admin/subscribers/${subscriberId}/toggle`;
    
    const modal = new bootstrap.Modal(document.getElementById('toggleStatusModal'));
    modal.show();
    
    // Handle form submission with loading state and toastr
    const toggleForm = document.getElementById('toggleStatusForm');
    const confirmBtn = document.getElementById('confirmToggleBtn');
    
    toggleForm.onsubmit = function(e) {
        e.preventDefault();
        
        const originalContent = confirmBtn.innerHTML;
        confirmBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Updating...';
        confirmBtn.disabled = true;
        
        // Submit the form with fetch for better control
        fetch(toggleForm.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                _method: 'PATCH'
            })
        })
        .then(response => response.json())
        .then(data => {
            modal.hide();
            
            if (data.success) {
                toastr.success(`Subscriber ${action}d successfully!`, 'Success!', {
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
                toastr.error('Error updating subscriber status. Please try again.', 'Error!', {
                    closeButton: true,
                    progressBar: true,
                    timeOut: 5000,
                    positionClass: 'toast-top-right'
                });
            }
        })
        .catch(error => {
            modal.hide();
            toastr.error('Error updating subscriber status. Please try again.', 'Error!', {
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

// Delete subscriber functionality with toastr
function deleteSubscriber(subscriberId, email) {
    document.getElementById('deleteEmail').textContent = email;
    document.getElementById('deleteSubscriberForm').action = `/admin/subscribers/${subscriberId}`;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteSubscriberModal'));
    modal.show();
    
    // Handle form submission with loading state and toastr
    const deleteForm = document.getElementById('deleteSubscriberForm');
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
            
            if (data.success) {
                toastr.success('Subscriber deleted successfully!', 'Success!', {
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
                toastr.error('Error deleting subscriber. Please try again.', 'Error!', {
                    closeButton: true,
                    progressBar: true,
                    timeOut: 5000,
                    positionClass: 'toast-top-right'
                });
            }
        })
        .catch(error => {
            modal.hide();
            toastr.error('Error deleting subscriber. Please try again.', 'Error!', {
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
