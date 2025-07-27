@extends('layouts.app')

@section('title', isset($blog) ? 'Edit Blog Post' : 'Create New Blog Post')

@section('content')
<div class="dashboard-container">
    {{-- Compact Header --}}
    <div class="dashboard-header mb-2">
        <div class="header-content">
            <h1 class="dashboard-title">
                <i class="fas fa-{{ isset($blog) ? 'edit' : 'plus' }} me-2"></i>
                {{ isset($blog) ? 'Edit Blog Post' : 'Create New Blog Post' }}
            </h1>
            <div class="header-meta">
                <small class="text-muted">
                    {{ isset($blog) ? 'Update your blog post content and settings' : 'Create engaging content for your audience' }}
                </small>
            </div>
        </div>
        <div class="header-actions">
            <button onclick="window.location.href='{{ route('admin.blogs.index') }}'" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>
                Back to Posts
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

    {{-- Blog Form --}}
    <form action="{{ isset($blog) ? route('admin.blogs.update', $blog->id) : route('admin.blogs.store') }}" 
          method="POST" enctype="multipart/form-data" id="blogForm">
        @csrf
        @if(isset($blog)) @method('PUT') @endif

        <div class="row g-3">
            {{-- Main Content --}}
            <div class="col-lg-8">
                <div class="content-card">
                    <div class="content-card-header">
                        <div class="header-content">
                            <h4 class="card-title">
                                <i class="fas fa-file-text me-2"></i>
                                Blog Content
                            </h4>
                            <p class="card-subtitle mb-0">
                                Enter the main content for your blog post
                            </p>
                        </div>
                    </div>
                    
                    <div class="content-card-body" style="padding: 1.5rem;">
                        <div class="row g-4">
                            {{-- Title --}}
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="title" class="form-label required">
                                        <i class="fas fa-heading me-1"></i>
                                        Title
                                    </label>
                                    <input type="text" 
                                           name="title" 
                                           id="title"
                                           class="form-control @error('title') is-invalid @enderror" 
                                           value="{{ old('title', $blog->title ?? '') }}" 
                                           required 
                                           placeholder="Enter blog post title">
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Slug --}}
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="slug" class="form-label">
                                        <i class="fas fa-link me-1"></i>
                                        Slug
                                    </label>
                                    <input type="text" 
                                           name="slug" 
                                           id="slug"
                                           class="form-control @error('slug') is-invalid @enderror" 
                                           value="{{ old('slug', $blog->slug ?? '') }}"
                                           placeholder="Will be auto-generated if left empty">
                                    <small class="form-text text-muted">URL-friendly version of the title</small>
                                    @error('slug')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Excerpt --}}
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="excerpt" class="form-label">
                                        <i class="fas fa-quote-left me-1"></i>
                                        Excerpt
                                    </label>
                                    <textarea name="excerpt" 
                                              id="excerpt"
                                              class="form-control @error('excerpt') is-invalid @enderror" 
                                              rows="3"
                                              placeholder="Brief description of the blog post">{{ old('excerpt', $blog->excerpt ?? '') }}</textarea>
                                    <small class="form-text text-muted">This will be shown in blog listings and search results</small>
                                    @error('excerpt')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Content --}}
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="content" class="form-label required">
                                        <i class="fas fa-edit me-1"></i>
                                        Content
                                    </label>
                                    <textarea name="content" 
                                              id="content"
                                              class="form-control content-editor @error('content') is-invalid @enderror" 
                                              rows="12" 
                                              required
                                              placeholder="Write your blog post content here...">{{ old('content', $blog->content ?? '') }}</textarea>
                                    <small class="form-text text-muted">You can use HTML tags for formatting</small>
                                    @error('content')
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
                {{-- Publish Settings --}}
                <div class="content-card mb-3">
                    <div class="content-card-header">
                        <div class="header-content">
                            <h4 class="card-title">
                                <i class="fas fa-calendar me-2"></i>
                                Publish Settings
                            </h4>
                        </div>
                    </div>
                    
                    <div class="content-card-body" style="padding: 1.5rem;">
                        <div class="row g-3">
                            {{-- Status --}}
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-circle me-1"></i>
                                        Status
                                    </label>
                                    <div class="d-flex gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input" 
                                                   type="radio" 
                                                   name="status" 
                                                   id="statusDraft" 
                                                   value="draft" 
                                                   @checked(!isset($blog) || !$blog->published_at)>
                                            <label class="form-check-label" for="statusDraft">
                                                Draft
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" 
                                                   type="radio" 
                                                   name="status" 
                                                   id="statusPublished" 
                                                   value="published" 
                                                   @checked(isset($blog) && $blog->published_at)>
                                            <label class="form-check-label" for="statusPublished">
                                                Published
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Publish Date --}}
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="published_at" class="form-label">
                                        <i class="fas fa-clock me-1"></i>
                                        Publish Date
                                    </label>
                                    <input type="datetime-local" 
                                           name="published_at" 
                                           id="published_at"
                                           class="form-control @error('published_at') is-invalid @enderror"
                                           value="{{ old('published_at', isset($blog->published_at) ? $blog->published_at->format('Y-m-d\TH:i') : '') }}">
                                    <small class="form-text text-muted">Leave empty for draft</small>
                                    @error('published_at')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Meta Information --}}
                <div class="content-card mb-3">
                    <div class="content-card-header">
                        <div class="header-content">
                            <h4 class="card-title">
                                <i class="fas fa-info-circle me-2"></i>
                                Meta Information
                            </h4>
                        </div>
                    </div>
                    
                    <div class="content-card-body" style="padding: 1.5rem;">
                        <div class="row g-3">
                            {{-- Author --}}
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="author" class="form-label">
                                        <i class="fas fa-user me-1"></i>
                                        Author
                                    </label>
                                    <input type="text" 
                                           name="author" 
                                           id="author"
                                           class="form-control @error('author') is-invalid @enderror" 
                                           value="{{ old('author', $blog->author ?? auth()->user()->name ?? '') }}"
                                           placeholder="Author name">
                                    @error('author')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Category --}}
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="category" class="form-label">
                                        <i class="fas fa-tag me-1"></i>
                                        Category
                                    </label>
                                    <input type="text" 
                                           name="category" 
                                           id="category"
                                           class="form-control @error('category') is-invalid @enderror" 
                                           value="{{ old('category', $blog->category ?? '') }}"
                                           placeholder="e.g., Technology, Lifestyle">
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Featured Image --}}
                <div class="content-card mb-3">
                    <div class="content-card-header">
                        <div class="header-content">
                            <h4 class="card-title">
                                <i class="fas fa-image me-2"></i>
                                Featured Image
                            </h4>
                        </div>
                    </div>
                    
                    <div class="content-card-body" style="padding: 1.5rem;">
                        <div class="form-group">
                            <label for="image" class="form-label">
                                <i class="fas fa-upload me-1"></i>
                                Upload Image
                            </label>
                            <div class="file-input-wrapper">
                                <div class="file-input-button" onclick="document.getElementById('image').click()">
                                    <i class="fas fa-cloud-upload-alt me-2"></i>
                                    <span class="file-text">Choose file</span>
                                </div>
                                <input type="file" 
                                       name="image" 
                                       id="image" 
                                       accept="image/*"
                                       class="file-input">
                            </div>
                            
                            @if(isset($blog) && $blog->image)
                                <div class="current-image mt-3">
                                    <p class="image-title">Current image:</p>
                                    <div class="image-preview mb-2">
                                        <img src="{{ $blog->image_url }}" 
                                             alt="Current featured image" 
                                             class="preview-img">
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" 
                                               class="form-check-input" 
                                               id="removeImage" 
                                               name="remove_image">
                                        <label class="form-check-label text-danger" for="removeImage">
                                            Remove current image
                                        </label>
                                    </div>
                                </div>
                            @endif
                            
                            @error('image')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="content-card">
                    <div class="content-card-body" style="padding: 1.5rem;">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-secondary" id="submitBtn">
                                <i class="fas fa-save me-2"></i>
                                {{ isset($blog) ? 'Update Blog Post' : 'Create Blog Post' }}
                            </button>
                            
                            @if(isset($blog))
                                <button type="button" 
                                        onclick="window.open('{{ route('blog.details', $blog->slug) }}', '_blank')" 
                                        class="btn btn-outline-secondary">
                                    <i class="fas fa-eye me-2"></i>
                                    Preview Post
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
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

