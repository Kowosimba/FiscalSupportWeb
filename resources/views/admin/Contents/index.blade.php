@extends('layouts.contents')

@section('content')
{{-- Dashboard Navigation Tabs --}}
<div class="dashboard-nav-wrapper mb-4">
    <ul class="panel-nav nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.index') || request()->routeIs('admin.tickets.*') ? 'active' : '' }}"
               href="{{ route('admin.index') }}">
                <i class="fa fa-tasks me-2"></i>
                Faults Allocation
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('content.*') || request()->routeIs('blogs.*') || request()->routeIs('admin.faqs.*') || request()->routeIs('admin.services.*') || request()->routeIs('admin.subscribers.*') || request()->routeIs('admin.newsletters.*') || request()->routeIs('faq-categories.*') ? 'active' : '' }}"
               href="{{ route('admin.content.index') }}">
                <i class="fa fa-cog me-2"></i>
                Manage Content
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.call-logs.*') ? 'active' : '' }}" 
               href="{{ route('admin.call-logs.index') }}">
                <i class="fa fa-phone me-2"></i>
                Call Logs
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}"
               href="{{ route('admin.contacts.index') }}">
                <i class="fa fa-users me-2"></i>
                Customer Contacts
            </a>
        </li>
    </ul>
</div>


    <!-- Content Dashboard -->
    <div class="content-dashboard">
        {{-- Stats Overview --}}
        <div class="stats-grid mb-5">
            @php
            $contentStats = [
                'blogs' => [
                    'label' => 'Total Blog Posts',
                    'icon' => 'fa-newspaper',
                    'count' => $blogCount ?? 0,
                    'color' => 'var(--primary-green)',
                    'bg' => 'linear-gradient(135deg, var(--primary-green) 0%, var(--primary-green-dark) 100%)',
                    'route' => route('admin.blogs.index')
                ],
                'published_blogs' => [
                    'label' => 'Published Blogs',
                    'icon' => 'fa-check-circle',
                    'count' => $publishedBlogCount ?? 0,
                    'color' => 'var(--primary-green-dark)',
                    'bg' => 'linear-gradient(135deg, var(--primary-green-dark) 0%, #0F3D0F 100%)',
                    'route' => route('admin.blogs.index', ['filter' => 'published'])
                ],
                'draft_blogs' => [
                    'label' => 'Draft Blogs',
                    'icon' => 'fa-edit',
                    'count' => $draftBlogCount ?? 0,
                    'color' => '#F59E0B',
                    'bg' => 'linear-gradient(135deg, #F59E0B 0%, #D97706 100%)',
                    'route' => route('admin.blogs.index', ['filter' => 'drafts'])
                ],
                'faqs' => [
                    'label' => 'Active FAQs',
                    'icon' => 'fa-question-circle',
                    'count' => $activeFaqCount ?? 0,
                    'color' => '#8B5CF6',
                    'bg' => 'linear-gradient(135deg, #8B5CF6 0%, #7C3AED 100%)',
                    'route' => route('admin.faqs.index')
                ],
            ];
            @endphp

            @foreach ($contentStats as $key => $data)
                <div class="stat-card">
                    <a href="{{ $data['route'] }}" class="stat-link">
                        <div class="stat-card-body">
                            <div class="stat-icon" style="background: {{ $data['bg'] }};">
                                <i class="fa {{ $data['icon'] }}"></i>
                            </div>
                            <div class="stat-content">
                                <h3 class="stat-number">{{ $data['count'] }}</h3>
                                <p class="stat-label">{{ $data['label'] }}</p>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        {{-- Recent Content Sections --}}
        <div class="row">
            {{-- Recent Blog Posts --}}
            <div class="col-md-6 mb-4">
                <div class="content-card">
                    <div class="content-card-header">
                        <div class="header-content">
                            <h5 class="card-title">
                                <i class="fa fa-newspaper me-2"></i>
                                Recent Blog Posts
                            </h5>
                            <p class="card-subtitle">Latest blog posts and their status</p>
                        </div>
                        <a href="{{ route('admin.blogs.index') }}" class="btn btn-primary">
                            <i class="fa fa-external-link-alt me-2"></i>
                            View All
                        </a>
                    </div>
                    <div class="content-card-body">
                        <div class="table-responsive">
                            <table class="enhanced-table">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($recentBlogs as $blog)
                                    <tr>
                                        <td>
                                            <div class="content-title">{{ Str::limit($blog->title, 30) }}</div>
                                        </td>
                                        <td>
                                            <span class="status-badge status-{{ $blog->isPublished() ? 'published' : 'draft' }}">
                                                @if($blog->isPublished())
                                                    <i class="fa fa-check me-1"></i>
                                                    Published
                                                @else
                                                    <i class="fa fa-edit me-1"></i>
                                                    Draft
                                                @endif
                                            </span>
                                        </td>
                                        <td>
                                            <span class="update-time">{{ $blog->created_at->format('M d, Y') }}</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.blogs.edit', $blog->id) }}"
                                               class="action-btn edit-btn"
                                               title="Edit Post">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="empty-state">
                                                <div class="empty-content">
                                                    <i class="fa fa-inbox"></i>
                                                    <p>No blog posts found.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- FAQ Categories --}}
            <div class="col-md-6 mb-4">
                <div class="content-card">
                    <div class="content-card-header">
                        <div class="header-content">
                            <h5 class="card-title">
                                <i class="fa fa-question-circle me-2"></i>
                                FAQ Categories
                            </h5>
                            <p class="card-subtitle">Categories and their active FAQs</p>
                        </div>
                        <a href="{{ route('admin.faqs.index') }}" class="btn btn-primary">
                            <i class="fa fa-external-link-alt me-2"></i>
                            Manage FAQs
                        </a>
                    </div>
                    <div class="content-card-body">
                        <div class="table-responsive">
                            <table class="enhanced-table">
                                <thead>
                                    <tr>
                                        <th>Category</th>
                                        <th>Active FAQs</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($faqCategories as $category)
                                    <tr>
                                        <td>
                                            <div class="content-title">{{ $category->name }}</div>
                                        </td>
                                        <td>
                                            <span class="count-badge">
                                                {{ $category->activeFaqs->count() }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.faqs.index', ['category' => $category->id]) }}"
                                               class="action-btn view-btn"
                                               title="View FAQs">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="empty-state">
                                                <div class="empty-content">
                                                    <i class="fa fa-inbox"></i>
                                                    <p>No FAQ categories found.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="quick-actions mb-4">
            <h5 class="section-title mb-3">
                <i class="fa fa-bolt me-2"></i>
                Quick Actions
            </h5>
            <div class="actions-grid">
                <a href="{{ route('admin.blogs.create') }}" class="action-card">
                    <div class="action-icon">
                        <i class="fa fa-plus"></i>
                    </div>
                    <div class="action-content">
                        <h6>New Blog Post</h6>
                        <p>Create a new blog article</p>
                    </div>
                </a>
                <a href="{{ route('admin.faqs.create') }}" class="action-card">
                    <div class="action-icon">
                        <i class="fa fa-question"></i>
                    </div>
                    <div class="action-content">
                        <h6>Add FAQ</h6>
                        <p>Create a new FAQ item</p>
                    </div>
                </a>
                <a href="{{ route('admin.services.index') }}" class="action-card">
                    <div class="action-icon">
                        <i class="fa fa-concierge-bell"></i>
                    </div>
                    <div class="action-content">
                        <h6>Manage Services</h6>
                        <p>Update service offerings</p>
                    </div>
                </a>
                <a href="{{ route('admin.subscribers.index') }}" class="action-card">
                    <div class="action-icon">
                        <i class="fa fa-envelope"></i>
                    </div>
                    <div class="action-content">
                        <h6>Newsletter</h6>
                        <p>Manage subscribers</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <style>
        /* Dashboard Navigation - Matching tickets page */
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

        /* Content Dashboard Styles */
        .content-dashboard {
            padding: 0;
        }

        /* Stats Grid - Matching tickets page */
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

        .stat-link {
            text-decoration: none;
            color: inherit;
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

        /* Content Cards - Matching tickets page styling */
        .content-card {
            background: var(--white);
            border-radius: 16px;
            box-shadow: var(--shadow);
            overflow: hidden;
            border: 1px solid var(--border-color);
            height: 100%;
        }

        .content-card-header {
            padding: 1.5rem 2rem;
            background: linear-gradient(135deg, var(--ultra-light-green) 0%, var(--light-green) 100%);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-content .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--primary-green);
            margin: 0;
            display: flex;
            align-items: center;
        }

        .header-content .card-subtitle {
            color: var(--light-text);
            font-size: 0.9rem;
            margin: 0.25rem 0 0 0;
        }

        .content-card-header .btn {
            padding: 0.4rem 0.8rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--primary-green-dark) 100%);
            color: var(--white);
        }

        .content-card-header .btn:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-hover);
        }

        .content-card-body {
            padding: 0;
        }

        /* Enhanced Table - Matching tickets page */
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

        .content-title {
            font-weight: 500;
            color: var(--dark-text);
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
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
        }

        .status-published {
            background: var(--ultra-light-green);
            color: var(--primary-green);
            border: 1px solid var(--secondary-green);
        }

        .status-draft {
            background: #FFFBEB;
            color: #D97706;
            border: 1px solid #fde68a;
        }

        .count-badge {
            background: var(--light-green);
            color: var(--primary-green-dark);
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-weight: 600;
            font-size: 0.875rem;
            border: 1px solid var(--secondary-green);
        }

        .update-time {
            color: var(--light-text);
            font-size: 0.875rem;
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
        }

        .view-btn {
            background: var(--light-green);
            color: var(--primary-green);
        }

        .view-btn:hover {
            background: var(--primary-green);
            color: var(--white);
            transform: translateY(-1px);
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

        .empty-state {
            text-align: center;
            padding: 3rem 1.5rem;
        }

        .empty-content i {
            font-size: 3rem;
            color: var(--light-text);
            margin-bottom: 1rem;
        }

        .empty-content p {
            color: var(--light-text);
            font-size: 1.1rem;
            margin: 0;
        }

        /* Quick Actions */
        .section-title {
            color: var(--primary-green-dark);
            font-weight: 600;
            display: flex;
            align-items: center;
        }

        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .action-card {
            background: var(--white);
            border-radius: 16px;
            box-shadow: var(--shadow);
            padding: 1.5rem;
            transition: all 0.3s ease;
            text-decoration: none;
            color: inherit;
            border: 1px solid var(--border-color);
        }

        .action-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-hover);
            border-color: var(--primary-green);
        }

        .action-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--primary-green-dark) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }

        .action-icon i {
            color: var(--white);
            font-size: 1.25rem;
        }

        .action-content h6 {
            font-size: 1rem;
            font-weight: 600;
            margin: 0 0 0.25rem 0;
            color: var(--dark-text);
        }

        .action-content p {
            font-size: 0.85rem;
            color: var(--light-text);
            margin: 0;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .stat-card-body {
                padding: 1rem;
            }

            .content-card-header {
                padding: 1rem;
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .enhanced-table {
                font-size: 0.875rem;
            }

            .enhanced-table th,
            .enhanced-table td {
                padding: 0.75rem 1rem;
            }

            .actions-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection
