@extends('layouts.tickets')

@section('ticket-content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0">Create New Support Ticket</h3>
    <a href="{{ route('admin.tickets.unassigned') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Tickets
    </a>
</div>

{{-- Error Messages --}}
@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show">
        <strong>Error!</strong> Please check the form for errors.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.tickets.store') }}" method="POST" enctype="multipart/form-data" id="adminTicketForm">
            @csrf
            <div class="row g-3">
                <!-- Company Name -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="company_name">Company Name</label>
                        <input type="text" class="form-control" name="company_name" id="company_name" 
                               placeholder="Company Name" value="{{ old('company_name') }}" required>
                        @error('company_name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Contact Details -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="contact_details">Contact Details</label>
                        <input type="text" class="form-control" name="contact_details" id="contact_details" 
                               placeholder="Phone, WhatsApp, etc." value="{{ old('contact_details') }}">
                        @error('contact_details')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Email -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" class="form-control" name="email" id="email" 
                               placeholder="Email Address" value="{{ old('email') }}" required>
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Service -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="service">Service Needed</label>
                        <select name="service" id="service" class="hidden-select" required>
                            <option value="" disabled selected>Select Service</option>
                            <option value="Fiscal Device Setup" {{ old('service') == 'Fiscal Device Setup' ? 'selected' : '' }}>Fiscal Device Setup</option>
                            <option value="Technical Support" {{ old('service') == 'Technical Support' ? 'selected' : '' }}>Technical Support</option>
                            <option value="Billing Inquiry" {{ old('service') == 'Billing Inquiry' ? 'selected' : '' }}>Billing Inquiry</option>
                            <option value="Software Update" {{ old('service') == 'Software Update' ? 'selected' : '' }}>Software Update</option>
                            <option value="Other" {{ old('service') == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                        <div class="custom-select" id="customSelect">
                            <div class="custom-select-trigger" id="customSelectTrigger">Select Service</div>
                            <div class="custom-options">
                                <div class="custom-option" data-value="Fiscal Device Setup">Fiscal Device Setup</div>
                                <div class="custom-option" data-value="Technical Support">Technical Support</div>
                                <div class="custom-option" data-value="Billing Inquiry">Billing Inquiry</div>
                                <div class="custom-option" data-value="Software Update">Software Update</div>
                                <div class="custom-option" data-value="Other">Other</div>
                            </div>
                        </div>
                        @error('service')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Subject -->
                <div class="col-12">
                    <div class="form-group">
                        <label for="subject">Subject</label>
                        <input type="text" class="form-control" name="subject" id="subject" 
                               placeholder="Brief description of the issue" value="{{ old('subject') }}" required>
                        @error('subject')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Message -->
                <div class="col-12">
                    <div class="form-group">
                        <label for="message">Description</label>
                        <textarea name="message" id="message" class="form-control" 
                                  placeholder="Describe the issue in detail" rows="4" required>{{ old('message') }}</textarea>
                        @error('message')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Attachment -->
                <div class="col-12">
                    <div class="form-group">
                        <label>Attachment (Optional)</label>
                        <div class="file-input-wrapper">
                            <div class="file-input-button">
                                <i class="fas fa-cloud-upload-alt me-2"></i>
                                <span>Click to upload or drag and drop</span>
                                <div class="file-name" id="fileName">No file selected</div>
                            </div>
                            <input type="file" name="attachment" id="attachment" class="file-input" accept=".pdf,.jpg,.png">
                        </div>
                        <small class="text-muted">Max file size: 5MB (PDF, JPG, PNG)</small>
                        @error('attachment')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Priority -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="priority">Priority</label>
                        <select name="priority" id="priority" class="form-control" required>
                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                        </select>
                        @error('priority')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Assigned Technician -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="assigned_to">Assign Technician</label>
                        <select name="assigned_to" id="assigned_to" class="form-control">
                            <option value="" selected>Unassigned</option>
                            @foreach($technicians as $tech)
                                <option value="{{ $tech->id }}" {{ old('assigned_to') == $tech->id ? 'selected' : '' }}>
                                    {{ $tech->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('assigned_to')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="col-12 d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-outline-secondary" onclick="resetForm()">Reset</button>
                    <button type="submit" class="btn btn-primary" id="submitTicket">Create Ticket</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Include SweetAlert --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    /* File Input Styles */
    .file-input-wrapper {
        position: relative;
        overflow: hidden;
        display: inline-block;
        width: 100%;
    }

    .file-input-button {
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        padding: 1.5rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 100%;
        background-color: #f8f9fa;
    }

    .file-input-button:hover {
        border-color: #4361ee;
        background-color: rgba(67, 97, 238, 0.05);
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
        color: #6c757d;
    }

    /* Custom Select Styles */
    .custom-select {
        position: relative;
        width: 100%;
    }

    .custom-select-trigger {
        padding: 0.75rem;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        background-color: white;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .custom-select-trigger::after {
        content: "▼";
        font-size: 0.75rem;
        transition: all 0.3s ease;
    }

    .custom-select.opened .custom-select-trigger::after {
        transform: rotate(180deg);
    }

    .custom-options {
        position: absolute;
        top: 100%;
        left: 0;
        width: 100%;
        background-color: white;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        z-index: 10;
        max-height: 200px;
        overflow-y: auto;
        display: none;
    }

    .custom-select.opened .custom-options {
        display: block;
    }

    .custom-option {
        padding: 0.75rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .custom-option:hover {
        background-color: #f8f9fa;
    }

    .custom-option.selection {
        background-color: #4361ee;
        color: white;
    }

    .hidden-select {
        display: none;
    }
</style>

<script>
    // Form handling
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('adminTicketForm');
        
        // Submit event handler
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Form validation
            if (!form.checkValidity()) {
                e.stopPropagation();
                form.classList.add('was-validated');
                return;
            }
            
            // Show loading state
            const submitBtn = document.getElementById('submitTicket');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';
            
            // Submit the form via AJAX
            const formData = new FormData(form);
            
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                },
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message with Sweet Alert
                    Swal.fire({
                        title: 'Success!',
                        text: 'Ticket #' + data.ticket.id + ' has been created successfully',
                        icon: 'success',
                        timer: 8000,
                        timerProgressBar: true,
                        showConfirmButton: false
                    });
                    
                    // Countdown and redirect
                    let seconds = 8;
                    const countdown = setInterval(function() {
                        seconds--;
                        
                        if (seconds <= 0) {
                            clearInterval(countdown);
                            window.location.href = "{{ route('admin.tickets.unassigned') }}";
                        }
                    }, 1000);
                } else {
                    // Show error
                    Swal.fire({
                        title: 'Error!',
                        text: data.message || 'Something went wrong. Please try again.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                    
                    // Reset button
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                
                // Show error message
                Swal.fire({
                    title: 'Error!',
                    text: 'Something went wrong. Please try again.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                
                // Reset button
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });
    });

    function resetForm() {
        const form = document.getElementById('adminTicketForm');
        if (form) form.reset();
        
        const fileName = document.getElementById('fileName');
        if (fileName) fileName.textContent = 'No file selected';
        
        const customSelectTrigger = document.getElementById('customSelectTrigger');
        if (customSelectTrigger) customSelectTrigger.textContent = 'Select Service';
        
        // Reset custom select visual
        document.querySelectorAll('.custom-option').forEach(opt => {
            opt.classList.remove('selection');
        });
    }

    // File input handling
    const fileInput = document.getElementById('attachment');
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            const fileName = document.getElementById('fileName');
            if (this.files.length > 0) {
                fileName.textContent = this.files[0].name;
            } else {
                fileName.textContent = 'No file selected';
            }
        });

        // Drag and drop functionality
        const fileInputWrapper = document.querySelector('.file-input-wrapper');
        fileInputWrapper.addEventListener('dragover', (e) => {
            e.preventDefault();
            fileInputWrapper.querySelector('.file-input-button').style.borderColor = '#4361ee';
            fileInputWrapper.querySelector('.file-input-button').style.backgroundColor = 'rgba(67, 97, 238, 0.1)';
        });

        fileInputWrapper.addEventListener('dragleave', () => {
            fileInputWrapper.querySelector('.file-input-button').style.borderColor = '#dee2e6';
            fileInputWrapper.querySelector('.file-input-button').style.backgroundColor = '#f8f9fa';
        });

        fileInputWrapper.addEventListener('drop', (e) => {
            e.preventDefault();
            fileInputWrapper.querySelector('.file-input-button').style.borderColor = '#dee2e6';
            fileInputWrapper.querySelector('.file-input-button').style.backgroundColor = '#f8f9fa';
            
            if (e.dataTransfer.files.length) {
                fileInput.files = e.dataTransfer.files;
                const fileName = document.getElementById('fileName');
                fileName.textContent = fileInput.files[0].name;
            }
        });
    }

    // Custom select functionality
    document.addEventListener('click', function(e) {
        // Handle option selection
        if (e.target.classList.contains('custom-option')) {
            const value = e.target.getAttribute('data-value');
            const hiddenSelect = document.getElementById('service');
            const customSelectTrigger = document.getElementById('customSelectTrigger');
            const customOptions = document.querySelectorAll('.custom-option');

            // Update hidden select
            for (let i = 0; i < hiddenSelect.options.length; i++) {
                if (hiddenSelect.options[i].value === value) {
                    hiddenSelect.selectedIndex = i;
                    break;
                }
            }

            // Update custom select visual
            customSelectTrigger.textContent = e.target.textContent;

            // Remove previous selection
            customOptions.forEach(opt => opt.classList.remove('selection'));

            // Add selection class
            e.target.classList.add('selection');

            // Close dropdown
            document.getElementById('customSelect').classList.remove('opened');
        }

        // Toggle select dropdown
        if (e.target.id === 'customSelectTrigger' || e.target.closest('#customSelectTrigger')) {
            document.getElementById('customSelect').classList.toggle('opened');
        } else if (!e.target.closest('.custom-select')) {
            // Close dropdown when clicking outside
            document.getElementById('customSelect').classList.remove('opened');
        }
    });

    // Initialize selected service if exists
    const hiddenSelect = document.getElementById('service');
    if (hiddenSelect && hiddenSelect.value) {
        const selectedOption = document.querySelector(`.custom-option[data-value="${hiddenSelect.value}"]`);
        if (selectedOption) {
            const customSelectTrigger = document.getElementById('customSelectTrigger');
            if (customSelectTrigger) {
                customSelectTrigger.textContent = selectedOption.textContent;
                selectedOption.classList.add('selection');
            }
        }
    }
</script>
@endsection