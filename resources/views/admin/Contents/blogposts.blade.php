@extends('layouts.app')

@section('title', 'Blog Management')

@section('content')
<div class="dashboard-container">
    {{-- Compact Header --}}
    <div class="dashboard-header mb-2">
        <div class="header-content">
            <h1 class="dashboard-title">
                <i class="fas fa-newspaper me-2"></i>
                Blog Management
            </h1>
            <div class="header-meta">
                <span class="badge bg-secondary">{{ $blogs->total() }} posts</span>
                <small class="text-muted">Updated: {{ now()->format('g:i a') }}</small>
            </div>
        </div>
        <div class="header-actions">
            <form method="GET" action="{{ route('admin.blogs.index') }}" class="search-form me-2">
                <div class="search-input-wrapper">
                    <i class="fas fa-search"></i>
                    <input type="text" name="search" 
                           value="{{ request('search') }}"
                           placeholder="Search posts..."
                           class="form-control form-control-sm">
                </div>
            </form>
            <button onclick="window.location.href='{{ route('admin.blogs.create') }}'" class="btn btn-sm btn-success me-2">
                <i class="fas fa-plus me-1"></i>
                New Post
            </button>
            <button id="refreshBlogs" class="btn btn-sm btn-secondary">
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

    {{-- Compact Stats Cards --}}
    <div class="stats-row mb-2">
        <div class="stat-item">
            <div class="stat-icon total-posts">
                <i class="fas fa-file-text"></i>
            </div>
            <div class="stat-info">
                <span class="stat-number">{{ $blogs->total() }}</span>
                <span class="stat-label">Total Posts</span>
            </div>
        </div>
        
        <div class="stat-item">
            <div class="stat-icon published-posts">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-info">
                <span class="stat-number">{{ $publishedCount }}</span>
                <span class="stat-label">Published</span>
            </div>
        </div>
        
        <div class="stat-item">
            <div class="stat-icon draft-posts">
                <i class="fas fa-edit"></i>
            </div>
            <div class="stat-info">
                <span class="stat-number">{{ $draftCount }}</span>
                <span class="stat-label">Drafts</span>
            </div>
        </div>
    </div>

    {{-- Blog Posts Table --}}
    <div class="content-card">
        <div class="content-card-header">
            <div class="header-content">
                <h4 class="card-title">
                    <i class="fas fa-list me-2"></i>
                    Blog Posts
                </h4>
                @if($blogs->count() > 0)
                <p class="card-subtitle mb-0">
                    Showing {{ $blogs->firstItem() }} to {{ $blogs->lastItem() }} of {{ $blogs->total() }} posts
                </p>
                @endif
            </div>
            <div class="header-actions">
                <div class="d-flex gap-2">
                    <span class="badge published-badge">
                        <i class="fas fa-check-circle me-1"></i>
                        {{ $publishedCount }} Published
                    </span>
                    <span class="badge draft-badge">
                        <i class="fas fa-edit me-1"></i>
                        {{ $draftCount }} Drafts
                    </span>
                </div>
            </div>
        </div>
        
        <div class="content-card-body">
            <div class="table-responsive">
                <table class="compact-table">
                    <thead>
                        <tr>
                            <th>Post</th>
                            <th>Author</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Published</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($blogs as $blog)
                        <tr class="{{ !$blog->is_published ? 'draft-row' : '' }}">
                            <td>
                                <div class="post-info">
                                    {{-- Image Preview Button --}}
                                    @if($blog->image_url)
                                    <button onclick="showImageModal('{{ $blog->image_url }}', '{{ addslashes($blog->title) }}')" 
                                            class="image-preview-btn" 
                                            title="View Image">
                                        <i class="fas fa-image"></i>
                                    </button>
                                    @else
                                    <div class="image-placeholder">
                                        <i class="fas fa-image"></i>
                                    </div>
                                    @endif
                                    
                                    <div class="post-details">
                                        <div class="post-title">
                                            <a href="{{ route('blog.details', $blog->slug) }}" 
                                               class="post-link" 
                                               target="_blank"
                                               title="{{ $blog->title }}">
                                                {{ Str::limit($blog->title, 40) }}
                                            </a>
                                        </div>
                                        <div class="post-slug">
                                            <i class="fas fa-link me-1"></i>/{{ Str::limit($blog->slug, 30) }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="author-info">
                                    <div class="d-flex align-items-center">
                                        <div class="user-avatar me-2" style="width: 24px; height: 24px; font-size: 0.7rem;">
                                            {{ substr($blog->author ?? 'U', 0, 1) }}
                                        </div>
                                        <span>{{ Str::limit($blog->author ?? 'Unknown', 12) }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($blog->category)
                                    <span class="category-badge">
                                        <i class="fas fa-tag me-1"></i>
                                        {{ $blog->category }}
                                    </span>
                                @else
                                    <span class="uncategorized-badge">
                                        <i class="fas fa-folder-open me-1"></i>
                                        Uncategorized
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($blog->is_published)
                                    <span class="status-badge status-open">
                                        <i class="fas fa-check-circle me-1"></i>
                                        Published
                                    </span>
                                @elseif($blog->published_at && $blog->published_at->isFuture())
                                    <span class="status-badge status-pending">
                                        <i class="fas fa-clock me-1"></i>
                                        Scheduled
                                    </span>
                                @else
                                    <span class="status-badge priority-medium">
                                        <i class="fas fa-edit me-1"></i>
                                        Draft
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="publish-date">
                                    @if($blog->published_at)
                                        <div class="date-main">{{ $blog->published_at->format('M d, Y') }}</div>
                                        <small class="text-muted">{{ $blog->published_at->diffForHumans() }}</small>
                                    @else
                                        <span class="text-muted">Not set</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button onclick="window.location.href='{{ route('admin.blogs.edit', $blog) }}'" 
                                       class="action-btn edit-btn" 
                                       title="Edit Post">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <button onclick="window.open('{{ route('blog.details', $blog->slug) }}', '_blank')"
                                       class="action-btn view-btn"
                                       title="Preview Post">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    <button class="action-btn delete-btn" 
                                            onclick="deleteBlog({{ $blog->id }}, '{{ addslashes(Str::limit($blog->title, 50)) }}')"
                                            title="Delete Post">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="empty-state">
                                <div class="empty-content">
                                    <i class="fas fa-newspaper"></i>
                                    <h6>No Blog Posts Found</h6>
                                    <p>No blog posts match your search criteria.</p>
                                    <button onclick="window.location.href='{{ route('admin.blogs.create') }}'" 
                                            class="btn btn-success btn-sm mt-2">
                                        <i class="fas fa-plus me-1"></i>
                                        Create First Post
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($blogs->hasPages())
            <div class="pagination-wrapper">
                {{ $blogs->withQueryString()->onEachSide(1)->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Image Modal --}}
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: var(--secondary); color: white;">
                <h5 class="modal-title" id="imageModalLabel">
                    <i class="fas fa-image me-2"></i>
                    Featured Image
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="" class="img-fluid rounded">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Close
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: var(--danger); color: white;">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-trash me-2"></i>
                    Delete Blog Post
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <i class="fas fa-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">Are you sure?</h5>
                    <p class="text-muted">You are about to delete the blog post:</p>
                    <strong id="blogTitle" class="text-danger"></strong>
                    <p class="text-muted mt-2">This action cannot be undone!</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancel
                </button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" id="confirmDeleteBtn">
                        <i class="fas fa-trash me-2"></i>Delete Post
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Updated CSS Variables for Blog Management */
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

/* Compact Dashboard Styles */
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
    align-items: center;
}

.header-actions .btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    height: 28px;
}

