@extends('layouts.app')

@section('title', 'FAQ Categories Management')

@section('content')
<div class="dashboard-container">
    {{-- Compact Header --}}
    <div class="dashboard-header mb-2">
        <div class="header-content">
            <h1 class="dashboard-title">
                <i class="fas fa-folder me-2"></i>
                FAQ Categories Management
            </h1>
            <div class="header-meta">
                <span class="badge bg-secondary">{{ $categories->count() }} categories</span>
                <small class="text-muted">Updated: {{ now()->format('g:i a') }}</small>
            </div>
        </div>
        <div class="header-actions">
            <button onclick="openCreateModal()" class="btn btn-sm btn-success me-2">
                <i class="fas fa-plus me-1"></i>
                Add Category
            </button>
            <button id="refreshCategories" class="btn btn-sm btn-secondary">
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

    {{-- Categories Table --}}
    <div class="content-card">
        <div class="content-card-header">
            <div class="header-content">
                <h4 class="card-title">
                    <i class="fas fa-list me-2"></i>
                    FAQ Categories
                </h4>
                @if($categories->count() > 0)
                <p class="card-subtitle mb-0">
                    Organize {{ $categories->count() }} FAQ categories
                </p>
                @endif
            </div>
            <div class="header-actions">
                <div class="d-flex gap-2">
                    <span class="badge active-categories">
                        <i class="fas fa-check-circle me-1"></i>
                        {{ $categories->where('is_active', true)->count() }} Active
                    </span>
                    <span class="badge inactive-categories">
                        <i class="fas fa-times-circle me-1"></i>
                        {{ $categories->where('is_active', false)->count() }} Inactive
                    </span>
                </div>
            </div>
        </div>
        
        <div class="content-card-body">
            <div class="table-responsive">
                <table class="compact-table" id="categoriesTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Order</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $index => $category)
                        <tr class="{{ !$category->is_active ? 'inactive-row' : '' }}">
                            <td>
                                <span class="row-number">{{ $index + 1 }}</span>
                            </td>
                            <td>
                                <div class="category-info">
                                    <div class="category-name">{{ $category->name }}</div>
                                </div>
                            </td>
                            <td>
                                <div class="category-slug">{{ $category->slug }}</div>
                            </td>
                            <td>
                                <span class="order-badge">{{ $category->order }}</span>
                            </td>
                            <td>
                                @if($category->is_active)
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
                                <div class="action-buttons">
                                    <button onclick="openEditModal({{ $category->id }}, '{{ addslashes($category->name) }}', '{{ $category->order }}', {{ $category->is_active ? 'true' : 'false' }})" 
                                       class="action-btn edit-btn" 
                                       title="Edit Category">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="action-btn delete-btn" 
                                            onclick="deleteCategory({{ $category->id }}, '{{ addslashes($category->name) }}')"
                                            title="Delete Category">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="empty-state">
                                <div class="empty-content">
                                    <i class="fas fa-folder"></i>
                                    <h6>No Categories Found</h6>
                                    <p>No FAQ categories have been created yet.</p>
                                    <button onclick="openCreateModal()" 
                                            class="btn btn-success btn-sm mt-2">
                                        <i class="fas fa-plus me-1"></i>
                                        Create First Category
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
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
                    Delete Category
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <i class="fas fa-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">Are you sure?</h5>
                    <p class="text-muted">You are about to delete the category:</p>
                    <strong id="categoryName" class="text-danger"></strong>
                    <div class="alert alert-danger mt-3">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Warning:</strong> Any FAQs in this category will also be removed.
                    </div>
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
                        <i class="fas fa-trash me-2"></i>Delete Category
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Create/Edit Category Modal --}}
<div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: var(--secondary); color: white;">
                <h5 class="modal-title" id="categoryModalLabel">
                    <i class="fas fa-plus me-2"></i>
                    Add New Category
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="categoryForm" method="POST">
                @csrf
                <div id="formMethod"></div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="name" class="form-label required">
                                    <i class="fas fa-tag me-1"></i>
                                    Category Name
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="name" 
                                       name="name" 
                                       required
                                       placeholder="Enter category name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="order" class="form-label">
                                    <i class="fas fa-sort-numeric-up me-1"></i>
                                    Display Order
                                </label>
                                <input type="number" 
                                       class="form-control" 
                                       id="order" 
                                       name="order" 
                                       value="0"
                                       min="0"
                                       placeholder="0">
                                <small class="form-text text-muted">Lower numbers appear first</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-toggle-on me-1"></i>
                                    Status
                                </label>
                                <div class="form-check form-switch">
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox" 
                                           name="is_active" 
                                           id="is_active" 
                                           class="form-check-input" 
                                           value="1"
                                           checked>
                                    <label for="is_active" class="form-check-label">
                                        Active Category
                                    </label>
                                    <small class="form-text text-muted d-block mt-1">
                                        Inactive categories will be hidden from public view
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
                    <button type="submit" class="btn btn-secondary" id="submitBtn">
                        <i class="fas fa-save me-2"></i>Save Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Updated CSS Variables for FAQ Categories */
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
.active-categories {
    background: #F0FDF4 !important;
    color: #166534 !important;
    border: 1px solid #BBF7D0;
    font-size: 0.7rem;
    padding: 0.3rem 0.6rem;
}

