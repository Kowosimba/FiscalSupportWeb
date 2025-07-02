@extends('layouts.contents')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-3 mb-4 border-bottom">
        <h1 class="h2">Services Management</h1>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    {{ $editService ? 'Edit Service' : 'Add New Service' }}
                </div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data" action="{{ $editService ? route('admin.services.update', $editService) : route('admin.services.store') }}">
                        @csrf
                        @if($editService) @method('PUT') @endif

                        <!-- Basic Information Section -->
                        <div class="mb-4">
                            <h5 class="mb-3">Basic Information</h5>
                            
                            <div class="mb-3">
                                <label for="title" class="form-label">Service Title *</label>
                                <input type="text" class="form-control" id="title" name="title" required 
                                    value="{{ old('title', $editService->title ?? '') }}">
                                @error('title')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="slug" class="form-label">URL Slug</label>
                                <input type="text" class="form-control" id="slug" name="slug" 
                                    value="{{ old('slug', $editService->slug ?? '') }}">
                                <div class="form-text">Leave blank to auto-generate from title</div>
                                @error('slug')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Short Description *</label>
                                <textarea class="form-control" id="description" name="description" rows="3" required>{{ 
                                    old('description', $editService->description ?? '')
                                }}</textarea>
                                @error('description')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="content" class="form-label">Detailed Content</label>
                                <textarea class="form-control rich-text-editor" id="content" name="content" rows="5">{{ 
                                    old('content', $editService->content ?? '')
                                }}</textarea>
                                @error('content')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Media Section -->
                        <div class="mb-4">
                            <h5 class="mb-3">Media</h5>
                            
                            <div class="mb-3">
                                <label for="image" class="form-label">Service Image</label>
                                <input type="file" class="form-control" id="image" name="image">
                                @if($editService && $editService->image)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $editService->image) }}" alt="Current Image" style="max-height: 100px;" class="img-thumbnail">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" id="remove_image" name="remove_image">
                                            <label class="form-check-label" for="remove_image">Remove current image</label>
                                        </div>
                                    </div>
                                @endif
                                @error('image')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="icon" class="form-label">Service Icon (Font Awesome class)</label>
                                <input type="text" class="form-control" id="icon" name="icon" 
                                    value="{{ old('icon', $editService->icon ?? '') }}"
                                    placeholder="fas fa-icon-name">
                                <div class="form-text">Example: fas fa-chart-line</div>
                                @error('icon')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Process Steps Section -->
                        <div class="mb-4">
                            <h5 class="mb-3">Process Steps</h5>
                            
                            <div id="process-steps-container">
                                @php
                                    $processSteps = $editService ? json_decode($editService->process_steps, true) : old('process_steps', []);
                                @endphp
                                
                                @if(!empty($processSteps))
                                    @foreach($processSteps as $index => $step)
                                        <div class="process-step mb-3 p-3 border rounded">
                                            <div class="d-flex justify-content-between mb-2">
                                                <h6>Step {{ $loop->iteration }}</h6>
                                                <button type="button" class="btn btn-sm btn-danger remove-step">Remove</button>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label">Title</label>
                                                <input type="text" class="form-control" name="process_steps[{{ $index }}][title]" 
                                                    value="{{ $step['title'] ?? '' }}" required>
                                            </div>
                                            <div>
                                                <label class="form-label">Description</label>
                                                <textarea class="form-control" name="process_steps[{{ $index }}][description]" 
                                                    rows="2" required>{{ $step['description'] ?? '' }}</textarea>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <button type="button" id="add-process-step" class="btn btn-sm btn-secondary mt-2">
                                <i class="fas fa-plus"></i> Add Process Step
                            </button>
                        </div>

                        <!-- Settings Section -->
                        <div class="mb-4">
                            <h5 class="mb-3">Settings</h5>
                            
                            <div class="mb-3 form-check form-switch">
                                <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" 
                                    {{ old('is_featured', $editService->is_featured ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_featured">Featured Service</label>
                            </div>

                            <div class="mb-3">
                                <label for="sort_order" class="form-label">Sort Order</label>
                                <input type="number" class="form-control" id="sort_order" name="sort_order" 
                                    value="{{ old('sort_order', $editService->sort_order ?? 0) }}">
                                @error('sort_order')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- SEO Section -->
                        <div class="mb-4">
                            <h5 class="mb-3">SEO Settings</h5>
                            
                            <div class="mb-3">
                                <label for="meta_title" class="form-label">Meta Title</label>
                                <input type="text" class="form-control" id="meta_title" name="meta_title" 
                                    value="{{ old('meta_title', $editService->meta_title ?? '') }}"
                                    maxlength="60">
                                <div class="form-text">Recommended: 50-60 characters</div>
                                @error('meta_title')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="meta_description" class="form-label">Meta Description</label>
                                <textarea class="form-control" id="meta_description" name="meta_description" 
                                    rows="3">{{ old('meta_description', $editService->meta_description ?? '') }}</textarea>
                                <div class="form-text">Recommended: 150-160 characters</div>
                                @error('meta_description')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="meta_keywords" class="form-label">Meta Keywords</label>
                                <input type="text" class="form-control" id="meta_keywords" name="meta_keywords" 
                                    value="{{ old('meta_keywords', $editService->meta_keywords ?? '') }}"
                                    placeholder="keyword1, keyword2, keyword3">
                                @error('meta_keywords')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Submit Section -->
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> {{ $editService ? 'Update Service' : 'Add Service' }}
                            </button>

                            @if($editService)
                                <a href="{{ route('admin.services.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Resources Section -->
            @if($editService)
            <div class="card">
                <div class="card-header bg-info text-white">
                    Service Resources
                </div>
                <div class="card-body">

                    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
                    <form method="POST" action="{{ route('admin.services.resources.store', $editService) }}" 
                          enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="resource_title" class="form-label">Resource Title *</label>
                            <input type="text" class="form-control" id="resource_title" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="resource_file" class="form-label">File *</label>
                            <input type="file" class="form-control" id="resource_file" name="file" required>
                            <div class="form-text">PDF, Word, Excel, PowerPoint or Text files (max 10MB)</div>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add Resource
                        </button>
                    </form>

                    <hr>

                    <h5 class="mt-4">Current Resources</h5>
                    @if($editService->resources->isEmpty())
                        <div class="alert alert-info">No resources added yet.</div>
                    @else
                        <div class="list-group">
                            @foreach($editService->resources as $resource)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $resource->title }}</strong>
                                        <div class="text-muted">{{ strtoupper($resource->file_type) }} • {{ $resource->file_size }}</div>
                                    </div>
                                    <div class="btn-group">
                                        <a href="{{ Storage::disk('public')->url($resource->file_path) }}" 
                                            class="btn btn-sm btn-success me-1" download>
                                            <i class="fas fa-download"></i>
                                        </a>
                                        <form action="{{ route('admin.services.resources.destroy', ['service' => $editService->id, 'resource' => $resource->id]) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                onclick="return confirm('Delete this resource?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success text-white">
                    Services List
                </div>
                <div class="card-body">
                    @if($services->isEmpty())
                        <div class="alert alert-info">No services found.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Title</th>
                                        <th>Featured</th>
                                        <th>Order</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($services as $service)
                                        <tr>
                                            <td>{{ $service->title }}</td>
                                            <td>
                                                @if($service->is_featured)
                                                    <span class="badge bg-success">Yes</span>
                                                @else
                                                    <span class="badge bg-secondary">No</span>
                                                @endif
                                            </td>
                                            <td>{{ $service->sort_order }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('admin.services.index', ['edit' => $service->id]) }}" 
                                                        class="btn btn-sm btn-primary">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.services.destroy', $service) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" 
                                                                onclick="return confirm('Are you sure you want to delete this service?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .rich-text-editor {
        min-height: 200px;
    }
    .process-step {
        background-color: #f8f9fa;
    }
