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
            <button onclick="window.location.href='{{ route('admin.tickets.unassigned') }}'" class="btn btn-sm btn-outline-secondary" type="button" aria-label="Back to tickets">
                <i class="fas fa-arrow-left me-1"></i>
                Back to Tickets
            </button>
        </div>
    </div>

    {{-- Validation Errors --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-2" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-2" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-2" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
            <form action="{{ route('admin.tickets.store') }}" method="POST" enctype="multipart/form-data" id="adminTicketForm" novalidate>
                @csrf

                <div class="row g-4">
                    {{-- Company Name --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="company_name" class="form-label required">
                                <i class="fas fa-building me-1"></i> Company Name
                            </label>
                            <input type="text"
                                name="company_name"
                                id="company_name"
                                class="form-control @error('company_name') is-invalid @enderror"
                                value="{{ old('company_name') }}"
                                required
                                placeholder="Enter company name"
                                aria-describedby="companyNameHelp">
                            @error('company_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email" class="form-label required">
                                <i class="fas fa-envelope me-1"></i> Email Address
                            </label>
                            <input type="email"
                                name="email"
                                id="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}"
                                required
                                placeholder="Enter email address"
                                aria-describedby="emailHelp">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Contact Details --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="contact_details" class="form-label">
                                <i class="fas fa-phone me-1"></i> Contact Details
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
                                <i class="fas fa-cogs me-1"></i> Service Needed
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
                                <i class="fas fa-heading me-1"></i> Subject
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
                                <i class="fas fa-comment-alt me-1"></i> Description
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
                                <i class="fas fa-paperclip me-1"></i> Attachment (Optional)
                            </label>
                            <div class="file-input-wrapper">
                                <div class="file-input-button" tabindex="0" role="button"
                                    aria-label="Upload attachment, click or drag and drop"
                                    onclick="document.getElementById('attachment').click()"
                                    onkeypress="if(event.key==='Enter'){ document.getElementById('attachment').click(); }">
                                    <i class="fas fa-cloud-upload-alt me-2"></i>
                                    <span>Click to upload or drag and drop</span>
                                    <div class="file-name" id="fileName">No file selected</div>
                                </div>
                                <input type="file" name="attachment" id="attachment" class="file-input" accept=".pdf,.jpg,.png,.jpeg" aria-describedby="attachmentHelp">
                            </div>
                            <small class="form-text text-muted" id="attachmentHelp">Max file size: 5MB (PDF, JPG, PNG)</small>
                            @error('attachment')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Priority --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="priority" class="form-label required">
                                <i class="fas fa-exclamation-triangle me-1"></i> Priority
                            </label>
                            <select name="priority" id="priority" class="form-select @error('priority') is-invalid @enderror" required>
                                <option value="low" @selected(old('priority') == 'low')>Low</option>
                                <option value="medium" @selected(old('priority') == 'medium')>Medium</option>
                                <option value="high" @selected(old('priority') == 'high')>High</option>
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
                                <i class="fas fa-user-cog me-1"></i> Assign Technician
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
                    <div class="d-flex gap-3 justify-content-end flex-wrap">
                        <button type="button" onclick="resetForm()" class="btn btn-outline-secondary" aria-label="Reset the form">
                            <i class="fas fa-undo me-2"></i> Reset Form
                        </button>
                        <button type="submit" class="btn btn-secondary" id="submitTicket" aria-live="polite" aria-busy="false">
                            <i class="fas fa-ticket-alt me-2"></i> Create Ticket
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
/* -- Your original CSS here -- */
/* Keep your styles as they are, no changes needed */
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('attachment');
    const fileNameDisplay = document.getElementById('fileName');
    const fileInputWrapper = document.querySelector('.file-input-wrapper');

    fileInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            fileNameDisplay.textContent = this.files[0].name;
            fileNameDisplay.style.color = 'var(--gray-800)';
            toastr.success(`File "${this.files[0].name}" selected successfully.`, 'File Selected', {
                closeButton: true,
                progressBar: true,
                timeOut: 2000,
                positionClass: 'toast-top-right'
            });
        } else {
            fileNameDisplay.textContent = 'No file selected';
            fileNameDisplay.style.color = 'var(--gray-500)';
        }
    });

    fileInputWrapper.addEventListener('dragover', (e) => {
        e.preventDefault();
        const btn = fileInputWrapper.querySelector('.file-input-button');
        btn.style.borderColor = 'var(--secondary)';
        btn.style.backgroundColor = 'rgba(107, 114, 128, 0.1)';
    });

    fileInputWrapper.addEventListener('dragleave', (e) => {
        e.preventDefault();
        const btn = fileInputWrapper.querySelector('.file-input-button');
        btn.style.borderColor = 'var(--gray-300)';
        btn.style.backgroundColor = 'var(--gray-50)';
    });

    fileInputWrapper.addEventListener('drop', (e) => {
        e.preventDefault();
        const btn = fileInputWrapper.querySelector('.file-input-button');
        btn.style.borderColor = 'var(--gray-300)';
        btn.style.backgroundColor = 'var(--gray-50)';
        if (e.dataTransfer.files.length) {
            fileInput.files = e.dataTransfer.files;
            fileNameDisplay.textContent = fileInput.files[0].name;
            fileNameDisplay.style.color = 'var(--gray-800)';
            toastr.success(`File "${fileInput.files[0].name}" dropped successfully.`, 'File Dropped', {
                closeButton: true,
                progressBar: true,
                timeOut: 2000,
                positionClass: 'toast-top-right'
            });
        }
    });

    // Auto-focus on the first input
    const firstInput = document.getElementById('company_name');
    if (firstInput) firstInput.focus();
});

// Reset form function
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

    toastr.info('All form fields have been cleared.', 'Form Reset', {
        closeButton: true,
        progressBar: true,
        timeOut: 2000,
        positionClass: 'toast-top-right'
    });
}
</script>
@endpush
