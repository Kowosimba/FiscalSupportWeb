@extends('layouts.app')

@section('title', isset($campaign) ? 'Edit Newsletter Campaign' : 'Create Newsletter Campaign')

@section('content')
<div class="dashboard-container">
    {{-- Compact Header --}}
    <div class="dashboard-header mb-2">
        <div class="header-content">
            <h1 class="dashboard-title">
                <i class="fas fa-{{ isset($campaign) ? 'edit' : 'bullhorn' }} me-2"></i>
                {{ isset($campaign) ? 'Edit Newsletter Campaign' : 'Create Newsletter Campaign' }}
            </h1>
            <div class="header-meta">
                <small class="text-muted">
                    {{ isset($campaign) ? 'Update your newsletter campaign content' : 'Create a new newsletter campaign' }}
                </small>
            </div>
        </div>
        <div class="header-actions">
            <button onclick="window.location.href='{{ route('admin.newsletters.index') }}'" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>
                Back to Campaigns
            </button>
        </div>
    </div>

    {{-- Validation Errors --}}
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

    {{-- Campaign Form --}}
    <div class="content-card">
        <div class="content-card-header">
            <div class="header-content">
                <h4 class="card-title">
                    <i class="fas fa-{{ isset($campaign) ? 'edit' : 'plus' }} me-2"></i>
                    {{ isset($campaign) ? 'Edit' : 'Create' }} Campaign
                </h4>
                <p class="card-subtitle mb-0">
                    {{ isset($campaign) ? 'Update campaign details and content' : 'Enter campaign information and content' }}
                </p>
            </div>
        </div>
        
        <div class="content-card-body" style="padding: 1.5rem;">
            <form action="{{ isset($campaign) 
                ? route('admin.newsletters.update', $campaign) 
                : route('admin.newsletters.store') }}" 
                  method="POST" 
                  id="campaignForm">
                @csrf
                @if(isset($campaign)) @method('PUT') @endif

                <div class="row g-4">
                    {{-- Campaign Subject --}}
                    <div class="col-12">
                        <div class="form-group">
                            <label for="subject" class="form-label required">
                                <i class="fas fa-heading me-1"></i>
                                Subject Line
                            </label>
                            <input type="text" 
                                   name="subject" 
                                   id="subject" 
                                   class="form-control @error('subject') is-invalid @enderror"
                                   value="{{ old('subject', $campaign->subject ?? '') }}" 
                                   required
                                   placeholder="Enter newsletter subject line"
                                   maxlength="100">
                            <small class="form-text text-muted">This will be the email subject line (recommended: 30-50 characters)</small>
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Campaign Content --}}
                    <div class="col-12">
                        <div class="form-group">
                            <label for="content" class="form-label required">
                                <i class="fas fa-file-text me-1"></i>
                                Campaign Content
                            </label>
                            <textarea name="content" 
                                      id="content" 
                                      class="form-control content-editor @error('content') is-invalid @enderror" 
                                      rows="12" 
                                      required
                                      placeholder="Enter your newsletter content here...">{{ old('content', $campaign->content ?? '') }}</textarea>
                            <small class="form-text text-muted">Use HTML tags for formatting. You can include links, images, and styling.</small>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Campaign Status (if editing) --}}
                    @if(isset($campaign))
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-info-circle me-1"></i>
                                Campaign Status
                            </label>
                            <div class="status-info">
                                @if($campaign->sent_at)
                                    <span class="status-badge status-sent">
                                        <i class="fas fa-check-circle me-1"></i>
                                        Sent on {{ $campaign->sent_at->format('M d, Y \a\t g:i A') }}
                                    </span>
                                @elseif($campaign->scheduled_at)
                                    <span class="status-badge status-scheduled">
                                        <i class="fas fa-clock me-1"></i>
                                        Scheduled for {{ $campaign->scheduled_at->format('M d, Y \a\t g:i A') }}
                                    </span>
                                @else
                                    <span class="status-badge status-draft">
                                        <i class="fas fa-edit me-1"></i>
                                        Draft
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Schedule Options --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="scheduled_at" class="form-label">
                                <i class="fas fa-calendar me-1"></i>
                                Schedule Campaign (Optional)
                            </label>
                            <input type="datetime-local" 
                                   name="scheduled_at" 
                                   id="scheduled_at"
                                   class="form-control @error('scheduled_at') is-invalid @enderror"
                                   value="{{ old('scheduled_at', isset($campaign->scheduled_at) ? $campaign->scheduled_at->format('Y-m-d\TH:i') : '') }}"
                                   min="{{ now()->format('Y-m-d\TH:i') }}">
                            <small class="form-text text-muted">Leave empty to save as draft</small>
                            @error('scheduled_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Form Actions --}}
                <div class="form-actions mt-4 pt-3" style="border-top: 1px solid var(--gray-200);">
                    <div class="d-flex gap-3 justify-content-end">
                        <button type="button" 
                                onclick="window.location.href='{{ route('admin.newsletters.index') }}'" 
                                class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>
                            Cancel
                        </button>
                        
                        {{-- Save as Draft --}}
                        <button type="submit" name="action" value="draft" class="btn btn-outline-primary" id="draftBtn">
                            <i class="fas fa-save me-2"></i>
                            Save as Draft
                        </button>
                        
                        {{-- Save and Send/Schedule --}}
                        @if(!isset($campaign) || !$campaign->sent_at)
                        <button type="submit" name="action" value="send" class="btn btn-secondary" id="submitBtn">
                            <i class="fas fa-{{ isset($campaign) ? 'save' : 'paper-plane' }} me-2"></i>
                            {{ isset($campaign) ? 'Update Campaign' : 'Save & Send' }}
                        </button>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Campaign Preview Section --}}
    @if(isset($campaign))
    <div class="content-card mt-3">
        <div class="content-card-header">
            <div class="header-content">
                <h4 class="card-title">
                    <i class="fas fa-eye me-2"></i>
                    Campaign Preview
                </h4>
                <p class="card-subtitle mb-0">
                    Preview how your campaign will look to subscribers
                </p>
            </div>
            <div class="header-actions">
                <button onclick="openPreviewModal()" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-external-link-alt me-1"></i>
                    Full Preview
                </button>
            </div>
        </div>
        
        <div class="content-card-body" style="padding: 1.5rem;">
            <div class="preview-container">
                <div class="email-preview">
                    <div class="email-header">
                        <strong>Subject:</strong> {{ $campaign->subject }}
                    </div>
                    <div class="email-content">
                        {!! Str::limit(strip_tags($campaign->content), 200) !!}
                        @if(strlen(strip_tags($campaign->content)) > 200)
                            <span class="text-muted">... <em>(content truncated in preview)</em></span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

