@extends('layouts.app')

@section('title', isset($contact) ? 'Edit Contact' : 'Add New Contact')

@section('content')
<div class="dashboard-container">
    {{-- Compact Header --}}
    <div class="dashboard-header mb-2">
        <div class="header-content">
            <h1 class="dashboard-title">
                <i class="fas fa-{{ isset($contact) ? 'user-edit' : 'user-plus' }} me-2"></i>
                {{ isset($contact) ? 'Edit Contact' : 'Add New Contact' }}
            </h1>
            <div class="header-meta">
                <small class="text-muted">
                    {{ isset($contact) ? 'Modify contact information' : 'Create a new customer contact' }}
                </small>
            </div>
        </div>
        <div class="header-actions">
            <button onclick="window.location.href='{{ route('admin.contacts.index') }}'" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>
                Back to Contacts
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

    {{-- Contact Form --}}
    <div class="content-card">
        <div class="content-card-header">
            <div class="header-content">
                <h4 class="card-title">
                    <i class="fas fa-id-card me-2"></i>
                    Contact Information
                </h4>
                <p class="card-subtitle mb-0">
                    {{ isset($contact) ? 'Update the contact details below' : 'Fill in the contact details below' }}
                </p>
            </div>
        </div>
        
        <div class="content-card-body" style="padding: 1.5rem;">
            <form action="{{ isset($contact) ? route('admin.contacts.update', $contact) : route('admin.contacts.store') }}" 
                  method="POST" id="contactForm">
                @csrf
                @if(isset($contact)) 
                    @method('PUT') 
                @endif

                <div class="row g-4">
                    {{-- Name Field --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name" class="form-label required">
                                <i class="fas fa-user me-1"></i>
                                Full Name
                            </label>
                            <input type="text" 
                                   name="name" 
                                   id="name"
                                   class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name', $contact->name ?? '') }}" 
                                   required 
                                   placeholder="Enter full name">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Email Field --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope me-1"></i>
                                Email Address
                            </label>
                            <input type="email" 
                                   name="email" 
                                   id="email"
                                   class="form-control @error('email') is-invalid @enderror" 
                                   value="{{ old('email', $contact->email ?? '') }}" 
                                   placeholder="Enter email address">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Phone Field --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="phone" class="form-label">
                                <i class="fas fa-phone me-1"></i>
                                Phone Number
                            </label>
                            <input type="text" 
                                   name="phone" 
                                   id="phone"
                                   class="form-control @error('phone') is-invalid @enderror" 
                                   value="{{ old('phone', $contact->phone ?? '') }}" 
                                   placeholder="Enter phone number">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Company Field --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="company" class="form-label">
                                <i class="fas fa-building me-1"></i>
                                Company
                            </label>
                            <input type="text" 
                                   name="company" 
                                   id="company"
                                   class="form-control @error('company') is-invalid @enderror" 
                                   value="{{ old('company', $contact->company ?? '') }}" 
                                   placeholder="Enter company name">
                            @error('company')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Position Field --}}
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="position" class="form-label">
                                <i class="fas fa-briefcase me-1"></i>
                                Position/Title
                            </label>
                            <input type="text" 
                                   name="position" 
                                   id="position"
                                   class="form-control @error('position') is-invalid @enderror" 
                                   value="{{ old('position', $contact->position ?? '') }}" 
                                   placeholder="Enter job title or position">
                            @error('position')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Notes Field --}}
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="notes" class="form-label">
                                <i class="fas fa-sticky-note me-1"></i>
                                Notes
                            </label>
                            <textarea name="notes" 
                                      id="notes"
                                      class="form-control @error('notes') is-invalid @enderror" 
                                      rows="4" 
                                      placeholder="Enter any additional notes or comments about this contact">{{ old('notes', $contact->notes ?? '') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Active Status --}}
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="form-check form-switch">
                                <input type="checkbox" 
                                       name="is_active" 
                                       id="is_active" 
                                       class="form-check-input" 
                                       value="1"
                                       {{ old('is_active', $contact->is_active ?? true) ? 'checked' : '' }}>
                                <label for="is_active" class="form-check-label">
                                    <i class="fas fa-toggle-on me-1"></i>
                                    Active Contact
                                </label>
                                <small class="form-text text-muted d-block mt-1">
                                    Inactive contacts will be hidden from most lists but can be reactivated
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Form Actions --}}
                <div class="form-actions mt-4 pt-3" style="border-top: 1px solid var(--gray-200);">
                    <div class="d-flex gap-3 justify-content-end">
                        <button type="button" 
                                onclick="window.location.href='{{ route('admin.contacts.index') }}'" 
                                class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-secondary" id="submitBtn">
                            <i class="fas fa-save me-2"></i>
                            {{ isset($contact) ? 'Update Contact' : 'Save Contact' }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
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
    display: flex;
    align-items: center;
}

.form-check-label i {
    color: var(--secondary);
    width: 16px;
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
    
    .form-actions .d-flex {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .form-actions .btn {
        width: 100%;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contactForm');
    const submitBtn = document.getElementById('submitBtn');
    
    // Check for success message in session and show toastr
    @if(session('success'))
        toastr.success("{{ session('success') }}", "Success!", {
            closeButton: true,
            progressBar: true,
            timeOut: 5000,
            extendedTimeOut: 2000,
            positionClass: 'toast-top-right'
        });
    @endif
    
    // Check for error message in session and show toastr
    @if(session('error'))
        toastr.error("{{ session('error') }}", "Error!", {
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
    
    // Auto-focus first input
    const firstInput = document.getElementById('name');
    if (firstInput) {
        firstInput.focus();
    }
});
</script>
@endpush
