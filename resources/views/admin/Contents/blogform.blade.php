@extends('layouts.contents')

@section('content')

    <div class="blog-editor-container">
        {{-- Page Header --}}
        <div class="page-header-card mb-4">
            <div class="page-header-content">
                <div class="header-text">
                    <h2 class="page-title">
                        <i class="fa fa-{{ isset($blog) ? 'edit' : 'plus' }} me-2"></i>
                        {{ isset($blog) ? 'Edit Blog Post' : 'Create New Blog Post' }}
                    </h2>
                    <p class="page-subtitle">
                        {{ isset($blog) ? 'Update your blog post content and settings' : 'Create engaging content for your audience' }}
                    </p>
                </div>
                <a href="{{ route('admin.blogs.index') }}" class="btn btn-outline-primary">
                    <i class="fa fa-arrow-left me-2"></i>
                    Back to Posts
                </a>
            </div>
        </div>

        {{-- Error Messages --}}
        @if ($errors->any())
            <div class="error-card mb-4">
                <div class="error-content">
                    <div class="error-icon">
                        <i class="fa fa-exclamation-triangle"></i>
                    </div>
                    <div class="error-text">
                        <h5 class="error-title">Please fix the following errors:</h5>
                        <ul class="error-list">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ isset($blog) ? route('admin.blogs.update', $blog->id) : route('admin.blogs.store') }}" 
              method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($blog)) @method('PUT') @endif

            <div class="row">
                <!-- Main Content -->
                <div class="col-lg-8">
                    <div class="content-card mb-4">
                        <div class="content-card-header">
                            <h5 class="card-title">
                                <i class="fa fa-file-text me-2"></i>
                                Blog Content
                            </h5>
                        </div>
                        <div class="content-card-body">
                            <!-- Title -->
                            <div class="form-group">
                                <label for="title" class="form-label">
                                    Title <span class="required">*</span>
                                </label>
                                <input type="text" 
                                       name="title" 
                                       id="title" 
                                       value="{{ old('title', $blog->title ?? '') }}" 
                                       required 
                                       class="form-control form-control-enhanced"
                                       placeholder="Enter blog post title">
                            </div>

                            <!-- Slug -->
                            <div class="form-group">
                                <label for="slug" class="form-label">
                                    Slug
                                </label>
                                <input type="text" 
                                       name="slug" 
                                       id="slug" 
                                       value="{{ old('slug', $blog->slug ?? '') }}"
                                       class="form-control form-control-enhanced"
                                       placeholder="Will be auto-generated if left empty">
                                <small class="form-help">URL-friendly version of the title</small>
                            </div>

                            <!-- Excerpt -->
                            <div class="form-group">
                                <label for="excerpt" class="form-label">
                                    Excerpt
                                </label>
                                <textarea name="excerpt" 
                                          id="excerpt" 
                                          rows="3"
                                          class="form-control form-control-enhanced"
                                          placeholder="Brief description of the blog post">{{ old('excerpt', $blog->excerpt ?? '') }}</textarea>
                                <small class="form-help">This will be shown in blog listings and search results</small>
                            </div>

                            <!-- Content -->
                            <div class="form-group">
                                <label for="content" class="form-label">
                                    Content <span class="required">*</span>
                                </label>
                                <textarea name="content" 
                                          id="content" 
                                          rows="15" 
                                          required
                                          class="form-control form-control-enhanced content-editor"
                                          placeholder="Write your blog post content here...">{{ old('content', $blog->content ?? '') }}</textarea>
                                <small class="form-help">You can use HTML tags for formatting</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Publish Settings -->
                    <div class="content-card mb-4">
                        <div class="content-card-header">
                            <h5 class="card-title">
                                <i class="fa fa-calendar me-2"></i>
                                Publish Settings
                            </h5>
                        </div>
                        <div class="content-card-body">
                            <!-- Status -->
                            <div class="form-group">
                                <label class="form-label">Status</label>
                                <div class="radio-group">
                                    <div class="custom-radio">
                                        <input class="radio-input" 
                                               type="radio" 
                                               name="status" 
                                               id="statusDraft" 
                                               value="draft" 
                                               {{ (!isset($blog) || !$blog->published_at) ? 'checked' : '' }}>
                                        <label class="radio-label" for="statusDraft">
                                            <span class="radio-button"></span>
                                            <span class="radio-text">Draft</span>
                                        </label>
                                    </div>
                                    <div class="custom-radio">
                                        <input class="radio-input" 
                                               type="radio" 
                                               name="status" 
                                               id="statusPublished" 
                                               value="published" 
                                               {{ (isset($blog) && $blog->published_at) ? 'checked' : '' }}>
                                        <label class="radio-label" for="statusPublished">
                                            <span class="radio-button"></span>
                                            <span class="radio-text">Published</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Publish Date -->
                            <div class="form-group">
                                <label for="published_at" class="form-label">
                                    Publish Date
                                </label>
                                <input type="datetime-local" 
                                       name="published_at" 
                                       id="published_at"
                                       value="{{ old('published_at', isset($blog->published_at) ? $blog->published_at->format('Y-m-d\TH:i') : '') }}"
                                       class="form-control form-control-enhanced">
                                <small class="form-help">Leave empty for draft</small>
                            </div>
                        </div>
                    </div>

                    <!-- Meta Information -->
                    <div class="content-card mb-4">
                        <div class="content-card-header">
                            <h5 class="card-title">
                                <i class="fa fa-info-circle me-2"></i>
                                Meta Information
                            </h5>
                        </div>
                        <div class="content-card-body">
                            <!-- Author -->
                            <div class="form-group">
                                <label for="author" class="form-label">
                                    Author
                                </label>
                                <input type="text" 
                                       name="author" 
                                       id="author" 
                                       value="{{ old('author', $blog->author ?? auth()->user()->name ?? '') }}"
                                       class="form-control form-control-enhanced"
                                       placeholder="Author name">
                            </div>

                            <!-- Category -->
                            <div class="form-group">
                                <label for="category" class="form-label">
                                    Category
                                </label>
                                <input type="text" 
                                       name="category" 
                                       id="category" 
                                       value="{{ old('category', $blog->category ?? '') }}"
                                       class="form-control form-control-enhanced"
                                       placeholder="e.g., Technology, Lifestyle">
                            </div>
                        </div>
                    </div>

                    <!-- Featured Image -->
                    <div class="content-card mb-4">
                        <div class="content-card-header">
                            <h5 class="card-title">
                                <i class="fa fa-image me-2"></i>
                                Featured Image
                            </h5>
                        </div>
                        <div class="content-card-body">
                            <div class="form-group">
                                <label for="image" class="form-label">
                                    Upload Image
                                </label>
                                <div class="file-upload-wrapper">
                                    <input type="file" 
                                           name="image" 
                                           id="image" 
                                           accept="image/*"
                                           class="file-input">
                                    <label class="file-label" for="image">
                                        <i class="fa fa-cloud-upload me-2"></i>
                                        <span class="file-text">Choose file</span>
                                    </label>
                                </div>
                                
                                @if(isset($blog) && $blog->image)
                                    <div class="current-image">
                                        <p class="image-title">Current image:</p>
                                        <div class="image-preview">
                                            <img src="{{ $blog->image_url }}" 
                                                 alt="Current featured image" 
                                                 class="preview-img">
                                        </div>
                                        <div class="remove-image-wrapper">
                                            <div class="custom-checkbox">
                                                <input type="checkbox" class="checkbox-input" id="removeImage" name="remove_image">
                                                <label class="checkbox-label" for="removeImage">
                                                    <span class="checkbox-button"></span>
                                                    <span class="checkbox-text">Remove current image</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="content-card">
                        <div class="content-card-body">
                            <button type="submit" 
                                    class="btn btn-primary btn-enhanced btn-block mb-3">
                                <i class="fa fa-save me-2"></i> 
                                {{ isset($blog) ? 'Update Blog Post' : 'Create Blog Post' }}
                            </button>
                            
                            @if(isset($blog))
                            <a href="{{ route('blog.details', $blog->slug) }}" 
                            target="_blank"
                            class="btn btn-outline-secondary btn-enhanced btn-block">
                                <i class="fa fa-eye me-2"></i> 
                                Preview Post
                            </a>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <style>
        /* Dashboard Navigation - Matching other pages */
        .dashboard-nav-wrapper {
            background: var(--white);
            border-radius: 12px;
            box-shadow: var(--shadow);
            padding: 0.5rem;
            margin-bottom: 2rem;
        }

        .panel-nav {
            border: none;
            gap: 0.5rem;
        }

        .panel-nav .nav-link {
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            color: var(--light-text);
            font-weight: 500;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
        }

        .panel-nav .nav-link:hover {
            background: var(--hover-bg);
            color: var(--medium-text);
        }

        .panel-nav .nav-link.active {
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--primary-green-dark) 100%);
            color: var(--white);
            box-shadow: var(--shadow-hover);
        }

        /* Blog Editor Container */
        .blog-editor-container {
            padding: 0;
        }

        /* Page Header */
        .page-header-card {
            background: var(--white);
            border-radius: 16px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border-color);
            overflow: hidden;
        }

        .page-header-content {
            padding: 2rem;
            background: linear-gradient(135deg, var(--ultra-light-green) 0%, var(--light-green) 100%);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary-green-dark);
            margin: 0;
            display: flex;
            align-items: center;
        }

        .page-subtitle {
            color: var(--light-text);
            margin: 0.5rem 0 0 0;
            font-size: 0.95rem;
        }

        /* Error Card */
        .error-card {
            background: #FEF2F2;
            border: 1px solid #FECACA;
            border-radius: 12px;
            overflow: hidden;
        }

        .error-content {
            padding: 1.5rem;
            display: flex;
            gap: 1rem;
        }

        .error-icon {
            color: #DC2626;
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .error-title {
            color: #DC2626;
            font-size: 1rem;
            font-weight: 600;
            margin: 0 0 0.5rem 0;
        }

        .error-list {
            margin: 0;
            padding-left: 1.25rem;
            color: #B91C1C;
        }

        /* Content Cards */
        .content-card {
            background: var(--white);
            border-radius: 16px;
            box-shadow: var(--shadow);
            overflow: hidden;
            border: 1px solid var(--border-color);
        }

        .content-card-header {
            padding: 1.5rem 2rem;
            background: linear-gradient(135deg, var(--ultra-light-green) 0%, var(--light-green) 100%);
            border-bottom: 1px solid var(--border-color);
        }

        .content-card-header .card-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--primary-green);
            margin: 0;
            display: flex;
            align-items: center;
        }

        .content-card-body {
            padding: 2rem;
        }

        /* Form Elements */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: var(--dark-text);
            margin-bottom: 0.5rem;
            display: block;
            font-size: 0.9rem;
        }

        .required {
            color: #DC2626;
        }

        .form-control-enhanced {
            border: 2px solid var(--border-color);
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: var(--white);
        }

        .form-control-enhanced:focus {
            border-color: var(--primary-green);
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
            outline: none;
        }

        .content-editor {
            min-height: 300px;
            font-family: 'Monaco', 'Menlo', monospace;
            font-size: 0.9rem;
        }

        .form-help {
            color: var(--light-text);
            font-size: 0.8rem;
            margin-top: 0.25rem;
            display: block;
        }

        /* Custom Radio Buttons */
        .radio-group {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .custom-radio {
            position: relative;
        }

        .radio-input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
        }

        .radio-label {
            display: flex;
            align-items: center;
            cursor: pointer;
            padding: 0.75rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            border: 2px solid var(--border-color);
        }

        .radio-label:hover {
            background: var(--ultra-light-green);
            border-color: var(--light-green);
        }

        .radio-button {
            width: 20px;
            height: 20px;
            border: 2px solid var(--border-color);
            border-radius: 50%;
            margin-right: 0.75rem;
            position: relative;
            transition: all 0.3s ease;
        }

        .radio-input:checked + .radio-label .radio-button {
            border-color: var(--primary-green);
            background: var(--primary-green);
        }

        .radio-input:checked + .radio-label .radio-button::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--white);
        }

        .radio-input:checked + .radio-label {
            background: var(--ultra-light-green);
            border-color: var(--primary-green);
        }

        .radio-text {
            font-weight: 500;
            color: var(--dark-text);
        }

        /* File Upload */
        .file-upload-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
            width: 100%;
        }

        .file-input {
            position: absolute;
            left: -9999px;
        }

        .file-label {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            border: 2px dashed var(--border-color);
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: var(--ultra-light-green);
            color: var(--primary-green);
            font-weight: 500;
        }

        .file-label:hover {
            border-color: var(--primary-green);
            background: var(--light-green);
        }

        .file-text {
            margin-left: 0.5rem;
        }

        /* Current Image */
        .current-image {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid var(--border-color);
        }

        .image-title {
            font-weight: 600;
            color: var(--dark-text);
            margin-bottom: 0.75rem;
            font-size: 0.9rem;
        }

        .image-preview {
            margin-bottom: 1rem;
        }

        .preview-img {
            width: 100%;
            height: auto;
            border-radius: 8px;
            border: 1px solid var(--border-color);
        }

        /* Custom Checkbox */
        .custom-checkbox {
            position: relative;
        }

        .checkbox-input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            cursor: pointer;
            color: #DC2626;
            font-size: 0.9rem;
        }

        .checkbox-button {
            width: 18px;
            height: 18px;
            border: 2px solid #DC2626;
            border-radius: 4px;
            margin-right: 0.5rem;
            position: relative;
            transition: all 0.3s ease;
        }

        .checkbox-input:checked + .checkbox-label .checkbox-button {
            background: #DC2626;
        }

        .checkbox-input:checked + .checkbox-label .checkbox-button::after {
            content: '✓';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 12px;
            font-weight: bold;
        }

        /* Enhanced Buttons */
        .btn-enhanced {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }

        .btn-primary.btn-enhanced {
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--primary-green-dark) 100%);
            color: var(--white);
        }

        .btn-primary.btn-enhanced:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-hover);
        }

        .btn-outline-primary {
            border: 2px solid var(--primary-green);
            color: var(--primary-green);
            background: transparent;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        .btn-outline-primary:hover {
            background: var(--primary-green);
            color: var(--white);
            transform: translateY(-1px);
        }

        .btn-outline-secondary.btn-enhanced {
            border: 2px solid var(--border-color);
            color: var(--medium-text);
            background: transparent;
        }

        .btn-outline-secondary.btn-enhanced:hover {
            background: var(--light-text);
            color: var(--white);
            border-color: var(--light-text);
        }

        .btn-block {
            width: 100%;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .page-header-content {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .content-card-header,
            .content-card-body {
                padding: 1rem;
            }

            .page-header-content {
                padding: 1.5rem;
            }
        }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
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

        // Update file input label
        const fileInput = document.getElementById('image');
        const fileLabel = document.querySelector('.file-text');
        
        fileInput.addEventListener('change', function(e) {
            const fileName = e.target.files[0] ? e.target.files[0].name : "Choose file";
            fileLabel.textContent = fileName;
        });
    });
    </script>
@endsection