</style>
@endpush

@push('scripts')
<script>
    // Auto-generate slug from title
    document.getElementById('title').addEventListener('input', function() {
        if (!document.getElementById('slug').value) {
            document.getElementById('slug').value = this.value.toLowerCase()
                .replace(/\s+/g, '-')
                .replace(/[^\w\-]+/g, '')
                .replace(/\-\-+/g, '-')
                .replace(/^-+/, '')
                .replace(/-+$/, '');
        }
    });

    // Process steps management
    document.getElementById('add-process-step').addEventListener('click', function() {
        const container = document.getElementById('process-steps-container');
        const stepCount = container.querySelectorAll('.process-step').length;
        
        const stepDiv = document.createElement('div');
        stepDiv.className = 'process-step mb-3 p-3 border rounded';
        stepDiv.innerHTML = `
            <div class="d-flex justify-content-between mb-2">
                <h6>Step ${stepCount + 1}</h6>
                <button type="button" class="btn btn-sm btn-danger remove-step">Remove</button>
            </div>
            <div class="mb-2">
                <label class="form-label">Title</label>
                <input type="text" class="form-control" name="process_steps[${stepCount}][title]" required>
            </div>
            <div>
                <label class="form-label">Description</label>
                <textarea class="form-control" name="process_steps[${stepCount}][description]" rows="2" required></textarea>
            </div>
        `;
        
        container.appendChild(stepDiv);
    });

    // Remove process step
    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('remove-step')) {
            e.target.closest('.process-step').remove();
            
            // Reindex remaining steps
            const container = document.getElementById('process-steps-container');
            const steps = container.querySelectorAll('.process-step');
            
            steps.forEach((step, index) => {
                step.querySelector('h6').textContent = `Step ${index + 1}`;
                
                // Update input names
                const inputs = step.querySelectorAll('input, textarea');
                inputs.forEach(input => {
                    const name = input.name.replace(/\[\d+\]/, `[${index}]`);
                    input.name = name;
                });
            });
        }
    });

    // Initialize rich text editor
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize CKEditor or other rich text editor here
        // Example: CKEDITOR.replace('content');
    });

    // Process steps management
