@extends('layouts.contents')

@section('content')
<div class="d-flex flex-column flex-fill overflow-auto bg-light min-vh-100">
    <!-- Header -->
    <header class="bg-white border-bottom sticky-top shadow-sm">
        <div class="container-fluid px-3 px-sm-4 py-3">
            <div class="row align-items-center">
                <div class="col-12 col-sm-6 col-md-8 mb-3 mb-sm-0">
                    <h1 class="h2 fw-bold text-dark mb-1">
                        <i class="bi bi-newspaper me-2"></i>Blog Management
                    </h1>
                    <p class="text-muted small mb-0">
                        <i class="bi bi-info-circle me-1"></i>Manage your blog posts and content
                    </p>
                </div>
                
                <!-- Search and Actions -->
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="d-flex gap-2">
                        <form method="GET" action="{{ route('blogs.index') }}" class="position-relative flex-fill">
                            <div class="position-absolute top-50 start-0 translate-middle-y ms-3">
                                <i class="bi bi-search text-muted"></i>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Search posts..." 
                                class="form-control form-control-sm ps-5">
                        </form>
                        
                        <a href="{{ route('blogs.create') }}" 
                           class="btn btn-success btn-sm d-flex align-items-center gap-1 text-nowrap">
                            <i class="bi bi-plus-lg"></i>
                            New Post
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container-fluid px-3 px-sm-4 py-4">
        <!-- Stats Cards -->
        <div class="row g-3 g-sm-4 mb-4">
            <div class="col-12 col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small fw-medium mb-1">
                                    <i class="bi bi-file-text me-1"></i>Total Posts
                                </p>
                                <h3 class="fw-bold mb-0">{{ $blogs->total() }}</h3>
                            </div>
                            <div class="bg-success bg-opacity-10 p-3 rounded-3">
                                <i class="bi bi-journal-text text-success fs-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-12 col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small fw-medium mb-1">
                                    <i class="bi bi-eye me-1"></i>Published
                                </p>
                                <h3 class="fw-bold mb-0 text-success">{{ $publishedCount }}</h3>
                            </div>
                            <div class="bg-primary bg-opacity-10 p-3 rounded-3">
                                <i class="bi bi-check-circle text-primary fs-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-12 col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small fw-medium mb-1">
                                    <i class="bi bi-pencil me-1"></i>Drafts
                                </p>
                                <h3 class="fw-bold mb-0 text-warning">{{ $draftCount }}</h3>
                            </div>
                            <div class="bg-warning bg-opacity-10 p-3 rounded-3">
                                <i class="bi bi-file-earmark-text text-warning fs-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Blog Posts Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h5 class="card-title mb-0">
                    <i class="bi bi-table me-2"></i>Blog Posts
                </h5>
            </div>
            
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0 text-muted small fw-medium text-uppercase">
                                    <i class="bi bi-file-earmark-text me-1"></i>Post
                                </th>
                                <th class="border-0 text-muted small fw-medium text-uppercase">
                                    <i class="bi bi-person me-1"></i>Author
                                </th>
                                <th class="border-0 text-muted small fw-medium text-uppercase">
                                    <i class="bi bi-tag me-1"></i>Category
                                </th>
                                <th class="border-0 text-muted small fw-medium text-uppercase">
                                    <i class="bi bi-circle-fill me-1"></i>Status
                                </th>
                                <th class="border-0 text-muted small fw-medium text-uppercase">
                                    <i class="bi bi-calendar me-1"></i>Published
                                </th>
                                <th class="border-0 text-muted small fw-medium text-uppercase">
                                    <i class="bi bi-gear me-1"></i>Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($blogs as $blog)
                            <tr>
                                <td class="py-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <!-- Image Preview Button -->
                                        @if($blog->image_url)
                                        <button onclick="showImageModal('{{ $blog->image_url }}', '{{ $blog->title }}')" 
                                                class="btn btn-outline-success btn-sm p-2 d-flex align-items-center justify-content-center" 
                                                style="width: 40px; height: 40px;">
                                            <i class="bi bi-image"></i>
                                        </button>
                                        @else
                                        <div class="bg-light border rounded d-flex align-items-center justify-content-center" 
                                             style="width: 40px; height: 40px;">
                                            <i class="bi bi-image text-muted"></i>
                                        </div>
                                        @endif
                                        
                                        <div class="flex-fill">
                                            <div class="fw-medium text-dark text-truncate">
                                                <a href="{{ route('blog.details', $blog->slug) }}" class="text-decoration-none" target="_blank">
                                                    {{ $blog->title }}
                                                </a>
                                            </div>
                                            <div class="text-muted small font-monospace">
                                                <i class="bi bi-link-45deg"></i> /{{ $blog->slug }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3">
                                    <div class="text-dark">
                                        <i class="bi bi-person me-1"></i>{{ $blog->author ?? 'Unknown' }}
                                    </div>
                                </td>
                                <td class="py-3">
                                    @if($blog->category)
                                    <span class="badge bg-success bg-opacity-10 text-success">
                                        <i class="bi bi-tag me-1"></i>{{ $blog->category }}
                                    </span>
                                    @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                        <i class="bi bi-tag me-1"></i>Uncategorized
                                    </span>
                                    @endif
                                </td>
                                <td class="py-3">
                                    @if($blog->is_published)
                                        <span class="badge bg-success d-flex align-items-center gap-1 w-fit">
                                            <i class="bi bi-check-circle me-1"></i>
                                            Published
                                        </span>
                                    @elseif($blog->published_at && $blog->published_at->isFuture())
                                        <span class="badge bg-info d-flex align-items-center gap-1 w-fit">
                                            <i class="bi bi-clock me-1"></i>
                                            Scheduled
                                        </span>
                                    @else
                                        <span class="badge bg-warning d-flex align-items-center gap-1 w-fit">
                                            <i class="bi bi-pencil me-1"></i>
                                            Draft
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 text-muted small">
                                    @if($blog->published_at)
                                        <i class="bi bi-calendar me-1"></i>{{ $blog->formatted_published_at }}
                                    @else
                                        <span class="text-muted">Not set</span>
                                    @endif
                                </td>
                                <td class="py-3">
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('blogs.edit', $blog->id) }}" 
                                           class="btn btn-link btn-sm text-primary text-decoration-none p-1 d-flex align-items-center gap-1"
                                           title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                            <span class="d-none d-md-inline">Edit</span>
                                        </a>
                                        <a href="{{ route('blog.details', $blog->slug) }}" 
                                           target="_blank"
                                           class="btn btn-link btn-sm text-info text-decoration-none p-1 d-flex align-items-center gap-1"
                                           title="Preview">
                                            <i class="bi bi-eye"></i>
                                            <span class="d-none d-md-inline">View</span>
                                        </a>
                                        <form action="{{ route('blogs.destroy', $blog->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    onclick="return confirm('Are you sure you want to delete this blog post?')"
                                                    class="btn btn-link btn-sm text-danger text-decoration-none p-1 d-flex align-items-center gap-1"
                                                    title="Delete">
                                                <i class="bi bi-trash"></i>
                                                <span class="d-none d-md-inline">Delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center">
                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mb-3" 
                                             style="width: 64px; height: 64px;">
                                            <i class="bi bi-file-text text-muted fs-3"></i>
                                        </div>
                                        <h5 class="text-dark mb-2">No blog posts found</h5>
                                        <p class="text-muted mb-4 text-center" style="max-width: 400px;">
                                            Get started by creating your first blog post and share your thoughts with the world.
                                        </p>
                                        <a href="{{ route('blogs.create') }}" 
                                           class="btn btn-success d-flex align-items-center gap-2">
                                            <i class="bi bi-plus-lg"></i>
                                            Create Blog Post
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Pagination -->
            @if($blogs->hasPages())
            <div class="card-footer bg-light">
                <div class="d-flex flex-column flex-sm-row align-items-center justify-content-between gap-3">
                    <div class="text-muted small">
                        <i class="bi bi-list-ol me-1"></i>
                        Showing <span class="fw-medium">{{ $blogs->firstItem() }}</span> to 
                        <span class="fw-medium">{{ $blogs->lastItem() }}</span> of 
                        <span class="fw-medium">{{ $blogs->total() }}</span> results
                    </div>
                    <div class="d-flex">
                        {{ $blogs->links() }}
                    </div>
                </div>
            </div>
            @endif
        </div>
    </main>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-truncate" id="imageModalLabel">
                    <i class="bi bi-image me-2"></i>Featured Image
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="" class="img-fluid rounded">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function showImageModal(imageUrl, title) {
    const modal = new bootstrap.Modal(document.getElementById('imageModal'));
    const modalImage = document.getElementById('modalImage');
    const modalTitle = document.getElementById('imageModalLabel');
    
    modalImage.src = imageUrl;
    modalImage.alt = title;
    modalTitle.textContent = title;
    modal.show();
}
</script>

<style>
/* Custom styles for better appearance */
.w-fit {
    width: fit-content !important;
}

.gap-1 {
    gap: 0.25rem !important;
}

.gap-2 {
    gap: 0.5rem !important;
}

.gap-3 {
    gap: 1rem !important;
}

/* Ensure consistent button sizing */
.btn-sm {
    font-size: 0.875rem;
}

/* Custom badge styling */
.badge {
    font-weight: 500;
}

/* Table hover effect */
.table-hover tbody tr:hover {
    background-color: rgba(0, 0, 0, 0.025);
}

/* Responsive improvements */
@media (max-width: 576px) {
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .btn-sm {
        font-size: 0.8rem;
        padding: 0.25rem 0.5rem;
    }
    
    .card-header h5 {
        font-size: 1rem;
    }
}

/* Add some spacing to icons in table headers */
th i {
    margin-right: 0.25rem;
}
</style>

@endsection