/* Search Form */
.search-form {
    min-width: 200px;
}

.search-input-wrapper {
    position: relative;
}

.search-input-wrapper i {
    position: absolute;
    left: 0.75rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--gray-500);
    font-size: 0.85rem;
}

.search-input-wrapper .form-control {
    padding-left: 2.25rem;
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

/* Compact Stats Row */
.stats-row {
    display: flex;
    gap: 1rem;
    background: var(--white);
    border-radius: var(--border-radius);
    padding: 0.75rem;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--gray-200);
}

.stat-item {
    flex: 1;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.stat-icon {
    width: 36px;
    height: 36px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.stat-icon i {
    font-size: 1rem;
    color: var(--white);
}

.total-posts {
    background: linear-gradient(135deg, var(--secondary), var(--secondary-dark));
}

.published-posts {
    background: linear-gradient(135deg, var(--success), var(--primary-dark));
}

.draft-posts {
    background: linear-gradient(135deg, var(--warning), #D97706);
}

.stat-info {
    display: flex;
    flex-direction: column;
}

.stat-number {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--gray-800);
    line-height: 1;
}

.stat-label {
    color: var(--gray-500);
    font-size: 0.75rem;
    font-weight: 500;
}

/* Content Card */
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

.content-card-body {
    padding: 0;
}

/* Header Action Badges */
.published-badge {
    background: #F0FDF4 !important;
    color: #166534 !important;
    border: 1px solid #BBF7D0;
    font-size: 0.7rem;
    padding: 0.3rem 0.6rem;
}

.draft-badge {
    background: #FFFBEB !important;
    color: #D97706 !important;
    border: 1px solid #FDE68A;
    font-size: 0.7rem;
    padding: 0.3rem 0.6rem;
}

/* Compact Table Styles */
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

/* Draft Row Styling */
.draft-row {
    background: #FFFBEB !important;
    opacity: 0.8;
}

.draft-row:hover {
    background: #FEF3C7 !important;
    opacity: 0.9;
}

/* Post Info */
.post-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.image-preview-btn {
    width: 32px;
    height: 32px;
    border-radius: 6px;
    border: 2px solid var(--secondary);
    background: var(--gray-100);
    color: var(--secondary);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: var(--transition);
    padding: 0;
}

.image-preview-btn:hover {
    background: var(--secondary);
    color: var(--white);
}

.image-placeholder {
    width: 32px;
    height: 32px;
    border-radius: 6px;
    border: 2px solid var(--gray-300);
    background: var(--gray-100);
    color: var(--gray-400);
    display: flex;
    align-items: center;
    justify-content: center;
}

.post-details {
    flex: 1;
    min-width: 0;
}

.post-title {
    font-weight: 500;
    margin-bottom: 0.25rem;
}

.post-link {
    color: var(--gray-800);
    text-decoration: none;
    transition: var(--transition);
}

.post-link:hover {
    color: var(--secondary);
    text-decoration: none;
}

.post-slug {
    color: var(--gray-500);
    font-size: 0.75rem;
    font-family: 'Monaco', 'Menlo', monospace;
}

/* Author Info */
.author-info {
    font-size: 0.8rem;
}

.user-avatar {
    width: 24px;
    height: 24px;
    background: linear-gradient(135deg, var(--secondary), var(--secondary-dark));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--white);
    font-size: 0.7rem;
    font-weight: 600;
    box-shadow: var(--shadow-sm);
}

/* Category Badges */
.category-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
    background: #F0FDF4;
    color: #166534;
    border: 1px solid #BBF7D0;
}

