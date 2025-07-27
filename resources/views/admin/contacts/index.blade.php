@extends('layouts.app')

@section('title', 'Customer Contacts')

@section('content')
<div class="dashboard-container">
    {{-- Compact Header --}}
    <div class="dashboard-header mb-2">
        <div class="header-content">
            <h1 class="dashboard-title">
                <i class="fas fa-address-book me-2"></i>
                Customer Contacts
            </h1>
            <div class="header-meta">
                <span class="badge bg-secondary">{{ $contacts->total() ?? 0 }} contacts</span>
                <small class="text-muted">Updated: {{ now()->format('g:i a') }}</small>
            </div>
        </div>
        <div class="header-actions">
            <button onclick="window.location.href='{{ route('admin.contacts.create') }}'" class="btn btn-sm btn-success me-2">
                <i class="fas fa-plus me-1"></i>
                Add Contact
            </button>
            <button id="refreshContacts" class="btn btn-sm btn-secondary">
                <i class="fas fa-sync-alt me-1"></i>
                Refresh
            </button>
        </div>
    </div>

    {{-- Compact Filter Card --}}
    <div class="filter-card mb-2">
        <form method="GET" action="{{ route('admin.contacts.index') }}" class="filter-form">
            <div class="filter-row">
                <div class="filter-group search-group">
                    <div class="search-input-wrapper">
                        <i class="fas fa-search"></i>
                        <input type="text" name="search" id="search"
                            value="{{ $search ?? '' }}"
                            placeholder="Search by name, email, phone, company, or position..."
                            class="form-control form-control-sm">
                    </div>
                </div>
                
                <div class="filter-actions">
                    <button type="submit" class="btn btn-sm btn-secondary">
                        <i class="fas fa-search me-1"></i>
                        Search
                    </button>
                    @if(request('search'))
                        <a href="{{ route('admin.contacts.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    {{-- Contacts Table --}}
    <div class="content-card">
        <div class="content-card-header">
            <div class="header-content">
                <h4 class="card-title">
                    <i class="fas fa-list me-2"></i>
                    Customer Contacts Directory
                </h4>
                @if($contacts->count() > 0)
                <p class="card-subtitle mb-0">
                    Showing {{ $contacts->firstItem() }} to {{ $contacts->lastItem() }} of {{ $contacts->total() }} contacts
                </p>
                @endif
            </div>
            <div class="header-actions">
                <div class="d-flex gap-2">
                    <span class="badge active-contacts">
                        <i class="fas fa-user-check me-1"></i>
                        {{ $contacts->where('is_active', true)->count() }} Active
                    </span>
                    <span class="badge inactive-contacts">
                        <i class="fas fa-user-times me-1"></i>
                        {{ $contacts->where('is_active', false)->count() }} Inactive
                    </span>
                </div>
            </div>
        </div>
        
        <div class="content-card-body">
            <div class="table-responsive">
                <table class="compact-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Company</th>
                            <th>Position</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($contacts as $contact)
                        <tr class="{{ !$contact->is_active ? 'inactive-row' : '' }}">
                            <td>
                                <div class="contact-info">
                                    <div class="contact-name">
                                        @if(!$contact->is_active)
                                            <i class="fas fa-user-slash me-1 text-muted" title="Inactive Contact"></i>
                                        @endif
                                        {{ $contact->name }}
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="email-info">
                                    @if($contact->email)
                                        <a href="mailto:{{ $contact->email }}" class="email-link">
                                            <i class="fas fa-envelope me-1"></i>
                                            {{ $contact->email }}
                                        </a>
                                    @else
                                        <span class="text-muted">No email</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="phone-info">
                                    @if($contact->phone)
                                        <a href="tel:{{ $contact->phone }}" class="phone-link">
                                            <i class="fas fa-phone me-1"></i>
                                            {{ $contact->phone }}
                                        </a>
                                    @else
                                        <span class="text-muted">No phone</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="company-info">
                                    @if($contact->company)
                                        <span class="company-name">{{ Str::limit($contact->company, 25) }}</span>
                                    @else
                                        <span class="text-muted">No company</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="position-info">
                                    @if($contact->position)
                                        <span class="position-badge">
                                            <i class="fas fa-briefcase me-1"></i>
                                            {{ Str::limit($contact->position, 20) }}
                                        </span>
                                    @else
                                        <span class="text-muted">No position</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button onclick="window.location.href='{{ route('admin.contacts.edit', $contact) }}'" 
                                       class="action-btn edit-btn" 
                                       title="Edit Contact">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="action-btn delete-btn" 
                                            onclick="deleteContact({{ $contact->id }}, '{{ addslashes($contact->name) }}')"
                                            title="Delete Contact">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="empty-state">
                                <div class="empty-content">
                                    <i class="fas fa-address-book"></i>
                                    <h6>No Contacts Found</h6>
                                    <p>No customer contacts match your search criteria.</p>
                                    <button onclick="window.location.href='{{ route('admin.contacts.create') }}'" 
                                            class="btn btn-success btn-sm mt-2">
                                        <i class="fas fa-plus me-1"></i>
                                        Add First Contact
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($contacts->hasPages())
            <div class="pagination-wrapper">
                {{ $contacts->withQueryString()->onEachSide(1)->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: var(--danger); color: white;">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-trash me-2"></i>
                    Delete Contact
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <i class="fas fa-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">Are you sure?</h5>
                    <p class="text-muted">You are about to delete the contact:</p>
                    <strong id="contactName" class="text-danger"></strong>
                    <p class="text-muted mt-2">This action cannot be undone!</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancel
                </button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" id="confirmDeleteBtn">
                        <i class="fas fa-trash me-2"></i>Delete Contact
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Updated CSS Variables for Customer Contacts */
:root {
    --primary: #059669;
    --primary-dark: #047857;
    --success: #059669;
    --warning: #F59E0B;
    --danger: #DC2626;
    --secondary: #1c5c3f;
    --secondary-dark: #2e563d;
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

/* Compact Filter Card */
.filter-card {
    background: var(--white);
    border-radius: var(--border-radius);
    padding: 0.75rem;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--gray-200);
    margin-bottom: 1rem;
}

.filter-form .filter-row {
    display: flex;
    gap: 0.75rem;
    align-items: center;
    flex-wrap: wrap;
}

.filter-group {
    flex: 1;
    min-width: 140px;
}

.search-group {
    flex: 3;
    min-width: 300px;
}

.search-input-wrapper {
    position: relative;
}

.search-input-wrapper i {
    position: absolute;
    left: 0.75rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--gray-500);
    font-size: 0.85rem;
}

.search-input-wrapper .form-control {
    padding-left: 2.25rem;
}

.filter-actions {
    display: flex;
    gap: 0.5rem;
}

.filter-actions .btn-secondary {
    background: var(--secondary);
    border-color: var(--secondary);
}

.filter-actions .btn-secondary:hover {
    background: var(--secondary-dark);
    border-color: var(--secondary-dark);
}

.form-select-sm, .form-control-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.85rem;
    height: 32px;
    border: 1px solid var(--gray-300);
    border-radius: var(--border-radius);
}

