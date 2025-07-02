<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Content Management Dashboard">
    <title>Content Management - Dashboard</title>

    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" as="style">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">

    <style>
        /* Reset & Base Styles */
        :root {
            --primary-color: #045f25;
            --secondary-color: #6B7280;
            --dark-color: #1F2937;
            --light-color: #F9FAFB;
            --danger-color: #EF4444;
            --success-color: #10B981;
            --warning-color: #F59E0B;
            --info-color: #16db96;
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 80px;
            --navbar-height: 64px;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            font-size: 0.95rem;
            line-height: 1.5;
            color: var(--dark-color);
            background-color: #f3f4f6;
            height: 100vh;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
        }

        a {
            color: var(--primary-color);
            text-decoration: none;
        }

        ul {
            list-style: none;
        }

        /* Layout Structure */
        .main-wrapper {
            display: flex;
            height: 100vh;
            width: 100%;
            overflow: hidden;
        }

        /* Navbar Styles */
        .navbar {
            height: var(--navbar-height);
            background-color: white;
            box-shadow: var(--shadow);
            padding: 0 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-right: 1rem;
        }

        .navbar-toggler {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: transparent;
            border: none;
            cursor: pointer;
            color: var(--secondary-color);
            border-radius: 4px;
            margin-left: 0.5rem;
        }

        .navbar-toggler:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }

        /* Dropdown Styles */
        .dropdown {
            position: relative;
        }

        .dropdown-toggle {
            background: transparent;
            border: none;
            cursor: pointer;
            padding: 0;
            display: flex;
            align-items: center;
        }

        .user-navbar {
            display: flex;
            align-items: center;
            padding: 0.5rem;
            border-radius: 0.375rem;
        }

        .user-navbar:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }

        /* Updated from img to .user-icon */
        .user-navbar .user-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            margin-right: 0.75rem;
            background-color: #e5e7eb; /* Placeholder background */
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: var(--secondary-color);
        }

        .user-info {
            text-align: left;
            margin-right: 0.75rem;
        }

        .user-name { /* Original user-name style */
            font-weight: 600;
            font-size: 0.875rem;
        }

        .user-greeting { /* New style for greeting */
            font-weight: 600;
            font-size: 0.875rem;
            color: var(--dark-color);
        }

        .user-role {
            font-size: 0.75rem;
            color: var(--secondary-color);
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background-color: white;
            border-radius: 0.375rem;
            box-shadow: var(--shadow-md);
            min-width: 220px;
            padding: 0.5rem 0;
            margin: 0.5rem 0 0;
            z-index: 1000;
            display: none;
        }

        .dropdown-menu.show {
            display: block;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            color: var(--dark-color);
            transition: var(--transition);
        }

        .dropdown-item:hover {
            background-color: #f3f4f6;
        }

        .dropdown-item i {
            margin-right: 0.5rem;
            color: var(--secondary-color);
            width: 16px;
        }

        .dropdown-divider {
            height: 1px;
            background-color: #e5e7eb;
            margin: 0.5rem 0;
        }

        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background-color: white;
            box-shadow: var(--shadow);
            overflow-y: auto;
            transition: var(--transition);
            position: fixed;
            top: var(--navbar-height);
            left: 0;
            bottom: 0;
            z-index: 1020;
        }

        .sidebar-nav {
            padding: 1rem 0;
        }

        .nav-header {
            padding: 0.75rem 1.5rem 0.5rem;
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: 600;
            color: var(--secondary-color);
            margin-top: 0.5rem;
        }

        .nav-item {
            margin-bottom: 0.25rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: var(--dark-color);
            border-left: 3px solid transparent;
            transition: var(--transition);
        }

        .nav-link:hover,
        .nav-link.active {
            background-color: rgba(79, 70, 229, 0.1);
            color: var(--primary-color);
            border-left-color: var(--primary-color);
        }

        .nav-link i {
            margin-right: 0.75rem;
            width: 20px;
            text-align: center;
            color: inherit;
        }

        .nav-item-text {
            flex-grow: 1;
        }

        /* Mobile Overlay */
        .mobile-overlay {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1010;
            display: none;
        }

        .mobile-overlay.show {
            display: block;
        }

        /* Content Area */
        .content-wrapper {
            flex: 1;
            padding: 1.5rem;
            padding-top: calc(var(--navbar-height) + 1.5rem);
            margin-left: var(--sidebar-width);
            overflow-y: auto;
            min-height: 100vh;
            transition: var(--transition);
        }

        /* Sidebar Toggle State */
        body.sidebar-collapsed .sidebar {
            width: var(--sidebar-collapsed-width);
        }

        body.sidebar-collapsed .nav-item-text,
        body.sidebar-collapsed .nav-header {
            display: none;
        }

        body.sidebar-collapsed .nav-link {
            padding: 0.75rem;
            justify-content: center;
        }

        body.sidebar-collapsed .nav-link i {
            margin-right: 0;
            font-size: 1.25rem;
        }

        body.sidebar-collapsed .content-wrapper {
            margin-left: var(--sidebar-collapsed-width);
        }

        /* Mobile Responsive */
        @media (max-width: 767.98px) {
            .sidebar {
                left: -280px;
            }

            .sidebar.mobile-show {
                left: 0;
            }

            .content-wrapper {
                margin-left: 0;
            }

            body.sidebar-collapsed .content-wrapper {
                margin-left: 0;
            }
        }

        /* Modal Styles */
        .modal {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: var(--shadow-md);
            width: 90%;
            max-width: 800px;
            z-index: 1050;
            display: none;
            overflow: hidden;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal.show {
            display: block;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .modal-title {
            font-weight: 600;
            font-size: 1.25rem;
            margin: 0;
        }

        .modal-close {
            background: transparent;
            border: none;
            font-size: 1.5rem;
            line-height: 1;
            cursor: pointer;
            color: var(--secondary-color);
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 0.5rem;
            padding: 1rem 1.5rem;
            border-top: 1px solid #e5e7eb;
        }

        .modal-backdrop {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1040;
            display: none;
        }

        .modal-backdrop.show {
            display: block;
        }

        /* Button Styles */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 1rem;
            font-weight: 500;
            font-size: 0.875rem;
            border-radius: 0.375rem;
            cursor: pointer;
            transition: var(--transition);
            border: 1px solid transparent;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background-color: #034e20;
        }

        .btn-secondary {
            background-color: white;
            border-color: #e5e7eb;
            color: var(--dark-color);
        }

        .btn-secondary:hover {
            background-color: #f9fafb;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }

        .btn-outline-primary {
            border-color: var(--primary-color);
            color: var(--primary-color);
            background-color: transparent;
        }

        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-success {
            background-color: var(--success-color);
            color: white;
        }

        .btn-success:hover {
            background-color: #0f9968;
        }

        .btn-warning {
            background-color: var(--warning-color);
            color: white;
        }

        .btn-warning:hover {
            background-color: #d97706;
        }

        .btn-danger {
            background-color: var(--danger-color);
            color: white;
        }

        .btn-danger:hover {
            background-color: #dc2626;
        }

        .btn-info {
            background-color: var(--info-color);
            color: white;
        }

        .btn-info:hover {
            background-color: #14c4a1;
        }

        /* Card Styles */
        .card {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: var(--shadow);
            margin-bottom: 1.5rem;
            overflow: hidden;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e5e7eb;
            background-color: var(--light-color);
        }

        .card-title {
            font-weight: 600;
            font-size: 1.125rem;
            margin: 0;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Table Styles */
        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th, .table td {
            padding: 0.75rem;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
        }

        .table th {
            background-color: var(--light-color);
            font-weight: 600;
            font-size: 0.875rem;
            color: var(--secondary-color);
        }

        .table-responsive {
            overflow-x: auto;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 1rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--dark-color);
        }

        .form-control {
            width: 100%;
            padding: 0.5rem 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            transition: var(--transition);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(4, 95, 37, 0.1);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }

        /* Utilities */
        .d-flex {
            display: flex;
        }

        .align-items-center {
            align-items: center;
        }

        .justify-content-between {
            justify-content: space-between;
        }

        .justify-content-end {
            justify-content: flex-end;
        }

        .mr-2 {
            margin-right: 0.5rem;
        }

        .mb-3 {
            margin-bottom: 1rem;
        }

        .text-center {
            text-align: center;
        }

        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border-width: 0;
        }

        /* Status Badges */
        .badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 0.25rem;
            text-align: center;
        }

        .badge-success {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
        }

        .badge-warning {
            background-color: rgba(245, 158, 11, 0.1);
            color: var(--warning-color);
        }

        .badge-danger {
            background-color: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
        }

        .badge-info {
            background-color: rgba(22, 219, 150, 0.1);
            color: var(--info-color);
        }

        /* Content Management Specific Styles */
        .content-section {
            margin-bottom: 2rem;
        }

        .content-actions {
            display: flex;
            gap: 0.5rem;
        }

        .preview-content {
            background-color: #f8f9fa;
            padding: 1rem;
            border-radius: 0.375rem;
            border: 1px solid #e5e7eb;
            margin-top: 0.5rem;
        }

        .content-meta {
            font-size: 0.75rem;
            color: var(--secondary-color);
            margin-top: 0.5rem;
        }

        .content-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 0.5rem;
            box-shadow: var(--shadow);
            text-align: center;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .stat-label {
            color: var(--secondary-color);
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }

        .filter-bar {
            background: white;
            padding: 1rem;
            border-radius: 0.5rem;
            box-shadow: var(--shadow);
            margin-bottom: 1.5rem;
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .filter-bar select {
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            font-size: 0.875rem;
        }

        /* Rich Text Editor Placeholder */
        .editor-placeholder {
            border: 2px dashed #d1d5db;
            padding: 2rem;
            text-align: center;
            color: var(--secondary-color);
            border-radius: 0.375rem;
            margin-top: 0.5rem;
        }

        .gap-3 {
            gap: 1rem;
        }

        .flex-wrap {
            flex-wrap: wrap;
        }

        /* Tab Navigation */
        .nav-tabs {
            border-bottom: 1px solid #e5e7eb;
            margin-bottom: 1.5rem;
        }

        .nav-tabs .nav-link {
            padding: 0.75rem 1.25rem;
            color: var(--secondary-color);
            border: none;
            border-bottom: 3px solid transparent;
            font-weight: 500;
        }

        .nav-tabs .nav-link.active {
            color: var(--primary-color);
            border-bottom-color: var(--primary-color);
            background-color: transparent;
        }

        /* Pagination */
        .pagination {
            margin-top: 1.5rem;
            justify-content: flex-end;
        }

        /* Media Gallery */
        .media-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .media-item {
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
            overflow: hidden;
            transition: var(--transition);
        }

        .media-item:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .media-thumbnail {
            height: 120px;
            background-color: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .media-thumbnail img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .media-info {
            padding: 0.75rem;
            background: white;
        }

        .media-title {
            font-weight: 500;
            margin-bottom: 0.25rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .media-meta {
            font-size: 0.75rem;
            color: var(--secondary-color);
        }

        /* Analytics Chart Placeholder */
        .chart-container {
            height: 300px;
            background-color: #f3f4f6;
            border-radius: 0.375rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--secondary-color);
            margin-bottom: 1.5rem;
        }

          /* Panel nav (alternative) */
        .panel-nav {
            display: flex;
            background-color: white;
            box-shadow: var(--shadow);
            margin-bottom: 1.5rem;
            border-radius: 0.5rem;
            overflow: hidden;
        }

        .panel-nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 1rem;
            color: var(--secondary-color);
            flex: 1;
            transition: var(--transition);
            border-bottom: 3px solid transparent;
        }

        .panel-nav-item i {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .panel-nav-item.active {
            color: var(--primary-color);
            border-bottom-color: var(--primary-color);
        }

        .panel-nav-item:hover {
            background-color: rgba(79, 70, 229, 0.05);
        }

          /* User Navigation Styles */
.user-navbar {
    display: flex;
    align-items: center;
    padding: 0.5rem 0.75rem;
    border-radius: 0.5rem;
    transition: all 0.2s ease;
    cursor: pointer;
}

.user-navbar:hover {
    background-color: rgba(0, 0, 0, 0.03);
}

.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #f3f4f6;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 0.75rem;
    color: var(--primary-color);
    font-size: 1.25rem;
}

.user-info {
    display: flex;
    flex-direction: column;
    margin-right: 0.75rem;
}

.greeting-container {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.1rem;
}

.greeting-icon {
    font-size: 0.8rem;
    color: var(--greeting-color);
}

.greeting-text {
    font-size: 0.75rem;
    font-weight: 500;
    color: var(--secondary-color);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.user-name {
    font-weight: 600;
    font-size: 0.9rem;
    color: var(--dark-color);
    line-height: 1.2;
}

.user-role {
    font-size: 0.7rem;
    color: var(--secondary-color);
    margin-top: 0.1rem;
}

.dropdown-arrow {
    font-size: 0.8rem;
    color: var(--secondary-color);
    transition: transform 0.2s ease;
}

.dropdown-toggle[aria-expanded="true"] .dropdown-arrow {
    transform: rotate(180deg);
}

/* Dropdown Menu Styles */
.dropdown-menu {
    border: none;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
    border-radius: 0.5rem;
    padding: 0.5rem 0;
    min-width: 200px;
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.dropdown-item {
    padding: 0.5rem 1rem;
    font-size: 0.85rem;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
}

.dropdown-item:hover {
    background-color: #f8f9fa;
    color: var(--primary-color);
}

.dropdown-item i {
    width: 20px;
    text-align: center;
    margin-right: 0.75rem;
    color: var(--secondary-color);
}

.dropdown-divider {
    border-top: 1px solid rgba(0, 0, 0, 0.05);
    margin: 0.5rem 0;
}
    </style>
</head>

<body>
    <div class="main-wrapper">
        <nav class="navbar">
            <div class="d-flex align-items-center">
                <a class="navbar-brand" href="{{ route('content.index') }}">Content Management</a>
                <button class="navbar-toggler" id="sidebarToggle" aria-label="Toggle navigation">
                    <i class="fas fa-bars"></i>
                </button>
            </div>

            <div class="dropdown">
    @php
        $hour = now()->hour;
        if ($hour < 12) {
            $greeting = 'Good morning';
            $greetingIcon = 'fa-sun';
            $greetingColor = '#F59E0B'; // Amber
        } elseif ($hour < 18) {
            $greeting = 'Good afternoon';
            $greetingIcon = 'fa-cloud-sun';
            $greetingColor = '#10B981'; // Green
        } else {
            $greeting = 'Good evening';
            $greetingIcon = 'fa-moon';
            $greetingColor = '#6366F1'; // Indigo
        }
    @endphp

    <button class="dropdown-toggle" id="userDropdown" aria-expanded="false" aria-haspopup="true">
    <div class="user-navbar">
        <div class="user-avatar">
            <i class="fas fa-user-circle"></i> </div>
        <div class="user-info">
            <div class="greeting-container">
                <i class="fas {{ $greetingIcon }} greeting-icon" style="color: {{ $greetingColor }};"></i>
                <span class="greeting-text">{{ $greeting }}</span>
            </div>
            <div class="user-name">{{ Auth::user()->name }}</div>
            <div class="user-role">{{ ucfirst(Auth::user()->role) }}</div>
        </div>
</button>

    <div class="dropdown-menu" id="userDropdownMenu" aria-labelledby="userDropdown">
        <a class="dropdown-item" href="#">
            <i class="fas fa-user-circle fa-fw me-2"></i>
            Profile
        </a>
        <a class="dropdown-item" href="#">
            <i class="fas fa-cog fa-fw me-2"></i>
                Settings
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" id="logoutTrigger">
                    <i class="fas fa-sign-out-alt fa-fw me-2"></i>
                    Logout
                </a>
            </div>
        </div>
        </nav>

        <aside class="sidebar" aria-label="Main navigation">
            <ul class="sidebar-nav">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin') ? 'active' : '' }}" href="{{ route('content.index') }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span class="nav-item-text">Dashboard</span>
                    </a>
                </li>
            
                <li class="nav-header">CONTENT MANAGEMENT</li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/faqs*') ? 'active' : '' }}" href="{{ route('admin.faqs.index') }}">
                        <i class="fas fa-question-circle"></i>
                        <span class="nav-item-text">FAQs</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/blogs*') ? 'active' : '' }}" href="{{ route('blogposts') }}">
                        <i class="fas fa-blog"></i>
                        <span class="nav-item-text">Blog Posts</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/services*') ? 'active' : '' }}" href="{{ route('admin.services.index') }}">
                        <i class="fas fa-cogs"></i>
                        <span class="nav-item-text">Services</span>
                    </a>
                </li>

        
        <li class="nav-header">EMAIL MARKETING</li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('admin/newsletters*') ? 'active' : '' }}" href="{{ route('admin.newsletters.index') }}" data-section="newsletters">
                <i class="fas fa-envelope"></i>
                <span class="nav-item-text">Newsletters</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('admin/subscribers*') ? 'active' : '' }}" href="{{ route('admin.subscribers.index') }}" data-section="subscribers">
                <i class="fas fa-users"></i>
                <span class="nav-item-text">Subscribers</span>
            </a>
        </li>
