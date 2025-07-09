@extends('layouts.contents')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-3 mb-4 border-bottom">
        <h1 class="h2">Services Management</h1>
    </div>
    <div class="row">
        {{-- Service Form --}}
        <div class="col-md-6">
            <div class="content-card mb-4">
                <div class="content-card-header">
                    <h5 class="card-title">
                        <i class="fa fa-cogs me-2"></i>
                        {{ $editService ? 'Edit Service' : 'Add New Service' }}
                    </h5>
                </div>
                <div class="content-card-body">
                    <form method="POST" enctype="multipart/form-data" action="{{ $editService ? route('admin.services.update', $editService) : route('admin.services.store') }}">
                        @csrf
                        @if($editService) @method('PUT') @endif

                        {{-- Basic Info --}}
                        <div class="mb-4">
                            <h6 class="section-title mb-3"><i class="fa fa-info-circle me-2"></i>Basic Information</h6>
                            <div class="form-group">
                                <label for="title" class="form-label">Service Title *</label>
                                <input type="text" class="form-control form-control-enhanced" id="title" name="title" required 
                                    value="{{ old('title', $editService->title ?? '') }}">
                                @error('title')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="slug" class="form-label">URL Slug</label>
                                <input type="text" class="form-control form-control-enhanced" id="slug" name="slug" 
                                    value="{{ old('slug', $editService->slug ?? '') }}">
                                <small class="form-help">Leave blank to auto-generate from title</small>
                                @error('slug')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="description" class="form-label">Short Description *</label>
                                <textarea class="form-control form-control-enhanced" id="description" name="description" rows="3" required>{{ old('description', $editService->description ?? '') }}</textarea>
                                @error('description')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="content" class="form-label">Detailed Content</label>
                                <textarea class="form-control form-control-enhanced rich-text-editor" id="content" name="content" rows="5">{{ old('content', $editService->content ?? '') }}</textarea>
                                @error('content')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Media --}}
                        <div class="mb-4">
                            <h6 class="section-title mb-3"><i class="fa fa-image me-2"></i>Media</h6>
                            <div class="form-group">
                                <label for="image" class="form-label">Service Image</label>
                                <input type="file" class="form-control form-control-enhanced" id="image" name="image">
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
                            <div class="form-group">
                                <label for="icon" class="form-label">Service Icon (Font Awesome class)</label>
                                <input type="text" class="form-control form-control-enhanced" id="icon" name="icon" 
                                    value="{{ old('icon', $editService->icon ?? '') }}"
                                    placeholder="fas fa-icon-name">
                                <small class="form-help">Example: fas fa-chart-line</small>
                                @error('icon')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Settings --}}
                        <div class="mb-4">
                            <h6 class="section-title mb-3"><i class="fa fa-sliders-h me-2"></i>Settings</h6>
                            <div class="form-check form-switch mb-3">
                                <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" 
                                    {{ old('is_featured', $editService->is_featured ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_featured">Featured Service</label>
                            </div>
                            <div class="form-group">
                                <label for="sort_order" class="form-label">Sort Order</label>
                                <input type="number" class="form-control form-control-enhanced" id="sort_order" name="sort_order" 
                                    value="{{ old('sort_order', $editService->sort_order ?? 0) }}">
                                @error('sort_order')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- SEO --}}
                        <div class="mb-4">
                            <h6 class="section-title mb-3"><i class="fa fa-search me-2"></i>SEO Settings</h6>
                            <div class="form-group">
                                <label for="meta_title" class="form-label">Meta Title</label>
                                <input type="text" class="form-control form-control-enhanced" id="meta_title" name="meta_title" 
                                    value="{{ old('meta_title', $editService->meta_title ?? '') }}"
                                    maxlength="60">
                                <small class="form-help">Recommended: 50-60 characters</small>
                                @error('meta_title')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="meta_description" class="form-label">Meta Description</label>
                                <textarea class="form-control form-control-enhanced" id="meta_description" name="meta_description" 
                                    rows="3">{{ old('meta_description', $editService->meta_description ?? '') }}</textarea>
                                <small class="form-help">Recommended: 150-160 characters</small>
                                @error('meta_description')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="meta_keywords" class="form-label">Meta Keywords</label>
                                <input type="text" class="form-control form-control-enhanced" id="meta_keywords" name="meta_keywords" 
                                    value="{{ old('meta_keywords', $editService->meta_keywords ?? '') }}"
                                    placeholder="keyword1, keyword2, keyword3">
                                @error('meta_keywords')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Submit --}}
                        <div class="form-actions d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary btn-enhanced">
                                <i class="fa fa-save me-2"></i> {{ $editService ? 'Update Service' : 'Add Service' }}
                            </button>
                            @if($editService)
                                <a href="{{ route('admin.services.index') }}" class="btn btn-secondary btn-enhanced">
                                    <i class="fa fa-times me-2"></i> Cancel
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            {{-- Resources Section --}}
            @if($editService)
            <div class="content-card">
                <div class="content-card-header">
                    <h5 class="card-title"><i class="fa fa-paperclip me-2"></i>Service Resources</h5>
                </div>
                <div class="content-card-body">
                    {{-- Alerts --}}
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
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form method="POST" action="{{ route('admin.services.resources.store', $editService) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="resource_title" class="form-label">Resource Title *</label>
                            <input type="text" class="form-control form-control-enhanced" id="resource_title" name="title" required>
                        </div>
                        <div class="form-group">
                            <label for="resource_file" class="form-label">File *</label>
                            <input type="file" class="form-control form-control-enhanced" id="resource_file" name="file" required>
                            <small class="form-help">PDF, Word, Excel, PowerPoint or Text files (max 10MB)</small>
                        </div>
                        <button type="submit" class="btn btn-success btn-enhanced">
                            <i class="fa fa-plus me-2"></i> Add Resource
                        </button>
                    </form>
                    <hr>
                    <h6 class="section-title mt-4 mb-3"><i class="fa fa-folder-open me-2"></i>Current Resources</h6>
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
                                        <a href="{{ Storage::disk('public')->url($resource->file_path) }}" class="btn btn-sm btn-success me-1" download>
                                            <i class="fa fa-download"></i>
                                        </a>
                                        <form action="{{ route('admin.services.resources.destroy', ['service' => $editService->id, 'resource' => $resource->id]) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this resource?')">
                                                <i class="fa fa-trash"></i>
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

        {{-- Services List --}}
        <div class="col-md-6">
            <div class="content-card">
                <div class="content-card-header">
                    <h5 class="card-title"><i class="fa fa-list me-2"></i>Services List</h5>
                </div>
                <div class="content-card-body">
                    @if($services->isEmpty())
                        <div class="alert alert-info">No services found.</div>
                    @else
                        <div class="table-responsive">
                            <table class="enhanced-table">
                                <thead>
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
                                                    <span class="badge status-badge status-active">Yes</span>
                                                @else
                                                    <span class="badge status-badge status-inactive">No</span>
                                                @endif
                                            </td>
                                            <td>{{ $service->sort_order }}</td>
                                            <td>
                                                <div class="action-buttons">
                                                    <a href="{{ route('admin.services.index', ['edit' => $service->id]) }}" class="action-btn edit-btn" title="Edit">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.services.destroy', $service) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="action-btn delete-btn" onclick="return confirm('Are you sure you want to delete this service?')">
                                                            <i class="fa fa-trash"></i>
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

