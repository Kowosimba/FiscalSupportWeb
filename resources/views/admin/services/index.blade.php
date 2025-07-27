@extends('layouts.app')

@section('title', 'Services Management')

@section('content')
<div class="dashboard-container">
    {{-- Compact Header --}}
    <div class="dashboard-header mb-2">
        <div class="header-content">
            <h1 class="dashboard-title">
                <i class="fas fa-cogs me-2"></i>
                Services Management
            </h1>
            <div class="header-meta">
                <span class="badge bg-secondary">{{ $services->count() }} services</span>
                <small class="text-muted">Updated: {{ now()->format('g:i a') }}</small>
            </div>
        </div>
        <div class="header-actions">
            @if($editService)
                <button onclick="window.location.href='{{ route('admin.services.index') }}'" class="btn btn-sm btn-outline-secondary me-2">
                    <i class="fas fa-plus me-1"></i>
                    Add New Service
                </button>
            @endif
            <button id="refreshServices" class="btn btn-sm btn-secondary">
                <i class="fas fa-sync-alt me-1"></i>
                Refresh
            </button>
        </div>
    </div>

    {{-- Success & Error Alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-2">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-2">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

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

    <div class="row g-3">
        {{-- Service Form --}}
        <div class="col-lg-6">
            <div class="content-card mb-3">
                <div class="content-card-header">
                    <div class="header-content">
                        <h4 class="card-title">
                            <i class="fas fa-{{ $editService ? 'edit' : 'plus' }} me-2"></i>
                            {{ $editService ? 'Edit Service' : 'Add New Service' }}
                        </h4>
                        <p class="card-subtitle mb-0">
                            {{ $editService ? 'Update service information' : 'Create a new service' }}
                        </p>
                    </div>
                </div>
                
                <div class="content-card-body" style="padding: 1.5rem;">
                    <form method="POST" enctype="multipart/form-data" 
                          action="{{ $editService ? route('admin.services.update', $editService) : route('admin.services.store') }}"
                          id="serviceForm">
                        @csrf
                        @if($editService) @method('PUT') @endif

                        <div class="row g-4">
                            {{-- Basic Information --}}
                            <div class="col-12">
                                <h6 class="section-title">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Basic Information
                                </h6>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title" class="form-label required">
                                        <i class="fas fa-heading me-1"></i>
                                        Service Title
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('title') is-invalid @enderror" 
                                           id="title" 
                                           name="title" 
                                           required 
                                           value="{{ old('title', $editService->title ?? '') }}"
                                           placeholder="Enter service title">
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="slug" class="form-label">
                                        <i class="fas fa-link me-1"></i>
                                        URL Slug
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('slug') is-invalid @enderror" 
                                           id="slug" 
                                           name="slug" 
                                           value="{{ old('slug', $editService->slug ?? '') }}"
                                           placeholder="auto-generated">
                                    <small class="form-text text-muted">Leave blank to auto-generate from title</small>
                                    @error('slug')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="description" class="form-label required">
                                        <i class="fas fa-align-left me-1"></i>
                                        Short Description
                                    </label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" 
                                              name="description" 
                                              rows="3" 
                                              required
                                              placeholder="Brief description of the service">{{ old('description', $editService->description ?? '') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="content" class="form-label">
                                        <i class="fas fa-file-text me-1"></i>
                                        Detailed Content
                                    </label>
                                    <textarea class="form-control @error('content') is-invalid @enderror" 
                                              id="content" 
                                              name="content" 
                                              rows="5"
                                              placeholder="Detailed service content (optional)">{{ old('content', $editService->content ?? '') }}</textarea>
                                    @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Media & Settings --}}
                            <div class="col-12">
                                <h6 class="section-title">
                                    <i class="fas fa-cog me-2"></i>
                                    Media & Settings
                                </h6>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="image" class="form-label">
                                        <i class="fas fa-image me-1"></i>
                                        Service Image
                                    </label>
                                    <div class="file-input-wrapper">
                                        <div class="file-input-button" onclick="document.getElementById('image').click()">
                                            <i class="fas fa-cloud-upload-alt me-2"></i>
                                            <span class="file-text">Choose file</span>
                                        </div>
                                        <input type="file" 
                                               class="file-input @error('image') is-invalid @enderror" 
                                               id="image" 
                                               name="image"
                                               accept="image/*">
                                    </div>
                                    
                                    @if($editService && $editService->image)
                                        <div class="current-image mt-3">
                                            <p class="image-title">Current image:</p>
                                            <div class="image-preview mb-2">
                                                <img src="{{ asset('storage/' . $editService->image) }}" 
                                                     alt="Current Image" 
                                                     class="preview-img">
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" 
                                                       type="checkbox" 
                                                       id="remove_image" 
                                                       name="remove_image">
                                                <label class="form-check-label text-danger" for="remove_image">
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

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="icon" class="form-label">
                                        <i class="fas fa-icons me-1"></i>
                                        Service Icon
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('icon') is-invalid @enderror" 
                                           id="icon" 
                                           name="icon" 
                                           value="{{ old('icon', $editService->icon ?? '') }}"
                                           placeholder="fas fa-icon-name">
                                    <small class="form-text text-muted">Example: fas fa-chart-line</small>
                                    @error('icon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-star me-1"></i>
                                        Featured Service
                                    </label>
                                    <div class="form-check form-switch">
                                        <input type="checkbox" 
                                               class="form-check-input" 
                                               id="is_featured" 
                                               name="is_featured"
                                               @checked(old('is_featured', $editService->is_featured ?? false))>
                                        <label class="form-check-label" for="is_featured">
                                            Mark as featured
                                        </label>
                                        <small class="form-text text-muted d-block mt-1">
                                            Featured services appear prominently
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sort_order" class="form-label">
                                        <i class="fas fa-sort-numeric-up me-1"></i>
                                        Sort Order
                                    </label>
                                    <input type="number" 
                                           class="form-control @error('sort_order') is-invalid @enderror" 
                                           id="sort_order" 
                                           name="sort_order" 
                                           value="{{ old('sort_order', $editService->sort_order ?? 0) }}"
                                           min="0"
                                           placeholder="0">
                                    <small class="form-text text-muted">Lower numbers appear first</small>
                                    @error('sort_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- SEO Settings --}}
                            <div class="col-12">
                                <h6 class="section-title">
                                    <i class="fas fa-search me-2"></i>
                                    SEO Settings
                                </h6>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="meta_title" class="form-label">
                                        <i class="fas fa-heading me-1"></i>
                                        Meta Title
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('meta_title') is-invalid @enderror" 
                                           id="meta_title" 
                                           name="meta_title" 
                                           value="{{ old('meta_title', $editService->meta_title ?? '') }}"
                                           maxlength="60"
                                           placeholder="SEO title (50-60 characters)">
                                    <small class="form-text text-muted">Recommended: 50-60 characters</small>
                                    @error('meta_title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="meta_description" class="form-label">
                                        <i class="fas fa-file-text me-1"></i>
                                        Meta Description
                                    </label>
                                    <textarea class="form-control @error('meta_description') is-invalid @enderror" 
                                              id="meta_description" 
                                              name="meta_description" 
                                              rows="3"
                                              placeholder="SEO description (150-160 characters)">{{ old('meta_description', $editService->meta_description ?? '') }}</textarea>
                                    <small class="form-text text-muted">Recommended: 150-160 characters</small>
                                    @error('meta_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="meta_keywords" class="form-label">
                                        <i class="fas fa-tags me-1"></i>
                                        Meta Keywords
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('meta_keywords') is-invalid @enderror" 
                                           id="meta_keywords" 
                                           name="meta_keywords" 
                                           value="{{ old('meta_keywords', $editService->meta_keywords ?? '') }}"
                                           placeholder="keyword1, keyword2, keyword3">
                                    @error('meta_keywords')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Form Actions --}}
                        <div class="form-actions mt-4 pt-3" style="border-top: 1px solid var(--gray-200);">
                            <div class="d-flex gap-3 justify-content-end">
                                @if($editService)
                                    <button type="button" 
                                            onclick="window.location.href='{{ route('admin.services.index') }}'" 
                                            class="btn btn-outline-secondary">
                                        <i class="fas fa-times me-2"></i>
                                        Cancel
                                    </button>
                                @endif
                                <button type="submit" class="btn btn-secondary" id="submitBtn">
                                    <i class="fas fa-save me-2"></i>
                                    {{ $editService ? 'Update Service' : 'Add Service' }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Service Resources Section --}}
            @if($editService)
            <div class="content-card">
                <div class="content-card-header">
                    <div class="header-content">
                        <h4 class="card-title">
                            <i class="fas fa-paperclip me-2"></i>
                            Service Resources
                        </h4>
                        <p class="card-subtitle mb-0">
                            Manage downloadable resources for this service
                        </p>
                    </div>
                </div>
                
                <div class="content-card-body" style="padding: 1.5rem;">
                    {{-- Add Resource Form --}}
                    <form method="POST" action="{{ route('admin.services.resources.store', $editService) }}" 
                          enctype="multipart/form-data" id="resourceForm">
                        @csrf
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="resource_title" class="form-label required">
                                        <i class="fas fa-heading me-1"></i>
                                        Resource Title
                                    </label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="resource_title" 
                                           name="title" 
                                           required
                                           placeholder="Enter resource title">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="resource_file" class="form-label required">
                                        <i class="fas fa-file me-1"></i>
                                        File
                                    </label>
                                    <input type="file" 
                                           class="form-control" 
                                           id="resource_file" 
                                           name="file" 
                                           required>
                                    <small class="form-text text-muted">PDF, Word, Excel, PowerPoint or Text files (max 10MB)</small>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-success btn-sm" id="addResourceBtn">
                                    <i class="fas fa-plus me-2"></i>
                                    Add Resource
                                </button>
                            </div>
                        </div>
                    </form>

                    {{-- Current Resources --}}
                    <div style="border-top: 1px solid var(--gray-200); padding-top: 1rem;">
                        <h6 class="section-title mb-3">
                            <i class="fas fa-folder-open me-2"></i>
                            Current Resources ({{ $editService->resources->count() }})
                        </h6>
                        
                        @if($editService->resources->isEmpty())
                            <div class="empty-state text-center py-4">
                                <i class="fas fa-folder-open text-muted" style="font-size: 2rem;"></i>
                                <h6 class="text-muted mt-2">No resources added yet</h6>
                                <p class="text-muted mb-0">Add downloadable resources for this service</p>
                            </div>
                        @else
                            <div class="resources-list">
                                @foreach($editService->resources as $resource)
                                    <div class="resource-item">
                                        <div class="resource-info">
                                            <div class="resource-title">{{ $resource->title }}</div>
                                            <div class="resource-meta">
                                                <span class="file-type">{{ strtoupper($resource->file_type) }}</span>
                                                <span class="file-size">{{ $resource->file_size }}</span>
                                            </div>
                                        </div>
                                        <div class="resource-actions">
                                            <button onclick="window.open('{{ Storage::url($resource->file_path) }}', '_blank')" 
                                                    class="action-btn download-btn" 
                                                    title="Download">
                                                <i class="fas fa-download"></i>
                                            </button>
                                            <button onclick="deleteResource({{ $editService->id }}, {{ $resource->id }}, '{{ addslashes($resource->title) }}')" 
                                                    class="action-btn delete-btn" 
                                                    title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>

        {{-- Services List --}}
        <div class="col-lg-6">
            <div class="content-card">
                <div class="content-card-header">
                    <div class="header-content">
                        <h4 class="card-title">
                            <i class="fas fa-list me-2"></i>
                            Services List
                        </h4>
                        @if($services->count() > 0)
                        <p class="card-subtitle mb-0">
                            Manage {{ $services->count() }} service{{ $services->count() !== 1 ? 's' : '' }}
                        </p>
                        @endif
                    </div>
                    <div class="header-actions">
                        <div class="d-flex gap-2">
                            <span class="badge featured-badge">
                                <i class="fas fa-star me-1"></i>
                                {{ $services->where('is_featured', true)->count() }} Featured
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="content-card-body">
                    @if($services->isEmpty())
                        <div class="empty-state text-center py-4">
                            <i class="fas fa-cogs text-muted" style="font-size: 2rem;"></i>
                            <h6 class="text-muted mt-2">No services found</h6>
                            <p class="text-muted mb-0">Create your first service to get started</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="compact-table">
                                <thead>
                                    <tr>
                                        <th>Service</th>
                                        <th>Status</th>
                                        <th>Order</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($services as $service)
                                        <tr class="{{ $editService && $editService->id === $service->id ? 'editing-row' : '' }}">
                                            <td>
                                                <div class="service-info">
                                                    @if($service->icon)
                                                        <div class="service-icon">
                                                            <i class="{{ $service->icon }}"></i>
                                                        </div>
                                                    @endif
                                                    <div class="service-details">
                                                        <div class="service-title">{{ $service->title }}</div>
                                                        <div class="service-slug">/{{ $service->slug }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if($service->is_featured)
                                                    <span class="status-badge status-featured">
                                                        <i class="fas fa-star me-1"></i>
                                                        Featured
                                                    </span>
                                                @else
                                                    <span class="status-badge status-regular">
                                                        <i class="fas fa-circle me-1"></i>
                                                        Regular
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="order-badge">{{ $service->sort_order }}</span>
                                            </td>
                                            <td>
                                                <div class="action-buttons">
                                                    <button onclick="window.location.href='{{ route('admin.services.index', ['edit' => $service->id]) }}'" 
                                                       class="action-btn edit-btn" 
                                                       title="Edit Service">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button onclick="deleteService({{ $service->id }}, '{{ addslashes($service->title) }}')" 
                                                            class="action-btn delete-btn" 
                                                            title="Delete Service">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
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

{{-- Delete Service Confirmation Modal --}}
<div class="modal fade" id="deleteServiceModal" tabindex="-1" aria-labelledby="deleteServiceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: var(--danger); color: white;">
                <h5 class="modal-title" id="deleteServiceModalLabel">
                    <i class="fas fa-trash me-2"></i>
                    Delete Service
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <i class="fas fa-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">Are you sure?</h5>
                    <p class="text-muted">You are about to delete the service:</p>
                    <strong id="serviceName" class="text-danger"></strong>
                    <p class="text-muted mt-2">This action cannot be undone!</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancel
                </button>
                <form id="deleteServiceForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" id="confirmDeleteServiceBtn">
                        <i class="fas fa-trash me-2"></i>Delete Service
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Delete Resource Confirmation Modal --}}
<div class="modal fade" id="deleteResourceModal" tabindex="-1" aria-labelledby="deleteResourceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: var(--danger); color: white;">
                <h5 class="modal-title" id="deleteResourceModalLabel">
                    <i class="fas fa-trash me-2"></i>
                    Delete Resource
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <i class="fas fa-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">Are you sure?</h5>
                    <p class="text-muted">You are about to delete the resource:</p>
                    <strong id="resourceName" class="text-danger"></strong>
                    <p class="text-muted mt-2">This action cannot be undone!</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancel
                </button>
                <form id="deleteResourceForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" id="confirmDeleteResourceBtn">
                        <i class="fas fa-trash me-2"></i>Delete Resource
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Enhanced Services Management Styles */
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

.header-meta {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-top: 0.125rem;
}

.header-meta .badge {
    font-size: 0.65rem;
    padding: 0.2rem 0.4rem;
    border-radius: 4px;
}

.bg-secondary {
    background: var(--secondary) !important;
    color: white;
}

.header-meta small {
    font-size: 0.7rem;
    color: var(--gray-500);
}

.header-actions {
    display: flex;
    gap: 0.5rem;
}

.header-actions .btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    height: 28px;
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

.section-title {
    color: var(--gray-700);
    font-weight: 600;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid var(--gray-200);
}

.section-title i {
    color: var(--secondary);
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
    max-height: 100px;
    object-fit: cover;
    display: block;
}

/* Form Switch */
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

/* Featured Badge */
.featured-badge {
    background: #FEF3C7 !important;
    color: #D97706 !important;
    border: 1px solid #FDE68A;
    font-size: 0.7rem;
    padding: 0.3rem 0.6rem;
}

/* Compact Table */
.compact-table {
    width: 100%;
    font-size: 0.85rem;
    border-collapse: separate;
    border-spacing: 0;
}

.compact-table th {
    background: var(--gray-50);
    color: var(--gray-700);
    font-weight: 600;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 0.5rem 0.75rem;
    border-bottom: 1px solid var(--gray-200);
}

.compact-table td {
    padding: 0.5rem 0.75rem;
    vertical-align: middle;
    border-bottom: 1px solid var(--gray-100);
}

.compact-table tr:last-child td {
    border-bottom: none;
}

.compact-table tr:hover {
    background: var(--gray-50);
}

.editing-row {
    background: #FEF3C7 !important;
}

.editing-row:hover {
    background: #FDE68A !important;
}

/* Service Info */
.service-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.service-icon {
    width: 32px;
    height: 32px;
    border-radius: 6px;
    background: var(--gray-100);
    color: var(--secondary);
    display: flex;
    align-items: center;
    justify-content: center;
}

.service-details {
    flex: 1;
    min-width: 0;
}

.service-title {
    font-weight: 500;
    color: var(--gray-800);
    font-size: 0.85rem;
}

.service-slug {
    color: var(--gray-500);
    font-size: 0.75rem;
    font-family: 'Monaco', 'Menlo', monospace;
}

/* Status badges */
.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
    white-space: nowrap;
}

.status-featured {
    background: #FEF3C7;
    color: #D97706;
    border: 1px solid #FDE68A;
}

.status-regular {
    background: var(--gray-100);
    color: var(--gray-600);
    border: 1px solid var(--gray-300);
}

.order-badge {
    background: var(--gray-100);
    color: var(--gray-700);
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-weight: 600;
    font-size: 0.75rem;
    border: 1px solid var(--gray-300);
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 0.25rem;
    align-items: center;
}

.action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    border-radius: 4px;
    transition: var(--transition);
    border: none;
    cursor: pointer;
    background: none;
    padding: 0;
}