/* Content Editor */
.content-editor {
    min-height: 300px;
    font-family: inherit;
    resize: vertical;
}

/* Radio buttons */
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

/* File Input */
.file-input-wrapper {
    position: relative;
    width: 100%;
}

.file-input-button {
    border: 2px dashed var(--gray-300);
    border-radius: var(--border-radius);
    padding: 1rem;
    text-align: center;
    cursor: pointer;
    transition: var(--transition);
    background-color: var(--gray-50);
    color: var(--gray-600);
}

.file-input-button:hover {
    border-color: var(--secondary);
    background-color: rgba(107, 114, 128, 0.05);
    color: var(--secondary);
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

.file-text {
    font-weight: 500;
}

/* Current Image */
.current-image {
    padding-top: 1rem;
    border-top: 1px solid var(--gray-200);
}

.image-title {
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

.image-preview {
    border-radius: var(--border-radius);
    overflow: hidden;
    border: 1px solid var(--gray-200);
}

.preview-img {
    width: 100%;
    height: auto;
    display: block;
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
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('blogForm');
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
    
    // Auto-generate slug from title
    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');
    
    titleInput.addEventListener('input', function() {
        if (!slugInput.value || slugInput.dataset.autoGenerated) {
            const slug = this.value
                .toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');
            slugInput.value = slug;
            slugInput.dataset.autoGenerated = 'true';
        }
    });

    // Mark slug as manually edited
    slugInput.addEventListener('input', function() {
        delete this.dataset.autoGenerated;
    });

    // Handle status change
    const statusRadios = document.querySelectorAll('input[name="status"]');
    const publishedAtInput = document.getElementById('published_at');
    
    statusRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'published' && !publishedAtInput.value) {
                // Set current date/time if publishing
                const now = new Date();
                const localDateTime = new Date(now.getTime() - now.getTimezoneOffset() * 60000)
                    .toISOString()
                    .slice(0, 16);
                publishedAtInput.value = localDateTime;
            } else if (this.value === 'draft') {
                publishedAtInput.value = '';
            }
        });
    });

    // Update file input display
    const fileInput = document.getElementById('image');
    const fileText = document.querySelector('.file-text');
    
    fileInput.addEventListener('change', function(e) {
        const fileName = e.target.files[0] ? e.target.files[0].name : "Choose file";
        fileText.textContent = fileName;
        
        if (e.target.files[0]) {
            toastr.success(`File "${fileName}" selected successfully.`, 'File Selected', {
                closeButton: true,
                progressBar: true,
                timeOut: 2000,
                positionClass: 'toast-top-right'
            });
        }
    });
    
    // Auto-focus first input
    const firstInput = document.getElementById('title');
    if (firstInput) {
        firstInput.focus();
    }
    
    // Initialize TinyMCE or similar editor if available
    if (typeof tinymce !== 'undefined') {
        tinymce.init({
            selector: '#content',
            height: 400,
            menubar: false,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'help', 'wordcount'
            ],
            toolbar: 'undo redo | blocks | ' +
                'bold italic forecolor | alignleft aligncenter ' +
                'alignright alignjustify | bullist numlist outdent indent | ' +
                'removeformat | help',
            content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 14px; }'
        });
    }
});
</script>
@endpush
