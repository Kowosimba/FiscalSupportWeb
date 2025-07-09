<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Content Management Dashboard">
    <title>@yield('title', 'Content Management - Dashboard')</title>

    <!-- Fonts & Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">

    <style>
        :root {
            /* Green Theme Palette */
            --primary-green: #064e3b;
            --primary-green-dark: #059669;
            --primary-green-light: #34d399;
            --primary-green-lighter: #6ee7b7;
            --secondary-green: #d1fae5;
            --accent-green: #a7f3d0;
            --success-green: #22c55e;
            --emerald-600: #059669;
            --emerald-700: #047857;
            --emerald-800: #065f46;
            --emerald-900: #064e3b;
            
            /* Supporting Colors */
            --warning-amber: #f59e0b;
            --danger-red: #ef4444;
            --info-blue: #3b82f6;
            --purple: #8b5cf6;
            --orange: #f97316;
            
            /* Neutrals */
            --white: #ffffff;
            --neutral-50: #f9fafb;
            --neutral-100: #f3f4f6;
            --neutral-200: #e5e7eb;
            --neutral-300: #d1d5db;
            --neutral-400: #9ca3af;
            --neutral-500: #6b7280;
            --neutral-600: #4b5563;
            --neutral-700: #374151;
            --neutral-800: #1f2937;
            --neutral-900: #111827;
            
            /* Layout Variables */
            --sidebar-width: 280px;
            --sidebar-collapsed: 100px;
            --navbar-height: 72px;
            --transition: all 0.3s cubic-bezier(0.4,0,0.2,1);
            --border-radius: 12px;
            --border-radius-sm: 8px;
            --border-radius-lg: 16px;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--neutral-50);
            color: var(--neutral-800);
            line-height: 1.6;
            font-weight: 400;
            overflow-x: hidden;
        }

        /* Enhanced Navbar */
        .navbar {
            height: var(--navbar-height);
            background: var(--white);
            box-shadow: var(--shadow-lg);
            z-index: 1030;
            border-bottom: 1px solid var(--neutral-200);
            backdrop-filter: blur(20px);
            padding: 0.5rem 1rem;
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-green) !important;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .navbar-brand::before {
            content: '';
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, var(--primary-green), var(--primary-green-light));
            border-radius: var(--border-radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            mask: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='currentColor'%3e%3cpath d='M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z'/%3e%3c/svg%3e") center/cover;
        }

        /* Sidebar Improvements */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--white);
            position: fixed;
            top: var(--navbar-height);
            left: 0;
            height: calc(100vh - var(--navbar-height));
            box-shadow: var(--shadow-xl);
            transition: var(--transition);
            z-index: 1020;
            border-right: 1px solid var(--neutral-200);
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: var(--neutral-300) transparent;
            display: flex;
            flex-direction: column;
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: var(--neutral-300);
            border-radius: 3px;
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed);
            overflow-x: hidden;
        }

        .sidebar-nav {
            padding: 1.5rem 0;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .nav-header {
            padding: 1rem 1.5rem 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--neutral-500);
            border-bottom: 1px solid var(--neutral-200);
            margin-bottom: 1rem;
            white-space: nowrap;
            transition: var(--transition);
        }

        .sidebar .nav-link {
            color: var(--neutral-700);
            padding: 0.875rem 1.5rem;
            border-radius: 0;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 1rem;
            font-weight: 500;
            margin: 0.125rem 0.75rem;
            border-radius: var(--border-radius);
            position: relative;
            overflow: hidden;
            white-space: nowrap;
        }

        .sidebar.collapsed .nav-link {
            justify-content: center;
            padding: 0.875rem;
            margin: 0.125rem 0.5rem;
        }

        .sidebar .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 0;
            height: 100%;
            background: linear-gradient(135deg, var(--primary-green), var(--primary-green-light));
            transition: var(--transition);
            z-index: -1;
        }

        .sidebar .nav-link:hover::before,
        .sidebar .nav-link.active::before {
            width: 100%;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: var(--white);
            background: transparent;
            transform: translateX(4px);
        }

        .sidebar .nav-link i {
            font-size: 1.125rem;
            min-width: 24px;
            text-align: center;
            transition: var(--transition);
        }

        .sidebar.collapsed .nav-link span {
            display: none;
        }

        .sidebar.collapsed .nav-header {
            padding: 1rem 0.5rem 0.5rem;
            text-align: center;
            opacity: 0;
            height: 0;
            margin: 0;
            border: none;
        }

        /* Enhanced Toggle Button */
        #sidebarToggle {
            font-size: 1.25rem;
            color: var(--primary-green);
            background: var(--secondary-green);
            border: none;
            outline: none;
            box-shadow: none;
            transition: var(--transition);
            padding: 0.5rem;
            border-radius: var(--border-radius);
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        #sidebarToggle:hover, #sidebarToggle:focus {
            color: var(--white);
            background: var(--primary-green);
            transform: scale(1.05);
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: calc(var(--navbar-height) + 2rem) 2rem 2rem 2rem;
            transition: var(--transition);
            min-height: calc(100vh - var(--navbar-height));
        }

        .sidebar.collapsed ~ .main-content {
            margin-left: var(--sidebar-collapsed);
        }

        /* User Navbar Enhancements */
        .user-navbar {
            display: flex;
            align-items: center;
            gap: 0.875rem;
            cursor: pointer;
            padding: 0.5rem 1rem;
            border-radius: var(--border-radius);
            transition: var(--transition);
            background: var(--neutral-50);
            border: 1px solid var(--neutral-200);
        }

        .user-navbar:hover {
            background: var(--secondary-green);
            border-color: var(--primary-green-light);
            transform: translateY(-1px);
        }

        .user-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-green), var(--primary-green-light));
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-size: 1.25rem;
            box-shadow: var(--shadow-md);
        }

        .greeting-container {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .greeting-text {
            font-size: 0.75rem;
            color: var(--neutral-500);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 500;
        }

        .user-name {
            font-weight: 600;
            font-size: 0.95rem;
            color: var(--neutral-800);
        }

        .user-role {
            font-size: 0.8rem;
            color: var(--neutral-600);
        }

        /* Notification Badge */
        .notification-link {
            position: relative;
            padding: 0.5rem;
            border-radius: var(--border-radius);
            transition: var(--transition);
            background: var(--neutral-50);
            border: 1px solid var(--neutral-200);
            color: var(--neutral-700);
        }

        .notification-link:hover {
            background: var(--secondary-green);
            border-color: var(--primary-green-light);
            color: var(--primary-green);
        }

        .notification-badge {
            position: absolute;
            top: -2px;
            right: -2px;
            background: var(--danger-red);
            color: var(--white);
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 50px;
            min-width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            box-shadow: var(--shadow-sm);
        }

        /* Dropdown Menus */
        .dropdown-menu {
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-xl);
            min-width: 220px;
            border: 1px solid var(--neutral-200);
            padding: 0.5rem;
            margin-top: 0.5rem;
        }

        .dropdown-item {
            padding: 0.75rem 1rem;
            border-radius: var(--border-radius-sm);
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: var(--neutral-700);
        }

        .dropdown-item:hover {
            background: var(--secondary-green);
            color: var(--primary-green);
        }

        .dropdown-item i {
            font-size: 1rem;
            min-width: 16px;
        }

        .dropdown-divider {
            margin: 0.5rem 0;
            border-color: var(--neutral-200);
        }

        /* Content Management Specific Styles */
        .content-card {
            background: var(--white);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--neutral-200);
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .content-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--neutral-200);
        }

        .content-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--neutral-800);
            margin: 0;
        }

        .content-actions {
            display: flex;
            gap: 1rem;
        }

        /* Table Styles */
        .content-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .content-table thead th {
            background: var(--neutral-50);
            color: var(--neutral-700);
            font-weight: 600;
            padding: 1rem;
            text-align: left;
            border-bottom: 2px solid var(--neutral-200);
        }

        .content-table tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid var(--neutral-200);
        }

        .content-table tbody tr:hover {
            background: var(--neutral-50);
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--neutral-700);
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--neutral-300);
            border-radius: var(--border-radius-sm);
            transition: var(--transition);
        }

        .form-control:focus {
            border-color: var(--primary-green);
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
            outline: none;
        }

        /* Mobile Responsiveness */
        @media (max-width: 991.98px) {
            .sidebar {
                left: -100%;
                width: var(--sidebar-width);
            }
            
            .sidebar.mobile-show {
                left: 0;
            }
            
            .main-content, 
            .sidebar.collapsed ~ .main-content {
                margin-left: 0;
                padding: calc(var(--navbar-height) + 1rem) 1rem 1rem 1rem;
            }
            
            .content-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            
            .content-actions {
                width: 100%;
                justify-content: flex-end;
            }
        }

        .mobile-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1010;
            backdrop-filter: blur(4px);
        }

        .mobile-overlay.show {
            display: block;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--neutral-100);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--neutral-300);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--neutral-400);
        }


    </style>
    
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container-fluid">
            <div class="d-flex align-items-center">
                <a class="navbar-brand" href="{{ route('admin.content.index') }}">Content Management</a>
                <button class="btn" id="sidebarToggle" aria-label="Toggle sidebar">
                    <i class="fas fa-bars"></i>
                </button>
            </div>

            <ul class="navbar-nav ms-auto align-items-center flex-row gap-3">
                <!-- Notifications -->
                <li class="nav-item dropdown">
                    <a class="nav-link notification-link" href="#" id="notificationsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge">3</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-notifications" aria-labelledby="notificationsDropdown">
                        <li>
                            <div class="dropdown-item d-flex align-items-start py-2">
                                <div class="flex-shrink-0 me-2 text-primary">
                                    <i class="fas fa-file-alt fa-fw"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <a href="#" class="text-decoration-none text-dark">
                                            <div class="mb-1">
                                                New content submission requires approval
                                            </div>
                                            <small class="text-muted">
                                                15 minutes ago
                                            </small>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-link p-0 text-muted" title="Mark as read">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li>
                            <div class="dropdown-item d-flex align-items-start py-2">
                                <div class="flex-shrink-0 me-2 text-primary">
                                    <i class="fas fa-comment fa-fw"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <a href="#" class="text-decoration-none text-dark">
                                            <div class="mb-1">
                                                5 new comments on your blog post
                                            </div>
                                            <small class="text-muted">
                                                2 hours ago
                                            </small>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-link p-0 text-muted" title="Mark as read">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li>
                            <div class="text-center py-1">
                                <a href="#" class="small">
                                    View all notifications
                                </a>
                                <span class="mx-2">•</span>
                                <button type="button" class="btn btn-link p-0 small">
                                    Mark all as read
                                </button>
                            </div>
                        </li>
                    </ul>
                </li>

                <!-- User Dropdown -->
                <li class="nav-item dropdown">
                    @php
                        $hour = now()->hour;
                        if ($hour < 10) {
                            $greeting = 'Good morning';
                            $greetingIcon = 'fa-sun';
                            $greetingColor = '#F59E0B';
                        } elseif ($hour < 16) {
                            $greeting = 'Good afternoon';
                            $greetingIcon = 'fa-cloud-sun';
                            $greetingColor = '#10B981';
                        } else {
                            $greeting = 'Good evening';
                            $greetingIcon = 'fa-moon';
                            $greetingColor = '#6366F1';
                        }
                    @endphp
                    <a class="nav-link dropdown-toggle user-navbar" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="user-avatar">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        <div class="d-none d-md-flex flex-column">
                            <div class="greeting-container">
                                <i class="fas {{ $greetingIcon }}" style="color: {{ $greetingColor }}; font-size: 0.9rem;"></i>
                                <span class="greeting-text">{{ $greeting }}</span>
                            </div>
                            <span class="user-name">{{ Auth::user()->name }}</span>
                            <span class="user-role">{{ ucfirst(Auth::user()->role) }}</span>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li>
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-user-circle fa-fw"></i> Profile
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-cog fa-fw"></i> Settings
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="#" id="logoutTrigger">
                                <i class="fas fa-sign-out-alt fa-fw"></i> Logout
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>

    <aside class="sidebar" id="sidebar" aria-label="Main navigation">
        <ul class="nav flex-column sidebar-nav">
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin') ? 'active' : '' }}" href="{{ route('admin.content.index') }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            
            <li class="nav-header">CONTENT MANAGEMENT</li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/faqs*') ? 'active' : '' }}" href="{{ route('admin.faqs.index') }}">
                    <i class="fas fa-question-circle"></i>
                    <span>FAQs</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/blogs*') ? 'active' : '' }}" href="{{ route('admin.blogs.index') }}">
                    <i class="fas fa-blog"></i>
                    <span>Blog Posts</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/services*') ? 'active' : '' }}" href="{{ route('admin.services.index') }}">
                    <i class="fas fa-cogs"></i>
                    <span>Services</span>
                </a>
            </li>
            
            <li class="nav-header">EMAIL MARKETING</li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/newsletters*') ? 'active' : '' }}" href="{{ route('admin.newsletters.index') }}">
                    <i class="fas fa-envelope"></i>
                    <span>Newsletters</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/subscribers*') ? 'active' : '' }}" href="{{ route('admin.subscribers.index') }}">
                    <i class="fas fa-users"></i>
                    <span>Subscribers</span>
                </a>
            </li>
        </ul>
    </aside>

    <div class="mobile-overlay" id="mobileOverlay" aria-hidden="true"></div>

    <main class="main-content" id="mainContent">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show mt-3" role="alert">
            <i class="fas fa-info-circle me-2"></i>
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show mt-3" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @yield('content')
    </main>

    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to log out?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn btn-primary">Logout</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // DOM Elements
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const mainContent = document.getElementById('mainContent');
            const mobileOverlay = document.getElementById('mobileOverlay');
            const logoutTrigger = document.getElementById('logoutTrigger');
            const logoutModal = new bootstrap.Modal(document.getElementById('logoutModal'));
            const notificationsDropdown = document.getElementById('notificationsDropdown');

            // Sidebar Toggle Functionality
            function handleSidebarToggle() {
                if (window.innerWidth <= 991.98) {
                    // Mobile behavior
                    sidebar.classList.toggle('mobile-show');
                    mobileOverlay.classList.toggle('show');
                } else {
                    // Desktop behavior
                    sidebar.classList.toggle('collapsed');
                    document.body.classList.toggle('sidebar-collapsed');
                    
                    // Adjust main content margin
                    if (sidebar.classList.contains('collapsed')) {
                        mainContent.style.marginLeft = 'var(--sidebar-collapsed)';
                    } else {
                        mainContent.style.marginLeft = 'var(--sidebar-width)';
                    }
                }
            }

            // Event Listeners
            sidebarToggle.addEventListener('click', handleSidebarToggle);
            
            mobileOverlay.addEventListener('click', function() {
                sidebar.classList.remove('mobile-show');
                mobileOverlay.classList.remove('show');
            });

            // Window Resize Handler
            function handleResize() {
                if (window.innerWidth > 991.98) {
                    sidebar.classList.remove('mobile-show');
                    mobileOverlay.classList.remove('show');
                } else if (sidebar.classList.contains('collapsed')) {
                    sidebar.classList.remove('collapsed');
                    document.body.classList.remove('sidebar-collapsed');
                    mainContent.style.marginLeft = '';
                }
            }

            window.addEventListener('resize', handleResize);

            // Initialize sidebar state
            handleResize();

            // Logout Modal
            if (logoutTrigger) {
                logoutTrigger.addEventListener('click', function(e) {
                    e.preventDefault();
                    logoutModal.show();
                });
            }

            // Initialize Summernote
            $('.summernote').summernote({
                height: 300,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']],
                ]
            });
        });
    </script>
    @stack('scripts')
</body>
</html>