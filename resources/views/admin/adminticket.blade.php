@extends('layouts.app')

@section('title', 'Create New Support Ticket')

@section('content')
<div class="dashboard-container">
    {{-- Compact Header --}}
    <div class="dashboard-header mb-2">
        <div class="header-content">
            <h1 class="dashboard-title">
                <i class="fas fa-ticket-alt me-2"></i>
                Create New Support Ticket
            </h1>
            <div class="header-meta">
                <small class="text-muted">Create a new support ticket for a customer</small>
            </div>
        </div>
        <div class="header-actions">
            <button onclick="window.location.href='{{ route('admin.tickets.unassigned') }}'" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>
                Back to Tickets
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

    {{-- Ticket Form --}}
    <div class="content-card">
        <div class="content-card-header">
            <div class="header-content">
                <h4 class="card-title">
                    <i class="fas fa-edit me-2"></i>
                    Ticket Information
                </h4>
                <p class="card-subtitle mb-0">
                    Fill in the ticket details below
                </p>
            </div>
        </div>
        
        <div class="content-card-body" style="padding: 1.5rem;">
            <form action="{{ route('admin.tickets.store') }}" method="POST" enctype="multipart/form-data" id="adminTicketForm">
                @csrf

                <div class="row g-4">
                    {{-- Company Name --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="company_name" class="form-label required">
                                <i class="fas fa-building me-1"></i>
                                Company Name
                            </label>
                            <input type="text" 
                                   name="company_name" 
                                   id="company_name"
                                   class="form-control @error('company_name') is-invalid @enderror" 
                                   value="{{ old('company_name') }}" 
                                   required 
                                   placeholder="Enter company name">
                            @error('company_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email" class="form-label required">
                                <i class="fas fa-envelope me-1"></i>
                                Email Address
                            </label>
                            <input type="email" 
                                   name="email" 
                                   id="email"
                                   class="form-control @error('email') is-invalid @enderror" 
                                   value="{{ old('email') }}" 
                                   required 
                                   placeholder="Enter email address">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Contact Details --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="contact_details" class="form-label">
                                <i class="fas fa-phone me-1"></i>
                                Contact Details
                            </label>
                            <input type="text" 
                                   name="contact_details" 
                                   id="contact_details"
                                   class="form-control @error('contact_details') is-invalid @enderror" 
                                   value="{{ old('contact_details') }}" 
                                   placeholder="Phone, WhatsApp, etc.">
                            @error('contact_details')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Service --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="service" class="form-label required">
                                <i class="fas fa-cogs me-1"></i>
                                Service Needed
                            </label>
                            <select name="service" id="service" class="form-select @error('service') is-invalid @enderror" required>
                                <option value="" disabled selected>Select Service</option>
                                <option value="Fiscal Device Setup" @selected(old('service') == 'Fiscal Device Setup')>Fiscal Device Setup</option>
                                <option value="Technical Support" @selected(old('service') == 'Technical Support')>Technical Support</option>
                                <option value="Billing Inquiry" @selected(old('service') == 'Billing Inquiry')>Billing Inquiry</option>
                                <option value="Software Update" @selected(old('service') == 'Software Update')>Software Update</option>
                                <option value="Other" @selected(old('service') == 'Other')>Other</option>
                            </select>
                            @error('service')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Subject --}}
                    <div class="col-12">
                        <div class="form-group">
                            <label for="subject" class="form-label required">
                                <i class="fas fa-heading me-1"></i>
                                Subject
                            </label>
                            <input type="text" 
                                   name="subject" 
                                   id="subject"
                                   class="form-control @error('subject') is-invalid @enderror" 
                                   value="{{ old('subject') }}" 
                                   required 
                                   placeholder="Brief description of the issue">
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Message --}}
                    <div class="col-12">
                        <div class="form-group">
                            <label for="message" class="form-label required">
                                <i class="fas fa-comment-alt me-1"></i>
                                Description
                            </label>
                            <textarea name="message" 
                                      id="message"
                                      class="form-control @error('message') is-invalid @enderror" 
                                      rows="4" 
                                      required
                                      placeholder="Describe the issue in detail">{{ old('message') }}</textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Attachment --}}
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-paperclip me-1"></i>
                                Attachment (Optional)
                            </label>
                            <div class="file-input-wrapper">
                                <div class="file-input-button" onclick="document.getElementById('attachment').click()">
                                    <i class="fas fa-cloud-upload-alt me-2"></i>
                                    <span>Click to upload or drag and drop</span>
                                    <div class="file-name" id="fileName">No file selected</div>
                                </div>
                                <input type="file" name="attachment" id="attachment" class="file-input" accept=".pdf,.jpg,.png,.jpeg">
                            </div>
                            <small class="form-text text-muted">Max file size: 5MB (PDF, JPG, PNG)</small>
                            @error('attachment')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Priority --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="priority" class="form-label required">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                Priority
                            </label>
                            <select name="priority" id="priority" class="form-select @error('priority') is-invalid @enderror" required>
                                <option value="low" @selected(old('priority') == 'low')>
                                    <i class="fas fa-circle me-1"></i> Low
                                </option>
                                <option value="medium" @selected(old('priority') == 'medium')>
                                    <i class="fas fa-minus-circle me-1"></i> Medium
                                </option>
                                <option value="high" @selected(old('priority') == 'high')>
                                    <i class="fas fa-exclamation-triangle me-1"></i> High
                                </option>
                            </select>
                            @error('priority')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Assigned Technician --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="assigned_to" class="form-label">
                                <i class="fas fa-user-cog me-1"></i>
                                Assign Technician
                            </label>
                            <select name="assigned_to" id="assigned_to" class="form-select @error('assigned_to') is-invalid @enderror">
                                <option value="" selected>Unassigned</option>
                                @foreach($technicians as $tech)
                                    <option value="{{ $tech->id }}" @selected(old('assigned_to') == $tech->id)>
                                        {{ $tech->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('assigned_to')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Form Actions --}}
                <div class="form-actions mt-4 pt-3" style="border-top: 1px solid var(--gray-200);">
                    <div class="d-flex gap-3 justify-content-end">
                        <button type="button" 
                                onclick="resetForm()" 
                                class="btn btn-outline-secondary">
                            <i class="fas fa-undo me-2"></i>
                            Reset Form
                        </button>
                        <button type="submit" class="btn btn-secondary" id="submitTicket">
                            <i class="fas fa-ticket-alt me-2"></i>
                            Create Ticket
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

