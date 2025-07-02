@extends('layouts.contents')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">
                    {{ isset($blog) ? 'Edit Blog Post' : 'Create New Blog Post' }}
                </h2>
                <a href="{{ route('blogs.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left mr-1"></i> Back to Posts
                </a>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <div class="d-flex">
                <i class="fas fa-exclamation-circle fa-lg mt-1 mr-3"></i>
                <div>
                    <h5 class="alert-heading">Please fix the following errors:</h5>
                    <ul class="mb-0 pl-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ isset($blog) ? route('blogs.update', $blog->id) : route('blogs.store') }}" 
          method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($blog)) @method('PUT') @endif

        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <!-- Title -->
                        <div class="form-group">
                            <label for="title" class="font-weight-bold">
                                Title <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="title" 
                                   id="title" 
                                   value="{{ old('title', $blog->title ?? '') }}" 
                                   required 
                                   class="form-control form-control-lg"
                                   placeholder="Enter blog post title">
                        </div>

                        <!-- Slug -->
                        <div class="form-group">
                            <label for="slug" class="font-weight-bold">
                                Slug
                            </label>
                            <input type="text" 
                                   name="slug" 
                                   id="slug" 
                                   value="{{ old('slug', $blog->slug ?? '') }}"
                                   class="form-control"
                                   placeholder="Will be auto-generated if left empty">
                            <small class="form-text text-muted">URL-friendly version of the title</small>
                        </div>

                        <!-- Excerpt -->
                        <div class="form-group">
                            <label for="excerpt" class="font-weight-bold">
                                Excerpt
                            </label>
                            <textarea name="excerpt" 
                                      id="excerpt" 
                                      rows="3"
                                      class="form-control"
                                      placeholder="Brief description of the blog post">{{ old('excerpt', $blog->excerpt ?? '') }}</textarea>
                            <small class="form-text text-muted">This will be shown in blog listings and search results</small>
                        </div>

                        <!-- Content -->
                        <div class="form-group">
                            <label for="content" class="font-weight-bold">
                                Content <span class="text-danger">*</span>
                            </label>
                            <textarea name="content" 
                                      id="content" 
                                      rows="15" 
                                      required
                                      class="form-control"
                                      placeholder="Write your blog post content here...">{{ old('content', $blog->content ?? '') }}</textarea>
                            <small class="form-text text-muted">You can use HTML tags for formatting</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Publish Settings -->
                <div class="card mb-4">
    <div class="card-header bg-light">
        <h5 class="mb-0">Publish Settings</h5>
    </div>
    <div class="card-body">
        <!-- Status -->
        <div class="form-group">
            <label class="font-weight-bold">Status</label>
            <div class="form-check">
                <input class="form-check-input" 
                       type="radio" 
                       name="status" 
                       id="statusDraft" 
                       value="draft" 
                       {{ (!isset($blog) || !$blog->published_at) ? 'checked' : '' }}>
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
                       {{ (isset($blog) && $blog->published_at) ? 'checked' : '' }}>
                <label class="form-check-label" for="statusPublished">
                    Published
                </label>
            </div>
        </div>

        <!-- Publish Date -->
        <div class="form-group">
            <label for="published_at" class="font-weight-bold">
                Publish Date
            </label>
            <input type="datetime-local" 
       name="published_at" 
       id="published_at"
       value="{{ old('published_at', isset($blog->published_at) ? $blog->published_at->format('Y-m-d\TH:i') : '') }}"
       class="form-control">
            <small class="form-text text-muted">Leave empty for draft</small>
        </div>
    </div>
</div>

                <!-- Meta Information -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Meta Information</h5>
                    </div>
                    <div class="card-body">
                        <!-- Author -->
                        <div class="form-group">
                            <label for="author" class="font-weight-bold">
                                Author
                            </label>
                            <input type="text" 
                                   name="author" 
                                   id="author" 
                                   value="{{ old('author', $blog->author ?? auth()->user()->name ?? '') }}"
                                   class="form-control"
                                   placeholder="Author name">
                        </div>

                        <!-- Category -->
                        <div class="form-group">
                            <label for="category" class="font-weight-bold">
                                Category
                            </label>
                            <input type="text" 
                                   name="category" 
                                   id="category" 
                                   value="{{ old('category', $blog->category ?? '') }}"
                                   class="form-control"
                                   placeholder="e.g., Technology, Lifestyle">
                        </div>
                    </div>
                </div>

                <!-- Featured Image -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Featured Image</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="image" class="font-weight-bold">
                                Upload Image
                            </label>
                            <div class="custom-file">
                                <input type="file" 
                                       name="image" 
                                       id="image" 
                                       accept="image/*"
                                       class="custom-file-input">
                                <label class="custom-file-label" for="image">Choose file</label>
                            </div>
                            
                            @if(isset($blog) && $blog->image)
                                <div class="mt-3">
                                    <p class="font-weight-bold mb-2">Current image:</p>
                                    <img src="{{ $blog->image_url }}" 
                                         alt="Current featured image" 
                                         class="img-fluid rounded border">
                                    <div class="mt-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="removeImage" name="remove_image">
                                            <label class="custom-control-label text-danger" for="removeImage">Remove current image</label>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="card">
                    <div class="card-body">
                        <button type="submit" 
                                class="btn btn-primary btn-block btn-lg mb-2">
                            <i class="fas fa-save mr-1"></i> 
                            {{ isset($blog) ? 'Update Blog Post' : 'Create Blog Post' }}
                        </button>
                        
                        @if(isset($blog))
                            <a href="{{ route('blog.details', $blog->slug) }}" 
                               target="_blank"
                               class="btn btn-outline-secondary btn-block">
                                <i class="fas fa-eye mr-1"></i> Preview Post
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

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
    document.querySelector('.custom-file-input').addEventListener('change', function(e) {
        const fileName = e.target.files[0] ? e.target.files[0].name : "Choose file";
        const label = this.nextElementSibling;
        label.textContent = fileName;
    });
});
</script>
@endsection