document.getElementById('add-process-step').addEventListener('click', function() {
    const container = document.getElementById('process-steps-container');
    const stepCount = container.querySelectorAll('.process-step').length;
    
    const stepDiv = document.createElement('div');
    stepDiv.className = 'process-step mb-3 p-3 border rounded';
    stepDiv.innerHTML = `
        <div class="d-flex justify-content-between mb-2">
            <h6>Step ${stepCount + 1}</h6>
            <button type="button" class="btn btn-sm btn-danger remove-step">Remove</button>
        </div>
        <div class="mb-2">
            <label class="form-label">Title *</label>
            <input type="text" class="form-control" name="process_steps[${stepCount}][title]" required>
        </div>
        <div>
            <label class="form-label">Description *</label>
            <textarea class="form-control" name="process_steps[${stepCount}][description]" rows="2" required></textarea>
        </div>
    `;
    
    container.appendChild(stepDiv);
});

// Remove process step
document.addEventListener('click', function(e) {
    if (e.target && e.target.classList.contains('remove-step')) {
        e.preventDefault();
        e.target.closest('.process-step').remove();
        
        // Reindex remaining steps
        const container = document.getElementById('process-steps-container');
        const steps = container.querySelectorAll('.process-step');
        
        steps.forEach((step, index) => {
            step.querySelector('h6').textContent = `Step ${index + 1}`;
            
            // Update input names
            const inputs = step.querySelectorAll('input, textarea');
            inputs.forEach(input => {
                const name = input.name.replace(/process_steps\[\d+\]/, `process_steps[${index}]`);
                input.name = name;
            });
        });
    }
});
</script>
@endpush