/* File Input Styles */
.file-input-wrapper {
    position: relative;
    overflow: hidden;
    display: inline-block;
    width: 100%;
}

.file-input-button {
    border: 2px dashed var(--gray-300);
    border-radius: var(--border-radius);
    padding: 1.5rem;
    text-align: center;
    cursor: pointer;
    transition: var(--transition);
    width: 100%;
    background-color: var(--gray-50);
}

.file-input-button:hover {
    border-color: var(--secondary);
    background-color: rgba(107, 114, 128, 0.05);
}

.file-input {
    position: absolute;
    left: 0;
    top: 0;
    opacity: 0;
    width: 100%;
    height: 100%;
    cursor: pointer;
}

.file-name {
    margin-top: 0.5rem;
    font-size: 0.875rem;
    color: var(--gray-500);
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
    const form = document.getElementById('adminTicketForm');
    const submitBtn = document.getElementById('submitTicket');
    
    // Handle form submission with toastr feedback
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Form validation
        if (!form.checkValidity()) {
            e.stopPropagation();
            form.classList.add('was-validated');
            
            toastr.error('Please fill in all required fields correctly.', 'Validation Error!', {
                closeButton: true,
                progressBar: true,
                timeOut: 5000,
                positionClass: 'toast-top-right'
            });
            return;
        }
        
        // Show loading state
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Creating Ticket...';
        
        // Submit the form via AJAX
        const formData = new FormData(form);
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success toastr
                toastr.success(`Ticket #${data.ticket.id} has been created successfully!`, 'Success!', {
                    closeButton: true,
                    progressBar: true,
                    timeOut: 3000,
                    positionClass: 'toast-top-right',
                    onHidden: function() {
                        // Redirect after toastr is hidden
                        window.location.href = "{{ route('admin.tickets.unassigned') }}";
                    }
                });
                
            } else {
                // Show error toastr
                toastr.error(data.message || 'Something went wrong. Please try again.', 'Error!', {
                    closeButton: true,
                    progressBar: true,
                    timeOut: 5000,
                    positionClass: 'toast-top-right'
                });
                
                // Reset button
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            
            // Show error toastr
            toastr.error('Something went wrong. Please try again.', 'Error!', {
                closeButton: true,
                progressBar: true,
                timeOut: 5000,
                positionClass: 'toast-top-right'
            });
            
            // Reset button
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    });
    
    // Auto-focus first input
    const firstInput = document.getElementById('company_name');
    if (firstInput) {
        firstInput.focus();
    }
});

