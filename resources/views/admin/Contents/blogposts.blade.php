@extends('layouts.contents')

@section('content')

    <div class="blog-management-container">
        {{-- Page Header --}}
        <div class="page-header-card mb-4">
            <div class="page-header-content">
                <div class="header-text">
                    <h1 class="page-title">
                        <i class="fa fa-newspaper me-2"></i>
                        Blog Management
                    </h1>
                    <p class="page-subtitle">
                        <i class="fa fa-info-circle me-2"></i>
                        Manage your blog posts and content
                    </p>
                </div>
                
                <!-- Search and Actions -->
                <div class="header-actions">
                    <form method="GET" action="{{ route('admin.blogs.index') }}" class="search-form">
                        <div class="search-wrapper">
                            <i class="fa fa-search search-icon"></i>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Search posts..." 
                                class="form-control search-input">
                        </div>
                    </form>
                    
                    <a href="{{ route('admin.blogs.create') }}" 
                       class="btn btn-primary btn-enhanced">
                        <i class="fa fa-plus me-2"></i>
                        New Post
                    </a>
                </div>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="stats-grid mb-4">
            <div class="stat-card">
                <div class="stat-card-body">
                    <div class="stat-icon" style="background: linear-gradient(135deg, var(--primary-green) 0%, var(--primary-green-dark) 100%);">
                        <i class="fa fa-file-text"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-number">{{ $blogs->total() }}</h3>
                        <p class="stat-label">Total Posts</p>
                    </div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-card-body">
                    <div class="stat-icon" style="background: linear-gradient(135deg, var(--primary-green-dark) 0%, #0F3D0F 100%);">
                        <i class="fa fa-check-circle"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-number">{{ $publishedCount }}</h3>
                        <p class="stat-label">Published</p>
                    </div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-card-body">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);">
                        <i class="fa fa-edit"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-number">{{ $draftCount }}</h3>
                        <p class="stat-label">Drafts</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Blog Posts Table --}}
        <div class="content-card">
            <div class="content-card-header">
                <h5 class="card-title">
                    <i class="fa fa-table me-2"></i>
                    Blog Posts
                </h5>
            </div>
            
            <div class="content-card-body">
                <div class="table-responsive">
                    <table class="enhanced-table">
                        <thead>
                            <tr>
                                <th>
                                    <i class="fa fa-file-text me-2"></i>Post
                                </th>
                                <th>
                                    <i class="fa fa-user me-2"></i>Author
                                </th>
                                <th>
                                    <i class="fa fa-tag me-2"></i>Category
                                </th>
                                <th>
                                    <i class="fa fa-circle me-2"></i>Status
                                </th>
                                <th>
                                    <i class="fa fa-calendar me-2"></i>Published
                                </th>
                                <th>
                                    <i class="fa fa-cog me-2"></i>Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($blogs as $blog)
                            <tr>
                                <td>
                                    <div class="post-info">
                                        <!-- Image Preview Button -->
                                        @if($blog->image_url)
                                        <button onclick="showImageModal('{{ $blog->image_url }}', '{{ $blog->title }}')" 
                                                class="image-preview-btn" 
                                                title="View Image">
                                            <i class="fa fa-image"></i>
                                        </button>
                                        @else
                                        <div class="image-placeholder">
                                            <i class="fa fa-image"></i>
                                        </div>
                                        @endif
                                        
                                        <div class="post-details">
                                            <div class="post-title">
                                                <a href="{{ route('blog.details', $blog->slug) }}" 
                                                   class="post-link" 
                                                   target="_blank">
                                                    {{ $blog->title }}
                                                </a>
                                            </div>
                                            <div class="post-slug">
                                                <i class="fa fa-link me-1"></i>/{{ $blog->slug }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="author-info">
                                        <i class="fa fa-user me-2"></i>{{ $blog->author ?? 'Unknown' }}
                                    </div>
                                </td>
                                <td>
                                    @if($blog->category)
                                    <span class="category-badge category-filled">
                                        <i class="fa fa-tag me-1"></i>{{ $blog->category }}
                                    </span>
                                    @else
                                    <span class="category-badge category-empty">
                                        <i class="fa fa-tag me-1"></i>Uncategorized
                                    </span>
                                    @endif
                                </td>
                                <td>
                                    @if($blog->is_published)
                                        <span class="status-badge status-published">
                                            <i class="fa fa-check-circle me-1"></i>
                                            Published
                                        </span>
                                    @elseif($blog->published_at && $blog->published_at->isFuture())
                                        <span class="status-badge status-scheduled">
                                            <i class="fa fa-clock me-1"></i>
                                            Scheduled
                                        </span>
                                    @else
                                        <span class="status-badge status-draft">
                                            <i class="fa fa-edit me-1"></i>
                                            Draft
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="publish-date">
                                        @if($blog->published_at)
                                            <i class="fa fa-calendar me-1"></i>{{ $blog->formatted_published_at }}
                                        @else
                                            <span class="text-muted">Not set</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.blogs.edit', $blog) }}" 
                                            class="action-btn edit-btn"
                                            title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </a>

                                        <a href="{{ route('blog.details', $blog->slug) }}" 
                                           target="_blank"
                                           class="action-btn view-btn"
                                           title="Preview">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <form action="{{ route('admin.blogs.destroy', $blog) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        onclick="return confirm('Are you sure you want to delete this blog post?')"
                                                        class="action-btn delete-btn"
                                                        title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>

                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="empty-state">
                                    <div class="empty-content">
                                        <div class="empty-icon">
                                            <i class="fa fa-file-text"></i>
                                        </div>
                                        <h5 class="empty-title">No blog posts found</h5>
                                        <p class="empty-description">
                                            Get started by creating your first blog post and share your thoughts with the world.
                                        </p>
                                        <a href="{{ route('admin.blogs.create') }}" 
                                           class="btn btn-primary btn-enhanced">
                                            <i class="fa fa-plus me-2"></i>
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
            <div class="pagination-footer">
                <div class="pagination-info">
                    <i class="fa fa-list me-1"></i>
                    Showing <span class="fw-medium">{{ $blogs->firstItem() }}</span> to 
                    <span class="fw-medium">{{ $blogs->lastItem() }}</span> of 
                    <span class="fw-medium">{{ $blogs->total() }}</span> results
                </div>
                <div class="pagination-links">
                    {{ $blogs->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">
                        <i class="fa fa-image me-2"></i>Featured Image
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" alt="" class="img-fluid rounded">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa fa-times me-1"></i>Close
                    </button>
                </div>
            </div>
        </div>
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

        /* Blog Management Container */
        .blog-management-container {
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
            gap: 2rem;
        }

        .page-title {
            font-size: 1.75rem;
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
            display: flex;
            align-items: center;
        }

        .header-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .search-form {
            position: relative;
        }

        .search-wrapper {
            position: relative;
        }

        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--light-text);
            z-index: 2;
        }

        .search-input {
            padding-left: 2.5rem;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            width: 250px;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            border-color: var(--primary-green);
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
            outline: none;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
        }

        .stat-card {
            background: var(--white);
            border-radius: 16px;
            box-shadow: var(--shadow);
            overflow: hidden;
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-hover);
        }

        .stat-card-body {
            padding: 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .stat-icon i {
            font-size: 1.25rem;
            color: var(--white);
        }

        .stat-content {
            flex: 1;
        }

        .stat-number {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--dark-text);
            margin: 0;
            line-height: 1;
        }

        .stat-label {
            color: var(--light-text);
            font-size: 0.85rem;
            margin: 0.25rem 0;
            font-weight: 500;
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
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--primary-green);
            margin: 0;
            display: flex;
            align-items: center;
        }

        .content-card-body {
            padding: 0;
        }

        /* Enhanced Table */
        .enhanced-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin: 0;
        }

        .enhanced-table thead th {
            background: var(--ultra-light-green);
            color: var(--primary-green);
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 1rem 1.5rem;
            border-bottom: 2px solid var(--light-green);
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .enhanced-table tbody tr {
            transition: all 0.2s ease;
            border-bottom: 1px solid var(--border-color);
        }

        .enhanced-table tbody tr:last-child {
            border-bottom: none;
        }

        .enhanced-table tbody tr:hover {
            background: var(--ultra-light-green);
        }

        .enhanced-table tbody td {
            padding: 1rem 1.5rem;
            vertical-align: middle;
        }

        /* Post Info */
        .post-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .image-preview-btn {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            border: 2px solid var(--primary-green);
            background: var(--light-green);
            color: var(--primary-green);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .image-preview-btn:hover {
            background: var(--primary-green);
            color: var(--white);
        }

        .image-placeholder {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            border: 2px solid var(--border-color);
            background: var(--hover-bg);
            color: var(--light-text);
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
            color: var(--dark-text);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .post-link:hover {
            color: var(--primary-green);
        }

        .post-slug {
            color: var(--light-text);
            font-size: 0.8rem;
            font-family: monospace;
        }

        .author-info {
            color: var(--medium-text);
            font-weight: 500;
        }

        /* Badges */
        .category-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .category-filled {
            background: var(--light-green);
            color: var(--primary-green);
            border: 1px solid var(--secondary-green);
        }

        .category-empty {
            background: var(--hover-bg);
            color: var(--light-text);
            border: 1px solid var(--border-color);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            width: fit-content;
        }

        .status-published {
            background: var(--ultra-light-green);
            color: var(--primary-green);
            border: 1px solid var(--secondary-green);
        }

        .status-scheduled {
            background: #EFF6FF;
            color: #3B82F6;
            border: 1px solid #BFDBFE;
        }

        .status-draft {
            background: #FFFBEB;
            color: #D97706;
            border: 1px solid #fde68a;
        }

        .publish-date {
            color: var(--light-text);
            font-size: 0.875rem;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            transition: all 0.2s ease;
            text-decoration: none;
            border: none;
            cursor: pointer;
        }

        .edit-btn {
            background: var(--light-green);
            color: var(--primary-green-dark);
        }

        .edit-btn:hover {
            background: var(--primary-green-dark);
            color: var(--white);
            transform: translateY(-1px);
        }

        .view-btn {
            background: #EFF6FF;
            color: #3B82F6;
        }

        .view-btn:hover {
            background: #3B82F6;
            color: var(--white);
            transform: translateY(-1px);
        }

        .delete-btn {
            background: #FEF2F2;
            color: #DC2626;
        }

        .delete-btn:hover {
            background: #DC2626;
            color: var(--white);
            transform: translateY(-1px);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
        }

        .empty-content {
            max-width: 400px;
            margin: 0 auto;
        }

        .empty-icon {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: var(--hover-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
        }

        .empty-icon i {
            font-size: 2rem;
            color: var(--light-text);
        }

        .empty-title {
            color: var(--dark-text);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .empty-description {
            color: var(--light-text);
            margin-bottom: 1.5rem;
        }

        /* Enhanced Buttons */
        .btn-enhanced {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            display: flex;
            align-items: center;
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

        /* Pagination */
        .pagination-footer {
            padding: 1rem 2rem;
            background: var(--ultra-light-green);
            border-top: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .pagination-info {
            color: var(--light-text);
            font-size: 0.875rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .page-header-content {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .header-actions {
                width: 100%;
                flex-direction: column;
                align-items: stretch;
            }

            .search-input {
                width: 100%;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .content-card-header {
                padding: 1rem;
            }

            .enhanced-table th,
            .enhanced-table td {
                padding: 0.75rem 1rem;
            }

            .pagination-footer {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
        }

        @media (max-width: 576px) {
            .post-info {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }

            .action-buttons {
                flex-wrap: wrap;
            }
        }
    </style>

    <script>
    function showImageModal(imageUrl, title) {
        const modal = new bootstrap.Modal(document.getElementById('imageModal'));
        const modalImage = document.getElementById('modalImage');
        const modalTitle = document.getElementById('imageModalLabel');
        
        modalImage.src = imageUrl;
        modalImage.alt = title;
        modalTitle.innerHTML = '<i class="fa fa-image me-2"></i>' + title;
        modal.show();
    }
    </script>
@endsection