.uncategorized-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
    background: var(--gray-100);
    color: var(--gray-600);
    border: 1px solid var(--gray-300);
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

.status-open {
    background: #F0FDF4;
    color: #047857;
    border: 1px solid #BBF7D0;
}

.status-pending {
    background: #EFF6FF;
    color: #1D4ED8;
    border: 1px solid #BFDBFE;
}

.priority-medium {
    background: #FFFBEB;
    color: #D97706;
    border: 1px solid #FDE68A;
}

/* Publish Date */
.publish-date {
    font-size: 0.8rem;
    color: var(--gray-600);
}

.date-main {
    font-weight: 500;
    color: var(--gray-800);
}

.publish-date small {
    display: block;
    margin-top: 0.15rem;
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

.view-btn {
    background: #F3F4F6;
    color: #4B5563;
    border: 1px solid #D1D5DB;
}

.view-btn:hover {
    background: #4B5563;
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

/* Empty State */
.empty-state {
    text-align: center;
    padding: 2rem 1rem;
}

.empty-content i {
    font-size: 1.5rem;
    color: var(--gray-300);
    margin-bottom: 0.5rem;
}

.empty-content h6 {
    font-size: 0.9rem;
    color: var(--gray-600);
    margin-bottom: 0.25rem;
}

.empty-content p {
    font-size: 0.8rem;
    color: var(--gray-500);
    margin: 0;
}

/* Pagination */
.pagination-wrapper {
    padding: 0.75rem;
    border-top: 1px solid var(--gray-200);
}

/* Override Bootstrap pagination colors */
.pagination .page-link {
    color: var(--secondary);
    border-color: var(--gray-300);
}

.pagination .page-link:hover {
    color: var(--secondary-dark);
    background-color: var(--gray-50);
    border-color: var(--gray-300);
}

.pagination .page-item.active .page-link {
    background-color: var(--secondary);
    border-color: var(--secondary);
    color: white;
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
        flex-wrap: wrap;
    }
    
    .search-form {
        min-width: 150px;
        flex: 1;
    }
    
    .stats-row {
        flex-direction: column;
        gap: 0.5rem;
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
}

@media (max-width: 480px) {
    .post-info {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .post-details {
        width: 100%;
    }
    
    .action-buttons {
        flex-direction: column;
        gap: 0.15rem;
    }
    
    .status-badge,
    .category-badge,
    .uncategorized-badge {
        font-size: 0.7rem;
        padding: 0.2rem 0.4rem;
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
    
    // Refresh button functionality
    const refreshBtn = document.getElementById('refreshBlogs');
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
    
    // Auto-submit search form
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                this.closest('form').submit();
            }, 500);
        });
    }
});

// Show image modal function
function showImageModal(imageUrl, title) {
    const modal = new bootstrap.Modal(document.getElementById('imageModal'));
    const modalImage = document.getElementById('modalImage');
    const modalTitle = document.getElementById('imageModalLabel');
    
    modalImage.src = imageUrl;
    modalImage.alt = title;
    modalTitle.innerHTML = '<i class="fas fa-image me-2"></i>' + title;
    modal.show();
}

// Delete blog functionality with toastr
function deleteBlog(blogId, blogTitle) {
    document.getElementById('blogTitle').textContent = blogTitle;
    document.getElementById('deleteForm').action = `/admin/blogs/${blogId}`;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
    
    // Handle form submission with loading state and toastr
    const deleteForm = document.getElementById('deleteForm');
    const confirmBtn = document.getElementById('confirmDeleteBtn');
    
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
                toastr.success('Blog post deleted successfully!', 'Success!', {
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
                toastr.error('Error deleting blog post. Please try again.', 'Error!', {
                    closeButton: true,
                    progressBar: true,
                    timeOut: 5000,
                    positionClass: 'toast-top-right'
                });
            }
        })
        .catch(error => {
            modal.hide();
            toastr.error('Error deleting blog post. Please try again.', 'Error!', {
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
