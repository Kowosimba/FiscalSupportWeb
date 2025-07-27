@extends('layouts.app')

@section('title', isset($faq) ? 'Edit FAQ' : 'Create New FAQ')

@section('content')
<div class="dashboard-container">
    {{-- Compact Header --}}
    <div class="dashboard-header mb-2">
        <div class="header-content">
            <h1 class="dashboard-title">
                <i class="fas fa-{{ isset($faq) ? 'edit' : 'plus' }} me-2"></i>
                {{ isset($faq) ? 'Edit FAQ' : 'Create New FAQ' }}
            </h1>
            <div class="header-meta">
                <small class="text-muted">
                    {{ isset($faq) ? 'Update FAQ information and content' : 'Add a new frequently asked question' }}
                </small>
            </div>
        </div>
        <div class="header-actions">
            <button onclick="window.location.href='{{ route('admin.faqs.index') }}'" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>
                Back to FAQs
            </button>
        </div>
    </div>

    {{-- Validation Errors (only show validation errors, not success messages) --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-2">
            <i class="fas fa-exclamation-circle me-2"></i>
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- FAQ Form --}}
    <form action="{{ isset($faq) ? route('admin.faqs.update', $faq->id) : route('admin.faqs.store') }}" 
          method="POST" id="faqForm">
        @csrf
        @if(isset($faq)) @method('PUT') @endif

        <div class="row g-3">
            {{-- Main Content --}}
            <div class="col-lg-8">
                <div class="content-card">
                    <div class="content-card-header">
                        <div class="header-content">
                            <h4 class="card-title">
                                <i class="fas fa-question-circle me-2"></i>
                                FAQ Content
                            </h4>
                            <p class="card-subtitle mb-0">
                                Enter the question and answer details
                            </p>
                        </div>
                    </div>
                    
                    <div class="content-card-body" style="padding: 1.5rem;">
                        <div class="row g-4">
                            {{-- Question --}}
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="question" class="form-label required">
                                        <i class="fas fa-question me-1"></i>
                                        Question
                                    </label>
                                    <input type="text" 
                                           name="question" 
                                           id="question"
                                           class="form-control @error('question') is-invalid @enderror" 
                                           value="{{ old('question', $faq->question ?? '') }}" 
                                           required 
                                           placeholder="Enter the frequently asked question">
                                    @error('question')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Answer --}}
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="answer" class="form-label required">
                                        <i class="fas fa-comment-alt me-1"></i>
                                        Answer
                                    </label>
                                    <textarea name="answer" 
                                              id="answer"
                                              class="form-control @error('answer') is-invalid @enderror" 
                                              rows="6" 
                                              required
                                              placeholder="Provide a detailed answer to the question">{{ old('answer', $faq->answer ?? '') }}</textarea>
                                    <small class="form-text text-muted">You can use HTML tags for formatting</small>
                                    @error('answer')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4">
                {{-- Category Settings --}}
                <div class="content-card mb-3">
                    <div class="content-card-header">
                        <div class="header-content">
                            <h4 class="card-title">
                                <i class="fas fa-tag me-2"></i>
                                Category Settings
                            </h4>
                        </div>
                    </div>
                    
                    <div class="content-card-body" style="padding: 1.5rem;">
                        <div class="form-group">
                            <label for="category_selection" class="form-label required">
                                <i class="fas fa-folder me-1"></i>
                                Category
                            </label>
                            
                            {{-- Existing Category Selection --}}
                            <div class="category-wrapper">
                                <select name="faq_category_id" id="faq_category_id" 
                                        class="form-select @error('faq_category_id') is-invalid @enderror mb-2">
                                    <option value="">Select existing category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" 
                                                @selected(old('faq_category_id', $faq->category_id ?? '') == $category->id)>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                
                                <div class="text-center my-2">
                                    <small class="text-muted">OR</small>
                                </div>
                                
                                {{-- New Category Creation --}}
                                <div class="new-category-section">
                                    <div class="input-group">
                                        <input type="text" 
                                               class="form-control" 
                                               id="new_category_name" 
                                               name="new_category_name"
                                               placeholder="Enter new category name">
                                        <button type="button" 
                                                class="btn btn-outline-secondary" 
                                                id="create_category_btn">
                                            <i class="fas fa-plus me-1"></i>Create
                                        </button>
                                    </div>
                                    <small class="form-text text-muted mt-1">Create a new category if it doesn't exist above</small>
                                </div>
                                @error('faq_category_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Display Settings --}}
                <div class="content-card mb-3">
                    <div class="content-card-header">
                        <div class="header-content">
                            <h4 class="card-title">
                                <i class="fas fa-cog me-2"></i>
                                Display Settings
                            </h4>
                        </div>
                    </div>
                    
                    <div class="content-card-body" style="padding: 1.5rem;">
                        <div class="row g-3">
                            {{-- Status --}}
                            <div class="col-12">
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
                                               @checked(old('is_active', $faq->is_active ?? '1') == '1')>
                                        <label for="is_active" class="form-check-label">
                                            Active FAQ
                                        </label>
                                        <small class="form-text text-muted d-block mt-1">
                                            Inactive FAQs will be hidden from public view
                                        </small>
                                    </div>
                                </div>
                            </div>

                            {{-- Display Order --}}
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="order" class="form-label">
                                        <i class="fas fa-sort-numeric-up me-1"></i>
                                        Display Order
                                    </label>
                                    <input type="number" 
                                           name="order" 
                                           id="order"
                                           class="form-control @error('order') is-invalid @enderror" 
                                           value="{{ old('order', $faq->order ?? '0') }}"
                                           min="0"
                                           placeholder="0">
                                    <small class="form-text text-muted">Lower numbers appear first</small>
                                    @error('order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="content-card">
                    <div class="content-card-body" style="padding: 1.5rem;">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-secondary" id="submitBtn">
                                <i class="fas fa-save me-2"></i>
                                {{ isset($faq) ? 'Update FAQ' : 'Create FAQ' }}
                            </button>
                            
                            @if(isset($faq))
                                <button type="button" 
                                        onclick="window.location.href='{{ route('admin.faqs.index') }}'" 
                                        class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-2"></i>
                                    Cancel
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

{{-- Success Message Template (for AJAX responses) --}}
<div id="success-template" class="d-none">
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle me-2"></i>
        <span class="message-text"></span>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Form Enhancement Styles */
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

.form-control, .form-select {
    border: 1px solid var(--gray-300);
    border-radius: var(--border-radius);
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
    transition: var(--transition);
}

.form-control:focus, .form-select:focus {
    border-color: var(--secondary);
    box-shadow: 0 0 0 2px rgba(107, 114, 128, 0.1);
}

.form-control.is-invalid, .form-select.is-invalid {
    border-color: var(--danger);
}

.form-control.is-invalid:focus, .form-select.is-invalid:focus {
    border-color: var(--danger);
    box-shadow: 0 0 0 2px rgba(220, 38, 38, 0.1);
}

.invalid-feedback {
    color: var(--danger);
    font-size: 0.75rem;
    margin-top: 0.25rem;
}

/* Category Wrapper */
.category-wrapper {
    background: var(--gray-50);
    border: 1px solid var(--gray-200);
    border-radius: var(--border-radius);
    padding: 1rem;
}

.new-category-section .input-group {
    border-radius: var(--border-radius);
    overflow: hidden;
}

.new-category-section .form-control {
    border-right: none;
}

.new-category-section .btn {
    border-left: none;
    white-space: nowrap;
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
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('faqForm');
    const submitBtn = document.getElementById('submitBtn');
    
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
    
    // Handle form submission with loading state
    form.addEventListener('submit', function() {
        const originalContent = submitBtn.innerHTML;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';
        submitBtn.disabled = true;
        
        // Re-enable if form validation fails (client-side)
        setTimeout(() => {
            if (submitBtn.disabled) {
                submitBtn.innerHTML = originalContent;
                submitBtn.disabled = false;
            }
        }, 3000);
    });
    
    // Category selection logic
    const categorySelect = document.getElementById('faq_category_id');
    const newCategoryInput = document.getElementById('new_category_name');
    const createCategoryBtn = document.getElementById('create_category_btn');
    
    categorySelect.addEventListener('change', function() {
        if (this.value) {
            newCategoryInput.value = '';
            newCategoryInput.disabled = true;
            createCategoryBtn.disabled = true;
        } else {
            newCategoryInput.disabled = false;
            createCategoryBtn.disabled = false;
        }
    });
    
    newCategoryInput.addEventListener('input', function() {
        if (this.value.trim()) {
            categorySelect.value = '';
            categorySelect.disabled = true;
        } else {
            categorySelect.disabled = false;
        }
    });
    
    // Create category via AJAX
    createCategoryBtn.addEventListener('click', function() {
        const categoryName = newCategoryInput.value.trim();
        
        if (!categoryName) {
            toastr.error('Please enter a category name', 'Validation Error!', {
                closeButton: true,
                progressBar: true,
                timeOut: 3000,
                positionClass: 'toast-top-right'
            });
            return;
        }
        
        // Show loading state
        const originalContent = this.innerHTML;
        this.disabled = true;
        this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Creating...';
        
        // Make AJAX request
        fetch('{{ route("admin.faq-categories.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                name: categoryName
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success || data.id) {
                // Add new option to select
                const option = document.createElement('option');
                option.value = data.id;
                option.textContent = data.name;
                option.selected = true;
                categorySelect.appendChild(option);
                
                // Clear and disable new category input
                newCategoryInput.value = '';
                newCategoryInput.disabled = true;
                
                // Show success toastr
                toastr.success('Category created successfully!', 'Success!', {
                    closeButton: true,
                    progressBar: true,
                    timeOut: 3000,
                    positionClass: 'toast-top-right'
                });
                
            } else {
                throw new Error(data.message || 'Failed to create category');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            toastr.error(error.message || 'Failed to create category', 'Error!', {
                closeButton: true,
                progressBar: true,
                timeOut: 5000,
                positionClass: 'toast-top-right'
            });
        })
        .finally(() => {
            // Restore button state
            this.disabled = false;
            this.innerHTML = originalContent;
        });
    });
    
    // Initialize form state
    if (categorySelect.value) {
        newCategoryInput.disabled = true;
        createCategoryBtn.disabled = true;
    }
    
    // Auto-focus first input
    const firstInput = document.getElementById('question');
    if (firstInput) {
        firstInput.focus();
    }
});
</script>
@endpush