</aside>

        <div class="mobile-overlay" id="mobileOverlay" aria-hidden="true"></div>

        <main class="content-wrapper">
           @yield('content')
        </main>
    </div>

    

    <div class="modal" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-header">
            <h3 class="modal-title" id="logoutModalLabel">Ready to Leave?</h3>
            <button class="modal-close" data-dismiss="modal" aria-label="Close">
                &times;
            </button>
        </div>
        <div class="modal-body">
            <p>Select "Logout" below if you are ready to end your current session.</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-primary">Logout</button>
            </form>
        </div>
    </div>
    <div class="modal-backdrop" id="modalBackdrop"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Cache DOM elements
            const dom = {
                body: document.body,
                sidebar: document.querySelector('.sidebar'),
                sidebarToggle: document.getElementById('sidebarToggle'),
                mobileOverlay: document.getElementById('mobileOverlay'),
                userDropdown: document.getElementById('userDropdown'),
                userDropdownMenu: document.getElementById('userDropdownMenu'),
                logoutTrigger: document.getElementById('logoutTrigger'),
                logoutModal: document.getElementById('logoutModal'),
                modalBackdrop: document.getElementById('modalBackdrop')
            };
            // Toggle sidebar function
            function toggleSidebar() {
                if (window.innerWidth < 768) {
                    dom.sidebar.classList.toggle('mobile-show');
                    dom.mobileOverlay.classList.toggle('show');
                    dom.mobileOverlay.setAttribute('aria-hidden', !dom.sidebar.classList.contains('mobile-show'));
                } else {
                    dom.body.classList.toggle('sidebar-collapsed');
                }
            }

            // Close sidebar function
            function closeSidebar() {
                dom.sidebar.classList.remove('mobile-show');
                dom.mobileOverlay.classList.remove('show');
                dom.mobileOverlay.setAttribute('aria-hidden', 'true');
            }

            // Toggle dropdown function
            function toggleDropdown() {
                const isExpanded = dom.userDropdown.getAttribute('aria-expanded') === 'true';
                dom.userDropdownMenu.classList.toggle('show');
                dom.userDropdown.setAttribute('aria-expanded', !isExpanded);
            }

            // Close all dropdowns function
            function closeAllDropdowns() {
                document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                    menu.classList.remove('show');
                    menu.previousElementSibling.setAttribute('aria-expanded', 'false');
                });
            }

            // Toggle modal function
            function toggleModal(modal) {
                modal.classList.toggle('show');
                dom.modalBackdrop.classList.toggle('show');
                modal.setAttribute('aria-hidden', !modal.classList.contains('show'));
            }

            // Event delegation for click events
            document.addEventListener('click', function(e) {
                // Sidebar toggle
                if (e.target.closest('#sidebarToggle')) {
                    toggleSidebar();
                }

                // Mobile overlay close
                if (e.target === dom.mobileOverlay) {
                    closeSidebar();
                }

                // User dropdown toggle
                if (e.target.closest('#userDropdown')) {
                    toggleDropdown();
                } else if (!e.target.closest('.dropdown-menu')) {
                    closeAllDropdowns();
                }

                // Modal toggle
                if (e.target.hasAttribute('data-dismiss')) {
                    const modal = e.target.closest('.modal');
                    if (modal) toggleModal(modal);
                }

                // Logout trigger
                if (e.target.closest('#logoutTrigger')) {
                    e.preventDefault();
                    toggleModal(dom.logoutModal);
                }
            });
            // Modal backdrop click handler
            dom.modalBackdrop.addEventListener('click', function() {
                document.querySelectorAll('.modal.show').forEach(modal => {
                    toggleModal(modal);
                });
            });
            // Window resize handler
            function handleResize() {
                if (window.innerWidth >= 768 && dom.sidebar.classList.contains('mobile-show')) {
                    closeSidebar();
                }
            }

            window.addEventListener('resize', handleResize);
        });
    </script>
</body>
</html>