.inactive-categories {
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
    background: #FAFAFA !important;
    opacity: 0.7;
}

.inactive-row:hover {
    background: #F5F5F5 !important;
    opacity: 0.8;
}

/* Table Content Styling */
.row-number {
    font-family: 'Monaco', 'Menlo', monospace;
    background: var(--secondary);
    color: var(--white);
    padding: 0.2rem 0.4rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
}

.category-name {
    font-weight: 500;
    color: var(--gray-800);
    font-size: 0.85rem;
}

.category-slug {
    font-family: 'Monaco', 'Menlo', monospace;
    color: var(--gray-600);
    font-size: 0.8rem;
    background: var(--gray-100);
    padding: 0.2rem 0.4rem;
    border-radius: 4px;
}

.order-badge {
    background: var(--gray-100);
    color: var(--gray-700);
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-weight: 600;
    font-size: 0.75rem;
    border: 1px solid var(--gray-300);
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

/* Form Styling */
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
}

@media (max-width: 480px) {
    .category-name {
        font-size: 0.75rem;
    }
    
    .status-badge,
    .order-badge {
        font-size: 0.7rem;
        padding: 0.2rem 0.4rem;
    }
    
    .action-buttons {
        flex-direction: column;
        gap: 0.15rem;
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
    const refreshBtn = document.getElementById('refreshCategories');
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
    
    // Initialize DataTable if available
    if (typeof DataTable !== 'undefined' && document.getElementById('categoriesTable')) {
        new DataTable('#categoriesTable', {
            responsive: true,
            pageLength: 25,
            order: [[3, 'asc']], // Sort by order column
            columnDefs: [
                { orderable: false, targets: [5] } // Disable sorting on Actions column
            ]
        });
    }
});

// Open create modal
function openCreateModal() {
    document.getElementById('categoryModalLabel').innerHTML = '<i class="fas fa-plus me-2"></i>Add New Category';
    document.getElementById('categoryForm').action = '{{ route("faq-categories.store") }}';
    document.getElementById('formMethod').innerHTML = '';
    document.getElementById('name').value = '';
    document.getElementById('order').value = '0';
    document.getElementById('is_active').checked = true;
    
    const modal = new bootstrap.Modal(document.getElementById('categoryModal'));
    modal.show();
}

// Open edit modal
function openEditModal(id, name, order, isActive) {
    document.getElementById('categoryModalLabel').innerHTML = '<i class="fas fa-edit me-2"></i>Edit Category';
    document.getElementById('categoryForm').action = `/admin/faq-categories/${id}`;
    document.getElementById('formMethod').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    document.getElementById('name').value = name;
    document.getElementById('order').value = order;
    document.getElementById('is_active').checked = isActive;
    
    const modal = new bootstrap.Modal(document.getElementById('categoryModal'));
    modal.show();
}

// Delete category functionality with toastr
function deleteCategory(categoryId, categoryName) {
    document.getElementById('categoryName').textContent = categoryName;
    document.getElementById('deleteForm').action = `/admin/faq-categories/${categoryId}`;
    
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
                toastr.success('Category deleted successfully!', 'Success!', {
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
                toastr.error('Error deleting category. Please try again.', 'Error!', {
                    closeButton: true,
                    progressBar: true,
                    timeOut: 5000,
                    positionClass: 'toast-top-right'
                });
            }
        })
        .catch(error => {
            modal.hide();
            toastr.error('Error deleting category. Please try again.', 'Error!', {
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

// Handle category form submission with toastr
document.getElementById('categoryForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = document.getElementById('submitBtn');
    const originalContent = submitBtn.innerHTML;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';
    submitBtn.disabled = true;
    
    const formData = new FormData(this);
    const method = this.querySelector('input[name="_method"]') ? 'PUT' : 'POST';
    
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
            const modal = bootstrap.Modal.getInstance(document.getElementById('categoryModal'));
            modal.hide();
            
            toastr.success(data.message || 'Category saved successfully!', 'Success!', {
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
            toastr.error(data.message || 'Error saving category.', 'Error!', {
                closeButton: true,
                progressBar: true,
                timeOut: 5000,
                positionClass: 'toast-top-right'
            });
        }
    })
    .catch(error => {
        toastr.error('Error saving category. Please try again.', 'Error!', {
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
</script>
@endpush