.form-select-sm:focus, .form-control-sm:focus {
    border-color: var(--secondary);
    box-shadow: 0 0 0 2px rgba(107, 114, 128, 0.1);
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
.active-contacts {
    background: #F0FDF4 !important;
    color: #166534 !important;
    border: 1px solid #BBF7D0;
    font-size: 0.7rem;
    padding: 0.3rem 0.6rem;
}

.inactive-contacts {
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

/* Inactive Row Styling */
.inactive-row {
    background: #FAFAFA !important;
    opacity: 0.7;
}

.inactive-row:hover {
    background: #F5F5F5 !important;
    opacity: 0.8;
}

/* Contact Info Styles */
.contact-info {
    display: flex;
    flex-direction: column;
    gap: 0.15rem;
}

.contact-name {
    font-weight: 600;
    color: var(--gray-800);
    font-size: 0.85rem;
    display: flex;
    align-items: center;
}

/* Email and Phone Links */
.email-link, .phone-link {
    color: var(--gray-700);
    text-decoration: none;
    font-size: 0.85rem;
    transition: var(--transition);
    display: flex;
    align-items: center;
}

.email-link:hover, .phone-link:hover {
    color: var(--secondary);
    text-decoration: none;
}

.email-link i {
    color: var(--secondary);
}

.phone-link i {
    color: var(--success);
}

/* Company and Position Info */
.company-info, .position-info {
    font-size: 0.85rem;
}

.company-name {
    font-weight: 500;
    color: var(--gray-800);
}

.position-badge {
    display: inline-flex;
    align-items: center;
    color: var(--gray-600);
    background: var(--gray-100);
    padding: 0.2rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 500;
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

.edit-btn {
    background: #EFF6FF;
    color: #1D4ED8;
    border: 1px solid #BFDBFE;
}

.edit-btn:hover {
    background: #1D4ED8;
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

/* Modal Styles */
.modal-content {
    border-radius: var(--border-radius);
    border: none;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
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
    
    .filter-row {
        flex-direction: column;
        align-items: stretch;
        gap: 0.5rem;
    }
    
    .filter-group, .search-group {
        min-width: 100%;
    }
    
    .filter-actions {
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
}

@media (max-width: 480px) {
    .contact-info,
    .email-info,
    .phone-info,
    .company-info,
    .position-info {
        font-size: 0.75rem;
    }
    
    .action-buttons {
        flex-direction: column;
        gap: 0.15rem;
    }
    
    .position-badge {
        font-size: 0.7rem;
        padding: 0.15rem 0.3rem;
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
    const refreshBtn = document.getElementById('refreshContacts');
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

// Delete contact functionality with toastr
function deleteContact(contactId, contactName) {
    document.getElementById('contactName').textContent = contactName;
    document.getElementById('deleteForm').action = `/admin/contacts/${contactId}`;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
    
    // Handle form submission with loading state and toastr
    const deleteForm = document.getElementById('deleteForm');
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
                toastr.success('Contact deleted successfully!', 'Success!', {
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
                toastr.error('Error deleting contact. Please try again.', 'Error!', {
                    closeButton: true,
                    progressBar: true,
                    timeOut: 5000,
                    positionClass: 'toast-top-right'
                });
            }
        })
        .catch(error => {
            modal.hide();
            toastr.error('Error deleting contact. Please try again.', 'Error!', {
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