.edit-btn {
    background: #EFF6FF;
    color: #1D4ED8;
    border: 1px solid #BFDBFE;
}

.edit-btn:hover {
    background: #1D4ED8;
    color: white;
}

.delete-btn {
    background: #FEF2F2;
    color: #DC2626;
    border: 1px solid #FECACA;
}

.delete-btn:hover {
    background: #DC2626;
    color: white;
}

.download-btn {
    background: #F0FDF4;
    color: #059669;
    border: 1px solid #BBF7D0;
}

.download-btn:hover {
    background: #059669;
    color: white;
}

/* Resources List */
.resources-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.resource-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem;
    border: 1px solid var(--gray-200);
    border-radius: var(--border-radius);
    transition: var(--transition);
}

.resource-item:hover {
    background: var(--gray-50);
}

.resource-info {
    flex: 1;
    min-width: 0;
}

.resource-title {
    font-weight: 500;
    color: var(--gray-800);
    font-size: 0.875rem;
    margin-bottom: 0.25rem;
}

.resource-meta {
    display: flex;
    gap: 0.5rem;
    font-size: 0.75rem;
    color: var(--gray-500);
}

.file-type {
    background: var(--gray-100);
    padding: 0.15rem 0.4rem;
    border-radius: 3px;
    font-weight: 600;
}