function resetForm() {
    const form = document.getElementById('adminTicketForm');
    if (form) {
        form.reset();
        form.classList.remove('was-validated');
    }
    
    const fileName = document.getElementById('fileName');
    if (fileName) {
        fileName.textContent = 'No file selected';
        fileName.style.color = 'var(--gray-500)';
    }
    
    // Show confirmation toastr
    toastr.info('All form fields have been cleared.', 'Form Reset', {
        closeButton: true,
        progressBar: true,
        timeOut: 2000,
        positionClass: 'toast-top-right'
    });
}

// File input handling (same as before)
const fileInput = document.getElementById('attachment');
if (fileInput) {
    fileInput.addEventListener('change', function() {
        const fileName = document.getElementById('fileName');
        if (this.files.length > 0) {
            fileName.textContent = this.files[0].name;
            fileName.style.color = 'var(--gray-800)';
            
            // Show file selected toastr
            toastr.success(`File "${this.files[0].name}" selected successfully.`, 'File Selected', {
                closeButton: true,
                progressBar: true,
                timeOut: 2000,
                positionClass: 'toast-top-right'
            });
        } else {
            fileName.textContent = 'No file selected';
            fileName.style.color = 'var(--gray-500)';
        }
    });

    // Drag and drop functionality (same as before but with toastr feedback)
    const fileInputWrapper = document.querySelector('.file-input-wrapper');
    
    fileInputWrapper.addEventListener('dragover', (e) => {
        e.preventDefault();
        const button = fileInputWrapper.querySelector('.file-input-button');
        button.style.borderColor = 'var(--secondary)';
        button.style.backgroundColor = 'rgba(107, 114, 128, 0.1)';
    });

    fileInputWrapper.addEventListener('dragleave', (e) => {
        e.preventDefault();
        const button = fileInputWrapper.querySelector('.file-input-button');
        button.style.borderColor = 'var(--gray-300)';
        button.style.backgroundColor = 'var(--gray-50)';
    });

    fileInputWrapper.addEventListener('drop', (e) => {
        e.preventDefault();
        const button = fileInputWrapper.querySelector('.file-input-button');
        button.style.borderColor = 'var(--gray-300)';
        button.style.backgroundColor = 'var(--gray-50)';
        
        if (e.dataTransfer.files.length) {
            fileInput.files = e.dataTransfer.files;
            const fileName = document.getElementById('fileName');
            fileName.textContent = fileInput.files[0].name;
            fileName.style.color = 'var(--gray-800)';
            
            // Show drag drop success toastr
            toastr.success(`File "${fileInput.files[0].name}" dropped successfully.`, 'File Dropped', {
                closeButton: true,
                progressBar: true,
                timeOut: 2000,
                positionClass: 'toast-top-right'
            });
        }
    });
}
</script>
@endpush