{{-- Preview Modal --}}
@if(isset($campaign))
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
                <div class="email-full-preview">
                    <div class="email-header mb-3">
                        <div class="row">
                            <div class="col-sm-3"><strong>Subject:</strong></div>
                            <div class="col-sm-9">{{ $campaign->subject }}</div>
                        </div>
                        @if($campaign->scheduled_at)
                        <div class="row mt-2">
                            <div class="col-sm-3"><strong>Scheduled:</strong></div>
                            <div class="col-sm-9">{{ $campaign->scheduled_at->format('M d, Y \a\t g:i A') }}</div>
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
@endif
@endsection

@push('styles')
<style>
/* Newsletter Campaign Form Styles */
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

/* Content Editor */
.content-editor {
    min-height: 300px;
    font-family: inherit;
    resize: vertical;
}

/* Status Info */
.status-info {
    padding: 0.5rem 0;
}

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
    color: #059669;
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

.btn-outline-primary {
    color: var(--info);
    border-color: var(--info);
}

.btn-outline-primary:hover {
    background: var(--info);
    border-color: var(--info);
    color: white;
}

/* Preview Styles */
.preview-container {
    border: 1px solid var(--gray-200);
    border-radius: var(--border-radius);
    overflow: hidden;
}

.email-preview {
    background: var(--gray-50);
    padding: 1rem;
}

.email-header {
    background: var(--white);
    padding: 0.75rem;
    border-bottom: 1px solid var(--gray-200);
    font-size: 0.875rem;
    margin-bottom: 0.75rem;
    border-radius: var(--border-radius);
}

.email-content {
    background: var(--white);
    padding: 1rem;
    border-radius: var(--border-radius);
    line-height: 1.6;
    color: var(--gray-700);
}

.email-full-preview .email-content {
    padding: 0;
    background: transparent;
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

/* Modal Styles */
.modal-content {
    border-radius: var(--border-radius);
    border: none;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
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
    
    .content-card-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .content-card-header .header-actions {
        width: 100%;
        justify-content: flex-start;
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
    const form = document.getElementById('campaignForm');
    const submitBtn = document.getElementById('submitBtn');
    const draftBtn = document.getElementById('draftBtn');
    
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
    
    // Handle form submission with loading states
    if (form) {
        form.addEventListener('submit', function(e) {
            const clickedBtn = e.submitter;
            
            if (clickedBtn === submitBtn) {
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
            } else if (clickedBtn === draftBtn) {
                const originalContent = draftBtn.innerHTML;
                draftBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';
                draftBtn.disabled = true;
                
                // Re-enable if form validation fails (client-side)
                setTimeout(() => {
                    if (draftBtn.disabled) {
                        draftBtn.innerHTML = originalContent;
                        draftBtn.disabled = false;
                    }
                }, 3000);
            }
        });
    }
    
    // Auto-focus first input
    const firstInput = document.getElementById('subject');
    if (firstInput) {
        firstInput.focus();
    }
    
    // Character counter for subject line
    const subjectInput = document.getElementById('subject');
    if (subjectInput) {
        function updateCharCount() {
            const length = subjectInput.value.length;
            const helpText = subjectInput.nextElementSibling;
            
            if (length > 50) {
                helpText.style.color = '#D97706';
            } else if (length > 30) {
                helpText.style.color = '#059669';
            } else {
                helpText.style.color = '#6B7280';
            }
            
            helpText.innerHTML = `This will be the email subject line (recommended: 30-50 characters) - Current: ${length}/100`;
        }
        
        subjectInput.addEventListener('input', updateCharCount);
        updateCharCount(); // Initial call
    }
    
    // Initialize TinyMCE or similar editor if available
    if (typeof tinymce !== 'undefined') {
        tinymce.init({
            selector: '#content',
            height: 400,
            menubar: false,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap',
                'anchor', 'searchreplace', 'visualblocks', 'code',
                'insertdatetime', 'media', 'table', 'help', 'wordcount'
            ],
            toolbar: 'undo redo | blocks | ' +
                'bold italic forecolor backcolor | alignleft aligncenter ' +
                'alignright alignjustify | bullist numlist outdent indent | ' +
                'removeformat | link | help',
            content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 14px; line-height: 1.6; }'
        });
    }
});

// Open preview modal function
@if(isset($campaign))
function openPreviewModal() {
    const modal = new bootstrap.Modal(document.getElementById('previewModal'));
    modal.show();
}
@endif
</script>
@endpush

