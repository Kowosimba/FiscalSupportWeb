<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Helpdesk Admin Dashboard">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Font Awesome (defer loading for better performance) -->
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" media="print" onload="this.media='all'">
    
    <!-- Google Fonts - Inter with font display swap -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" media="print" onload="this.media='all'" fetchpriority="low">

    <!-- AdminLTE 3.2 - Load only what's needed -->
    <link href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css" rel="stylesheet" media="print" onload="this.media='all'">
    
    <!-- Critical CSS - Inline the essential styles -->
    <style>
        /* CSS Custom Properties for theme colors */
        :root {
            --primary: #059669;
            --primary-hover: #047857;
            --primary-light: #ECFDF5;
            --primary-dark: #065F46;
            --secondary: #10B981;
            --success: #10B981;
            --warning: #F59E0B;
            --danger: #EF4444;
            --dark: #1F2937;
            --light: #F9FAFB;
            --gray-medium: #9CA3AF;
            --body-bg: #F3F4F6;
        }
        
        /* Base styles */
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--body-bg);
            color: #374151;
        }
        
        /* Top Panel Navigation */
        .panel-nav {
            background-color: #1F2937;
            padding: 0.5rem 1rem;
            display: flex;
            gap: 1rem;
            border-bottom: 1px solid #E5E7EB;
        }
        
        .panel-nav-item {
            color: #E5E7EB;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-weight: 500;
            transition: all 0.2s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .panel-nav-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }
        
        .panel-nav-item.active {
            background-color: var(--primary);
            color: white;
        }
        
        /* Sidebar styling - simplified & optimized */
        .main-sidebar {
            background-color: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            border-right: 1px solid #E5E7EB;
        }
        
        .sidebar-light-primary .nav-sidebar > .nav-item > .nav-link.active {
            background-color: var(--primary-light);
            color: var(--primary);
            border-left: 3px solid var(--primary);
        }
        
        .sidebar-light-primary .nav-sidebar > .nav-item > .nav-link {
            color: #4B5563;
            font-weight: 500;
        }
        
        .nav-sidebar .nav-link:hover {
            background-color: var(--primary-light);
        }
        
        /* Card styling - keeping only essential properties */
        .card {
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            border: 1px solid #E5E7EB;
            margin-bottom: 1.5rem;
            overflow: hidden;
        }
        
        .card-header {
            background-color: #fff;
            border-bottom: 1px solid #E5E7EB;
            padding: 1rem 1.5rem;
        }
        
        .card-title {
            font-weight: 600;
            color: #111827;
        }
        
        /* Button styling - simplified */
        .btn {
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
        }
        
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        
        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
            transform: translateY(-1px);
        }
        
        /* Table styling - simplified */
        .table th {
            background-color: #F9FAFB;
            color: #374151;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }
        
        .table-hover tbody tr:hover {
            background-color: var(--primary-light);
        }
    </style>
    
    <!-- Non-critical CSS - Load asynchronously -->
    <style media="print" onload="this.media='all'">
        /* Header, nav & user elements */
        .main-header {
            background-color: #fff;
            border-bottom: 1px solid #E5E7EB;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }
        
        .navbar-light .navbar-nav .nav-link {
            color: #4B5563;
        }
        
        .content-wrapper {
            background-color: var(--body-bg);
        }
        
        /* Component-specific styles */
        .nav-header {
            color: #6B7280 !important;
            font-weight: 600;
            padding: 1rem 1rem 0.5rem;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }
        
        .badge {
            font-weight: 500;
            padding: 0.35em 0.65em;
            border-radius: 0.375rem;
        }
        
        .badge-primary { background-color: var(--primary); }
        .badge-success { background-color: var(--success); }
        .badge-warning { background-color: var(--warning); }
        .badge-danger { background-color: var(--danger); }
        
        /* User interface elements */
        .user-panel {
            border-bottom: 1px solid #E5E7EB;
            padding: 1rem;
        }
        
        .user-panel .info {
            padding: 0.5rem 0;
        }
        
        .brand-link {
            border-bottom: 1px solid #E5E7EB;
            font-weight: 600;
            padding: 1.25rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .brand-image {
            height: 2rem;
            width: auto;
        }
        
        /* Footer */
        .main-footer {
            background-color: #fff;
            border-top: 1px solid #E5E7EB;
            color: #6B7280;
            padding: 1rem;
            font-size: 0.875rem;
        }
        
        /* Secondary button styles */
        .btn-outline-primary {
            color: var(--primary);
            border-color: var(--primary);
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary-light);
            border-color: var(--primary);
            color: var(--primary);
        }
        
        /* Info boxes and small boxes */
        .info-box, .small-box {
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            border: 1px solid #E5E7EB;
            overflow: hidden;
        }
        
        .info-box-icon {
            border-radius: 0 !important;
            background-color: var(--primary-light);
            color: var(--primary);
        }
        
        .small-box {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .small-box:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .small-box .icon {
            opacity: 0.15;
            font-size: 70px;
            right: 15px;
            color: var(--primary);
        }
        
        .small-box .icon i {
            font-size: 70px;
            top: 20px;
            transition: all 0.3s ease;
        }
        
        .small-box-footer {
            background: rgba(5, 150, 105, 0.1);
            text-align: center;
            padding: 0.5rem 0;
            color: var(--primary);
            font-weight: 500;
            display: block;
            z-index: 10;
            position: relative;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }
        
        /* Status indicators */
        .status-indicator {
            display: inline-block;
            width: 0.75rem;
            height: 0.75rem;
            border-radius: 50%;
            margin-right: 0.5rem;
        }
        
        .status-open { background-color: var(--primary); }
        .status-pending { background-color: var(--warning); }
        .status-solved { background-color: var(--success); }
        .status-closed { background-color: #6B7280; }
        
        /* Search box */
        .search-box {
            position: relative;
            margin: 1rem 0;
        }
        
        .search-box .form-control {
            padding-left: 2.5rem;
            border-radius: 0.5rem;
            border: 1px solid #E5E7EB;
        }
        
        .search-box .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9CA3AF;
        }
        
        /* Dropdown menus */
        .dropdown-menu {
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid #E5E7EB;
            padding: 0.5rem 0;
        }
        
        .dropdown-item {
            padding: 0.5rem 1.5rem;
            color: #374151;
            font-weight: 500;
        }
        
        .dropdown-item:hover {
            background-color: var(--primary-light);
            color: var(--primary);
        }
        
        /* Form elements */
        .form-control {
            border-radius: 0.5rem;
            border: 1px solid #E5E7EB;
            padding: 0.5rem 1rem;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(5, 150, 105, 0.25);
        }
        
        /* Tabs */
        .nav-tabs {
            border-bottom: 1px solid #E5E7EB;
        }
        
        .nav-tabs .nav-link {
            border: none;
            color: #6B7280;
            font-weight: 500;
            padding: 0.75rem 1.25rem;
            border-radius: 0;
        }
        
        .nav-tabs .nav-link.active {
            color: var(--primary);
            border-bottom: 2px solid var(--primary);
            background-color: transparent;
        }
        
        .nav-tabs .nav-link:hover {
            border-bottom: 2px solid #E5E7EB;
        }
        
        /* User navbar */
        .user-navbar {
            display: flex;
            align-items: center;
            padding: 0.5rem;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
        }
        
        .user-navbar:hover {
            background-color: var(--primary-light);
        }
        
        .user-navbar img {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border: 2px solid var(--primary);
            object-fit: cover;
        }
        
        .user-navbar .user-info {
            margin-left: 0.5rem;
            margin-right: 0.5rem;
        }
        
        .user-navbar .user-name {
            font-weight: 600;
            color: #111827;
            font-size: 0.875rem;
            margin-bottom: 0;
        }
        
        .user-navbar .user-role {
            font-size: 0.75rem;
            color: var(--primary);
            margin-bottom: 0;
        }
        
        /* Custom scrollbar - for modern browsers */
        @media screen and (min-width: 768px) {
            ::-webkit-scrollbar {
                width: 8px;
                height: 8px;
            }
            
            ::-webkit-scrollbar-track {
                background: #F1F1F1;
            }
            
            ::-webkit-scrollbar-thumb {
                background: #D1D5DB;
                border-radius: 4px;
            }
            
            ::-webkit-scrollbar-thumb:hover {
                background: var(--gray-medium);
            }
        }
        
        /* Navbar badge positioning */
        .navbar .badge {
            font-size: 0.65rem;
            padding: 0.25em 0.5em;
            position: absolute;
            right: 3px;
            top: 5px;
        }
        
        /* Breadcrumb */
        .breadcrumb {
            background: transparent;
            padding: 0.5rem 0;
        }
        
        .breadcrumb-item.active {
            color: var(--primary);
            font-weight: 500;
        }
    </style>
    
    @stack('styles')
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Top Panel Navigation -->
        <div class="panel-nav">
            <a href="" class="panel-nav-item {{ request()->routeIs('tickets.*') ? 'active' : '' }}">
                <i class="fas fa-ticket-alt"></i>
                Tickets
            </a>
            <a href="" class="panel-nav-item {{ request()->routeIs('call-logs.*') ? 'active' : '' }}">
                <i class="fas fa-phone"></i>
                Call Logs
            </a>
            <a href="" class="panel-nav-item {{ request()->routeIs('content.*') ? 'active' : '' }}">
                <i class="fas fa-file-alt"></i>
                Content
            </a>
            <a href="" class="panel-nav-item {{ request()->routeIs('analytics.*') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i>
                Analytics
            </a>
            <a href="" class="panel-nav-item {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                <i class="fas fa-cog"></i>
                Settings
            </a>
        </div>
        
        <!-- Main content would go here -->
        
    </div>

    <!-- Load scripts at the end for better performance -->
    <!-- jQuery with defer attribute -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" defer></script>
    <!-- Bootstrap 4 with defer attribute -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" defer></script>
    <!-- AdminLTE App with defer attribute -->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js" defer></script>
    <!-- Chart.js - load only when needed with data-src -->
    <script data-src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    
    <script>
        // Defer initialization until DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            // Load Chart.js only if needed
            const needsCharts = document.querySelector('.chart-container');
            if (needsCharts) {
                const chartScript = document.querySelector('script[data-src*="chart.js"]');
                if (chartScript) {
                    chartScript.src = chartScript.dataset.src;
                    delete chartScript.dataset.src;
                }
            }
            
            // Initialize tooltips if Bootstrap is loaded
            if (typeof $ !== 'undefined' && $.fn.tooltip) {
                $('[data-toggle="tooltip"]').tooltip();
            }
            
            // Add responsive class to tables if needed
            document.querySelectorAll('.table').forEach(table => {
                const parent = table.parentElement;
                if (!parent.classList.contains('table-responsive')) {
                    parent.classList.add('table-responsive');
                }
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>