<style>
    .dashboard-nav-wrapper { background: var(--white); border-radius: 12px; box-shadow: var(--shadow); padding: 0.5rem; margin-bottom: 2rem;}
    .panel-nav { border: none; gap: 0.5rem; }
    .panel-nav .nav-link { border: none; padding: 0.75rem 1.5rem; border-radius: 8px; color: var(--light-text); font-weight: 500; transition: all 0.3s ease; display: flex; align-items: center; }
    .panel-nav .nav-link:hover { background: var(--hover-bg); color: var(--medium-text);}
    .panel-nav .nav-link.active { background: linear-gradient(135deg, var(--primary-green) 0%, var(--primary-green-dark) 100%); color: var(--white); box-shadow: var(--shadow-hover);}
    .content-card { background: var(--white); border-radius: 16px; box-shadow: var(--shadow); overflow: hidden; border: 1px solid var(--border-color);}
    .content-card-header { padding: 1.5rem 2rem; background: linear-gradient(135deg, var(--ultra-light-green) 0%, var(--light-green) 100%); border-bottom: 1px solid var(--border-color);}
    .content-card-header .card-title { font-size: 1.15rem; font-weight: 600; color: var(--primary-green);}
    .content-card-body { padding: 2rem;}
    .form-label { font-weight: 600; color: var(--dark-text);}
    .form-control-enhanced { border: 2px solid var(--border-color); border-radius: 8px; padding: 0.75rem 1rem; font-size: 0.95rem; transition: all 0.3s ease; background: var(--white);}
    .form-control-enhanced:focus { border-color: var(--primary-green); box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1); outline: none;}
    .section-title { color: var(--primary-green-dark); font-weight: 600; display: flex; align-items: center;}
    .form-help { color: var(--light-text); font-size: 0.8rem;}
    .btn-enhanced { padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 500; transition: all 0.3s ease; border: none; display: flex; align-items: center; text-decoration: none;}
    .btn-primary.btn-enhanced { background: linear-gradient(135deg, var(--primary-green) 0%, var(--primary-green-dark) 100%); color: var(--white);}
    .btn-primary.btn-enhanced:hover { transform: translateY(-2px); box-shadow: var(--shadow-hover);}
    .btn-secondary.btn-enhanced { background: var(--hover-bg); color: var(--medium-text); border: 2px solid var(--border-color);}
    .btn-secondary.btn-enhanced:hover { background: var(--medium-text); color: var(--white);}
    .action-buttons { display: flex; gap: 0.5rem;}
    .action-btn { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 8px; transition: all 0.2s ease; text-decoration: none; border: none; cursor: pointer;}
    .edit-btn { background: var(--light-green); color: var(--primary-green-dark);}
    .edit-btn:hover { background: var(--primary-green-dark); color: var(--white);}
    .delete-btn { background: #FEF2F2; color: #DC2626;}
    .delete-btn:hover { background: #DC2626; color: var(--white);}
    .status-badge { display: inline-flex; align-items: center; padding: 0.375rem 0.75rem; border-radius: 6px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;}
    .status-active { background: var(--ultra-light-green); color: var(--primary-green); border: 1px solid var(--secondary-green);}
    .status-inactive { background: #FEF2F2; color: #DC2626; border: 1px solid #FECACA;}
    .enhanced-table { width: 100%; border-collapse: separate; border-spacing: 0; margin: 0;}
    .enhanced-table thead th { background: var(--ultra-light-green); color: var(--primary-green); font-weight: 600; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.05em; padding: 1rem 1.5rem; border-bottom: 2px solid var(--light-green);}
    .enhanced-table tbody tr { transition: all 0.2s ease; border-bottom: 1px solid var(--border-color);}
    .enhanced-table tbody tr:last-child { border-bottom: none;}
    .enhanced-table tbody tr:hover { background: var(--ultra-light-green);}
    .enhanced-table tbody td { padding: 1rem 1.5rem; vertical-align: middle;}
    @media (max-width: 768px) {
        .content-card-header, .content-card-body { padding: 1rem;}
    }
</style>
@endsection

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
</script>
@endpush