.resource-actions {
    display: flex;
    gap: 0.25rem;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 2rem 1rem;
}

.empty-state i {
    color: var(--gray-300);
    margin-bottom: 0.5rem;
}

.empty-state h6 {
    font-size: 0.9rem;
    color: var(--gray-600);
    margin-bottom: 0.25rem;
}

.empty-state p {
    font-size: 0.8rem;
    color: var(--gray-500);
    margin: 0;
}

/* Alert Styles */
.alert {
    border-radius: var(--border-radius);
    padding: 0.75rem 1rem;
    font-size: 0.85rem;
}

.alert-success {
    background: #F0FDF4;
    color: #166534;
    border: 1px solid #BBF7D0;
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

/* Responsive */
@media (max-width: 768px) {
    .dashboard-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
        padding: 0.5rem;
    }
    
    .header-actions {
        width: 100%;
        justify-content: flex-end;
    }
    
    .compact-table {
        font-size: 0.8rem;
    }
    
    .compact-table th,
    .compact-table td {
        padding: 0.4rem 0.5rem;
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
    
    .service-info {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .resource-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .resource-actions {
        width: 100%;
        justify-content: flex-end;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
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
    
    // Check for error message and show toastr
    @if(session('error'))
        toastr.error("{{ session('error') }}", "Error!", {
            closeButton: true,
            progressBar: true,
            timeOut: 5000,
            extendedTimeOut: 2000,
            positionClass: 'toast-top-right'
        });
    @endif
    
    // Auto-generate slug from title
    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');
    
    if (titleInput && slugInput) {
        titleInput.addEventListener('input', function() {
            if (!slugInput.value || slugInput.dataset.autoGenerated) {
                const slug = this.value.toLowerCase()
                    .replace(/\s+/g, '-')
                    .replace(/[^\w\-]+/g, '')
                    .replace(/\-\-+/g, '-')
                    .replace(/^-+/, '')
                    .replace(/-+$/, '');
                slugInput.value = slug;
                slugInput.dataset.autoGenerated = 'true';
            }
        });

        // Mark slug as manually edited
        slugInput.addEventListener('input', function() {
            delete this.dataset.autoGenerated;
        });
    }
    
    // Handle form submission with loading state
    const serviceForm = document.getElementById('serviceForm');
    const submitBtn = document.getElementById('submitBtn');
    
    if (serviceForm && submitBtn) {
        serviceForm.addEventListener('submit', function() {
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
    }
    
    // Handle resource form submission
    const resourceForm = document.getElementById('resourceForm');
    const addResourceBtn = document.getElementById('addResourceBtn');
    
    if (resourceForm && addResourceBtn) {
        resourceForm.addEventListener('submit', function() {
            const originalContent = addResourceBtn.innerHTML;
            addResourceBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Adding...';
            addResourceBtn.disabled = true;
            
            // Re-enable if form validation fails (client-side)
            setTimeout(() => {
                if (addResourceBtn.disabled) {
                    addResourceBtn.innerHTML = originalContent;
                    addResourceBtn.disabled = false;
                }
            }, 3000);
        });
    }
    
    // Update file input display
    const fileInput = document.getElementById('image');
    const fileText = document.querySelector('.file-text');
    
    if (fileInput && fileText) {
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
    }
    
    // Refresh button functionality
    const refreshBtn = document.getElementById('refreshServices');
    if (refreshBtn) {
        refreshBtn.addEventListener('click', function() {
            const originalContent = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Refreshing';
            this.disabled = true;
            
            setTimeout(() => {
                window.location.reload();
            }, 800);
        });
    }
    
    // Auto-focus first input
    const firstInput = document.getElementById('title');
    if (firstInput) {
        firstInput.focus();
    }
});

// Delete service functionality with toastr
function deleteService(serviceId, serviceName) {
    document.getElementById('serviceName').textContent = serviceName;
    document.getElementById('deleteServiceForm').action = `/admin/services/${serviceId}`;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteServiceModal'));
    modal.show();
    
    // Handle form submission with loading state and toastr
    const deleteForm = document.getElementById('deleteServiceForm');
    const confirmBtn = document.getElementById('confirmDeleteServiceBtn');
    
    deleteForm.onsubmit = function(e) {
        e.preventDefault();
        
        const originalContent = confirmBtn.innerHTML;
        confirmBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Deleting...';
        confirmBtn.disabled = true;
        
        // Submit the form with fetch for better control
        fetch(deleteForm.action, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            modal.hide();
            
            if (data.success || response.ok) {
                toastr.success('Service deleted successfully!', 'Success!', {
                    closeButton: true,
                    progressBar: true,
                    timeOut: 3000,
                    positionClass: 'toast-top-right'
                });
                
                // Reload page after short delay
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                toastr.error('Error deleting service. Please try again.', 'Error!', {
                    closeButton: true,
                    progressBar: true,
                    timeOut: 5000,
                    positionClass: 'toast-top-right'
                });
            }
        })
        .catch(error => {
            modal.hide();
            toastr.error('Error deleting service. Please try again.', 'Error!', {
                closeButton: true,
                progressBar: true,
                timeOut: 5000,
                positionClass: 'toast-top-right'
            });
        })
        .finally(() => {
            // Restore button
            confirmBtn.innerHTML = originalContent;
            confirmBtn.disabled = false;
        });
    };
}

// Delete resource functionality with toastr
function deleteResource(serviceId, resourceId, resourceName) {
    document.getElementById('resourceName').textContent = resourceName;
    document.getElementById('deleteResourceForm').action = `/admin/services/${serviceId}/resources/${resourceId}`;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteResourceModal'));
    modal.show();
    
    // Handle form submission with loading state and toastr
    const deleteForm = document.getElementById('deleteResourceForm');
    const confirmBtn = document.getElementById('confirmDeleteResourceBtn');
    
    deleteForm.onsubmit = function(e) {
        e.preventDefault();
        
        const originalContent = confirmBtn.innerHTML;
        confirmBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Deleting...';
        confirmBtn.disabled = true;
        
        // Submit the form with fetch for better control
        fetch(deleteForm.action, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            modal.hide();
            
            if (data.success || response.ok) {
                toastr.success('Resource deleted successfully!', 'Success!', {
                    closeButton: true,
                    progressBar: true,
                    timeOut: 3000,
                    positionClass: 'toast-top-right'
                });
                
                // Reload page after short delay
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                toastr.error('Error deleting resource. Please try again.', 'Error!', {
                    closeButton: true,
                    progressBar: true,
                    timeOut: 5000,
                    positionClass: 'toast-top-right'
                });
            }
        })
        .catch(error => {
            modal.hide();
            toastr.error('Error deleting resource. Please try again.', 'Error!', {
                closeButton: true,
                progressBar: true,
                timeOut: 5000,
                positionClass: 'toast-top-right'
            });
        })
        .finally(() => {
            // Restore button
            confirmBtn.innerHTML = originalContent;
            confirmBtn.disabled = false;
        });
    };
}
</script>
@endpush

