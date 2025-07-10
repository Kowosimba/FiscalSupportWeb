<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Job Cards Management - Dashboard')</title>
    <meta name="description" content="Modern Job Cards Management Dashboard">

    <!-- Bootstrap 5 & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* Base Variables - Green Theme */
        :root {
            /* Primary Green Palette */
            --primary-green: #064e3b;
            --primary-green-dark: #022c22;
            --primary-green-light: #059669;
            --primary-green-lighter: #34d399;
            --secondary-green: #d1fae5;
            --accent-green: #a7f3d0;
            --success-green: #22c55e;
            --emerald-600: #059669;
            --emerald-700: #047857;
            --emerald-800: #065f46;
            --emerald-900: #064e3b;
            --ultra-light-green: #ecfdf5;
            --light-green: #d1fae5;
            
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
            
            /* Text Colors */
            --dark-text: var(--neutral-900);
            --medium-text: var(--neutral-600);
            --light-text: var(--neutral-500);
            --border-color: var(--neutral-200);
            --hover-bg: var(--neutral-50);
            
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
            --shadow-hover: 0 20px 25px -5px rgba(6, 78, 59, 0.25), 0 8px 10px -6px rgba(6, 78, 59, 0.1);
            --shadow: var(--shadow-lg);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--ultra-light-green);
            color: var(--dark-text);
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
            border-bottom: 1px solid var(--border-color);
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
            mask: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='currentColor'%3e%3cpath d='M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'/%3e%3c/svg%3e") center/cover;
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
            border-right: 1px solid var(--border-color);
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: var(--border-color) transparent;
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
            background: var(--border-color);
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
            color: var(--light-text);
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 1rem;
            white-space: nowrap;
            transition: var(--transition);
        }

        .sidebar .nav-link {
            color: var(--medium-text);
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
            font-size: 1.25rem;
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
            background: var(--hover-bg);
            border: 1px solid var(--border-color);
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
            color: var(--light-text);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 500;
        }

        .user-name {
            font-weight: 600;
            font-size: 0.95rem;
            color: var(--dark-text);
        }

        .user-role {
            font-size: 0.8rem;
            color: var(--medium-text);
        }

        /* Notification Badge */
        .notification-link {
            position: relative;
            padding: 0.5rem;
            border-radius: var(--border-radius);
            transition: var(--transition);
            background: var(--hover-bg);
            border: 1px solid var(--border-color);
            color: var(--medium-text);
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
            background: #dc2626;
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
            border: 1px solid var(--border-color);
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
            color: var(--medium-text);
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
            border-color: var(--border-color);
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--white);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-lg);
            display: flex;
            align-items: center;
            gap: 1.5rem;
            padding: 2rem;
            transition: var(--transition);
            border: 1px solid var(--border-color);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, var(--primary-green), var(--primary-green-light));
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-xl);
        }

        .stat-icon {
            width: 64px;
            height: 64px;
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: var(--white);
            box-shadow: var(--shadow-md);
        }

        .stat-icon.primary { background: linear-gradient(135deg, var(--primary-green), var(--primary-green-light)); }
        .stat-icon.success { background: linear-gradient(135deg, var(--success-green), var(--primary-green)); }
        .stat-icon.warning { background: linear-gradient(135deg, #f59e0b, #d97706); }
        .stat-icon.info { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
        .stat-icon.danger { background: linear-gradient(135deg, #ef4444, #dc2626); }
        .stat-icon.purple { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }

        .stat-content {
            flex: 1;
        }

        .stat-number {
            font-size: 2.25rem;
            font-weight: 800;
            color: var(--dark-text);
            line-height: 1;
            margin-bottom: 0.25rem;
        }

        .stat-label {
            font-size: 1rem;
            color: var(--medium-text);
            font-weight: 500;
        }

        .stat-footer {
            font-size: 0.825rem;
            color: var(--light-text);
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }

        .stat-footer i {
            font-size: 0.75rem;
        }

        /* Enhanced Badges */
        .badge {
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: var(--border-radius-sm);
            padding: 0.375rem 0.75rem;
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
        }

        /* Status Badges */
        .status-pending { 
            background: rgba(245, 158, 11, 0.1); 
            color: var(--warning-amber);
            border: 1px solid rgba(245, 158, 11, 0.2);
        }
        .status-assigned { 
            background: rgba(59, 130, 246, 0.1); 
            color: var(--info-blue);
            border: 1px solid rgba(59, 130, 246, 0.2);
        }
        .status-in_progress { 
            background: rgba(6, 78, 59, 0.1); 
            color: var(--primary-green);
            border: 1px solid rgba(6, 78, 59, 0.2);
        }
        .status-complete { 
            background: rgba(34, 197, 94, 0.1); 
            color: var(--success-green);
            border: 1px solid rgba(34, 197, 94, 0.2);
        }
        .status-cancelled { 
            background: rgba(239, 68, 68, 0.1); 
            color: var(--danger-red);
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        /* Type Badges */
        .type-normal { 
            background: rgba(6, 78, 59, 0.1); 
            color: var(--primary-green);
            border: 1px solid rgba(6, 78, 59, 0.2);
        }
        .type-maintenance { 
            background: rgba(59, 130, 246, 0.1); 
            color: var(--info-blue);
            border: 1px solid rgba(59, 130, 246, 0.2);
        }
        .type-repair { 
            background: rgba(245, 158, 11, 0.1); 
            color: var(--warning-amber);
            border: 1px solid rgba(245, 158, 11, 0.2);
        }
        .type-installation { 
            background: rgba(139, 92, 246, 0.1); 
            color: var(--purple);
            border: 1px solid rgba(139, 92, 246, 0.2);
        }
        .type-consultation { 
            background: rgba(249, 115, 22, 0.1); 
            color: var(--orange);
            border: 1px solid rgba(249, 115, 22, 0.2);
        }
        .type-emergency { 
            background: rgba(239, 68, 68, 0.1); 
            color: var(--danger-red);
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        /* Enhanced Modal */
        .modal-content {
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-xl);
            border: 1px solid var(--border-color);
        }

        .modal-header {
            background: var(--ultra-light-green);
            border-bottom: 1px solid var(--border-color);
            border-radius: var(--border-radius-lg) var(--border-radius-lg) 0 0;
        }

        .modal-title {
            font-weight: 600;
            color: var(--dark-text);
        }

        .modal-footer {
            background: var(--ultra-light-green);
            border-top: 1px solid var(--border-color);
            border-radius: 0 0 var(--border-radius-lg) var(--border-radius-lg);
        }

        .btn-primary {
            background: var(--primary-green);
            border-color: var(--primary-green);
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: var(--border-radius);
            transition: var(--transition);
        }

        .btn-primary:hover {
            background: var(--primary-green-dark);
            border-color: var(--primary-green-dark);
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: var(--border-color);
            border-color: var(--border-color);
            color: var(--medium-text);
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: var(--border-radius);
            transition: var(--transition);
        }

        .btn-secondary:hover {
            background: #e5e7eb;
            border-color: #e5e7eb;
            color: var(--dark-text);
        }

        /* Page header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border-color);
        }

        .page-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--dark-text);
            margin: 0;
        }

        .page-actions {
            display: flex;
            gap: 1rem;
        }

        /* Job Cards Table Card */
        .job-cards-card {
            background: var(--white);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            border: 1px solid var(--border-color);
        }

        .job-cards-card-header {
            background: linear-gradient(135deg, var(--ultra-light-green) 0%, var(--light-green) 100%);
            padding: 1.5rem 2rem;
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

        .job-cards-card-header .btn {
            padding: 0.4rem 0.8rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--primary-green-light) 100%);
            color: var(--white);
        }

        .job-cards-card-header .btn:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-hover);
        }

        .job-cards-card-body {
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

        .job-card-number {
            font-family: 'Monaco', 'Menlo', monospace;
            background: var(--light-green);
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.875rem;
            color: var(--primary-green-dark);
            font-weight: 600;
            border: 1px solid var(--secondary-green);
        }

        .fault-description {
            font-weight: 500;
            color: var(--dark-text);
            max-width: 250px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .company-name {
            color: var(--medium-text);
            font-weight: 500;
        }

        .job-date {
            color: var(--light-text);
            font-size: 0.875rem;
        }

        .amount-charged {
            font-weight: 600;
            color: var(--primary-green-dark);
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
            margin-right: 0.25rem;
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
            background: #EFF6FF;
            color: #3B82F6;
        }

        .edit-btn:hover {
            background: #3B82F6;
            color: var(--white);
            transform: translateY(-1px);
        }

        .assign-btn {
            background: #F3E8FF;
            color: #8B5CF6;
        }

        .assign-btn:hover {
            background: #8B5CF6;
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

        .empty-content h5 {
            color: var(--primary-green);
            margin-bottom: 0.5rem;
        }

        .empty-content p {
            color: var(--light-text);
            font-size: 1.1rem;
            margin: 0;
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
            
            .stats-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .stat-card {
                padding: 1.5rem;
            }
            
            .user-navbar .d-md-flex {
                display: none !important;
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
            background: var(--ultra-light-green);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--secondary-green);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-green-light);
        }

        /* Animation for smooth transitions */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .stat-card {
            animation: fadeInUp 0.5s ease-out;
        }

        .stat-card:nth-child(1) { animation-delay: 0.1s; }
        .stat-card:nth-child(2) { animation-delay: 0.2s; }
        .stat-card:nth-child(3) { animation-delay: 0.3s; }
        .stat-card:nth-child(4) { animation-delay: 0.4s; }

        /* Notification dropdown styles */
        .dropdown-notifications {
            width: 320px;
            max-height: 400px;
            overflow-y: auto;
        }

        .dropdown-notifications .dropdown-item {
            white-space: normal;
            transition: all 0.2s;
            padding: 0.5rem 1rem;
        }

        .dropdown-notifications .dropdown-item:hover {
            background-color: rgba(6, 78, 59, 0.05);
        }

        /* Content container */
        .content-container {
            background: var(--white);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border-color);
            padding: 2rem;
            margin-bottom: 2rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
                padding: 1rem;
            }

            .job-cards-card-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
                padding: 1rem;
            }

            .enhanced-table th,
            .enhanced-table td {
                padding: 0.75rem 0.5rem;
            }

            .fault-description {
                max-width: 150px;
            }
        }

        @media (max-width: 480px) {
            .page-header {
                padding: 0.75rem;
            }

            .enhanced-table th,
            .enhanced-table td {
                padding: 0.5rem 0.25rem;
                font-size: 0.75rem;
            }

            .fault-description {
                max-width: 120px;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container-fluid">
            <div class="d-flex align-items-center">
                <a class="navbar-brand" href="{{ route('admin.call-logs.index') }}">Job Cards Management</a>
                <button class="btn" id="sidebarToggle" aria-label="Toggle sidebar">
                    <i class="fas fa-bars"></i>
                </button>
            </div>

            <ul class="navbar-nav ms-auto align-items-center flex-row gap-3">
                <!-- Notifications -->
                <li class="nav-item dropdown">
                    <a class="nav-link notification-link" href="#" id="notificationsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bell"></i>
                        @auth
                            @php
                                $unreadCount = 0; // Placeholder for notification count
                            @endphp
                            @if($unreadCount > 0)
                                <span class="notification-badge">{{ $unreadCount }}</span>
                            @endif
                        @endauth
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-notifications" aria-labelledby="notificationsDropdown">
                        <li>
                            <div class="dropdown-item text-muted py-2">
                                No new notifications
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
                <a class="nav-link {{ request()->routeIs('admin.call-logs.index') || request()->routeIs('admin.call-logs.dashboard') ? 'active' : '' }}" href="{{ route('admin.call-logs.index') }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            
            <li class="nav-header">Job Management</li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.call-logs.my-jobs') ? 'active' : '' }}" href="{{ route('admin.call-logs.my-jobs') }}">
                    <i class="fas fa-user-check"></i>
                    <span>My Jobs</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.call-logs.in-progress') ? 'active' : '' }}" href="{{ route('admin.call-logs.in-progress') }}">
                    <i class="fas fa-play-circle"></i>
                    <span>In Progress</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.call-logs.completed') ? 'active' : '' }}" href="{{ route('admin.call-logs.completed') }}">
                    <i class="fas fa-check-circle"></i>
                    <span>Completed</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.call-logs.pending') ? 'active' : '' }}" href="{{ route('admin.call-logs.pending') }}">
                    <i class="fas fa-clock"></i>
                    <span>Pending</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.call-logs.unassigned') ? 'active' : '' }}" href="{{ route('admin.call-logs.unassigned') }}">
                    <i class="fas fa-user-slash"></i>
                    <span>Unassigned</span>
                </a>
            </li>
            
            <li class="nav-header">Administration</li>
            @if(in_array(auth()->user()->role, ['admin', 'accounts']))
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.call-logs.create') ? 'active' : '' }}" href="{{ route('admin.call-logs.create') }}">
                    <i class="fas fa-plus-circle"></i>
                    <span>New Job Card</span>
                </a>
            </li>
            @endif
            @if(auth()->user()->role === 'admin')
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.call-logs.reports') ? 'active' : '' }}" href="{{ route('admin.call-logs.reports') }}">
                    <i class="fas fa-chart-line"></i>
                    <span>Reports</span>
                </a>
            </li>
            @endif
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

    <!-- Job Details Modal -->
    <div class="modal fade" id="jobDetailsModal" tabindex="-1" aria-labelledby="jobDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="jobDetailsModalLabel">Job Card Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="job-details-header">
                        <h3 class="job-details-title" id="jobDescription">Loading...</h3>
                        <div class="job-details-status">
                            <span class="badge" id="jobStatusBadge">Pending</span>
                            <span class="badge" id="jobTypeBadge">Normal</span>
                        </div>
                    </div>
                    
                    <div class="job-details-content">
                        <div>
                            <div class="job-details-section">
                                <h5 class="job-details-section-title">
                                    <i class="fas fa-info-circle"></i> Job Information
                                </h5>
                                <div class="job-details-info">
                                    <span class="job-details-label">Job Card:</span>
                                    <span class="job-details-value" id="jobCard">-</span>
                                </div>
                                <div class="job-details-info">
                                    <span class="job-details-label">Date Booked:</span>
                                    <span class="job-details-value" id="dateBooked">-</span>
                                </div>
                                <div class="job-details-info">
                                    <span class="job-details-label">Job Type:</span>
                                    <span class="job-details-value" id="jobType">-</span>
                                </div>
                                <div class="job-details-info">
                                    <span class="job-details-label">Duration:</span>
                                    <span class="job-details-value" id="jobDuration">-</span>
                                </div>
                            </div>
                            
                            <div class="job-details-section">
                                <h5 class="job-details-section-title">
                                    <i class="fas fa-building"></i> Company Information
                                </h5>
                                <div class="job-details-info">
                                    <span class="job-details-label">Company:</span>
                                    <span class="job-details-value" id="companyName">-</span>
                                </div>
                                <div class="job-details-info">
                                    <span class="job-details-label">ZIMRA Ref:</span>
                                    <span class="job-details-value" id="zimraRef">-</span>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <div class="job-details-section">
                                <h5 class="job-details-section-title">
                                    <i class="fas fa-user-tie"></i> Assignment
                                </h5>
                                <div class="job-details-info">
                                    <span class="job-details-label">Engineer:</span>
                                    <span class="job-details-value" id="assignedEngineer">-</span>
                                </div>
                                <div class="job-details-info">
                                    <span class="job-details-label">Approved By:</span>
                                    <span class="job-details-value" id="approvedBy">-</span>
                                </div>
                                <div class="job-details-info">
                                    <span class="job-details-label">Date Resolved:</span>
                                    <span class="job-details-value" id="dateResolved">-</span>
                                </div>
                            </div>
                            
                            <div class="job-details-section">
                                <h5 class="job-details-section-title">
                                    <i class="fas fa-file-invoice-dollar"></i> Billing
                                </h5>
                                <div class="job-details-info">
                                    <span class="job-details-label">Billed Hours:</span>
                                    <span class="job-details-value" id="billedHours">-</span>
                                </div>
                                <div class="job-details-info">
                                    <span class="job-details-label">Amount Charged:</span>
                                    <span class="job-details-value" id="amountCharged">-</span>
                                </div>
                                <div class="job-details-info">
                                    <span class="job-details-label">Time Range:</span>
                                    <span class="job-details-value" id="timeRange">-</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="job-details-notes">
                        <h5 class="job-details-section-title">
                            <i class="fas fa-sticky-note"></i> Engineer Comments
                        </h5>
                        <div class="job-details-notes-content" id="engineerComments">
                            No comments available.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="editJobBtn">Edit Job</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Logout Form -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        // Sidebar Toggle
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const mainContent = document.getElementById('mainContent');
            const mobileOverlay = document.getElementById('mobileOverlay');
            
            // Check if elements exist
            if (!sidebar || !sidebarToggle) return;
            
            // Load saved sidebar state
            const sidebarState = localStorage.getItem('sidebarCollapsed');
            if (sidebarState === 'true') {
                sidebar.classList.add('collapsed');
            }
            
            // Toggle sidebar
            sidebarToggle.addEventListener('click', function() {
                const isMobile = window.innerWidth <= 991.98;
                
                if (isMobile) {
                    sidebar.classList.toggle('mobile-show');
                    mobileOverlay.classList.toggle('show');
                    document.body.style.overflow = sidebar.classList.contains('mobile-show') ? 'hidden' : '';
                } else {
                    sidebar.classList.toggle('collapsed');
                    localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
                }
            });
            
            // Close mobile sidebar when clicking overlay
            if (mobileOverlay) {
                mobileOverlay.addEventListener('click', function() {
                    sidebar.classList.remove('mobile-show');
                    mobileOverlay.classList.remove('show');
                    document.body.style.overflow = '';
                });
            }
            
            // Handle window resize
            window.addEventListener('resize', function() {
                const isMobile = window.innerWidth <= 991.98;
                
                if (!isMobile) {
                    sidebar.classList.remove('mobile-show');
                    mobileOverlay.classList.remove('show');
                    document.body.style.overflow = '';
                }
            });
            
            // Logout functionality
            const logoutTrigger = document.getElementById('logoutTrigger');
            if (logoutTrigger) {
                logoutTrigger.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (confirm('Are you sure you want to logout?')) {
                        document.getElementById('logout-form').submit();
                    }
                });
            }
            
            // Auto-hide alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
            
            // Job Details Modal functionality
            const jobDetailsModal = document.getElementById('jobDetailsModal');
            if (jobDetailsModal) {
                // Function to load job details
                window.loadJobDetails = function(jobId) {
                    // Show loading state
                    document.getElementById('jobDescription').textContent = 'Loading...';
                    document.getElementById('jobCard').textContent = '-';
                    document.getElementById('dateBooked').textContent = '-';
                    document.getElementById('jobType').textContent = '-';
                    document.getElementById('jobDuration').textContent = '-';
                    document.getElementById('companyName').textContent = '-';
                    document.getElementById('zimraRef').textContent = '-';
                    document.getElementById('assignedEngineer').textContent = '-';
                    document.getElementById('approvedBy').textContent = '-';
                    document.getElementById('dateResolved').textContent = '-';
                    document.getElementById('billedHours').textContent = '-';
                    document.getElementById('amountCharged').textContent = '-';
                    document.getElementById('timeRange').textContent = '-';
                    document.getElementById('engineerComments').textContent = 'Loading...';
                    
                    // Fetch job details
                    fetch(`/admin/call-logs/${jobId}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                const job = data.job;
                                
                                // Update modal content
                                document.getElementById('jobDescription').textContent = job.fault_description || 'No description provided';
                                document.getElementById('jobCard').textContent = job.job_card;
                                document.getElementById('dateBooked').textContent = job.date_booked;
                                document.getElementById('jobType').textContent = job.type;
                                document.getElementById('jobDuration').textContent = job.billed_hours ? `${job.billed_hours} hours` : 'Not calculated';
                                document.getElementById('companyName').textContent = job.company_name;
                                document.getElementById('zimraRef').textContent = job.zimra_ref || 'N/A';
                                document.getElementById('assignedEngineer').textContent = job.engineer || 'Unassigned';
                                document.getElementById('approvedBy').textContent = job.approved_by || 'N/A';
                                document.getElementById('dateResolved').textContent = job.date_resolved || 'Not resolved';
                                document.getElementById('billedHours').textContent = job.billed_hours ? `${job.billed_hours} hours` : 'Not calculated';
                                document.getElementById('amountCharged').textContent = job.amount_charged ? `USD $${parseFloat(job.amount_charged).toFixed(2)}` : 'Not set';
                                document.getElementById('timeRange').textContent = (job.time_start && job.time_finish) ? `${job.time_start} - ${job.time_finish}` : 'Not set';
                                document.getElementById('engineerComments').textContent = job.engineer_comments || 'No comments available.';
                                
                                // Update badges
                                const statusBadge = document.getElementById('jobStatusBadge');
                                const typeBadge = document.getElementById('jobTypeBadge');
                                
                                statusBadge.textContent = job.status;
                                statusBadge.className = `badge status-${job.status}`;
                                
                                typeBadge.textContent = job.type;
                                typeBadge.className = `badge type-${job.type}`;
                                
                                // Update edit button
                                const editBtn = document.getElementById('editJobBtn');
                                if (editBtn) {
                                    editBtn.onclick = function() {
                                        window.location.href = `/admin/call-logs/${jobId}/edit`;
                                    };
                                }
                            } else {
                                console.error('Error loading job details:', data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching job details:', error);
                            document.getElementById('jobDescription').textContent = 'Error loading job details';
                            document.getElementById('engineerComments').textContent = 'Error loading comments';
                        });
                };
            }
            
            // Enhanced table interactions
            const enhancedTables = document.querySelectorAll('.enhanced-table, .enhanced-job-cards-table');
            enhancedTables.forEach(function(table) {
                const rows = table.querySelectorAll('tbody tr');
                rows.forEach(function(row) {
                    row.addEventListener('click', function(e) {
                        // Don't trigger if clicking on buttons or links
                        if (e.target.closest('button, a, .action-btn')) {
                            return;
                        }
                        
                        // Get job ID from data attribute or row
                        const jobId = row.dataset.jobId;
                        if (jobId && window.loadJobDetails) {
                            window.loadJobDetails(jobId);
                            const modal = new bootstrap.Modal(document.getElementById('jobDetailsModal'));
                            modal.show();
                        }
                    });
                });
            });
            
            // Form enhancements
            const enhancedForms = document.querySelectorAll('form');
            enhancedForms.forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        const originalText = submitBtn.innerHTML;
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
                        
                        // Re-enable after 5 seconds as fallback
                        setTimeout(function() {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalText;
                        }, 5000);
                    }
                });
            });
            
            // Tooltips initialization
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            // Popovers initialization
            const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
            popoverTriggerList.map(function(popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl);
            });
            
            // Keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                // Ctrl/Cmd + K for search
                if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                    e.preventDefault();
                    const searchInput = document.querySelector('input[type="search"], .enhanced-input[placeholder*="search"]');
                    if (searchInput) {
                        searchInput.focus();
                    }
                }
                
                // Escape to close modals
                if (e.key === 'Escape') {
                    const openModals = document.querySelectorAll('.modal.show');
                    openModals.forEach(function(modal) {
                        const bsModal = bootstrap.Modal.getInstance(modal);
                        if (bsModal) {
                            bsModal.hide();
                        }
                    });
                }
            });
            
            // Initialize any additional components
            console.log('Job Cards Management System initialized successfully');
        });
        
        // Global utility functions
        window.formatCurrency = function(amount) {
            return new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD'
            }).format(amount);
        };
        
        window.formatDate = function(date) {
            return new Intl.DateTimeFormat('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            }).format(new Date(date));
        };
        
        window.formatDateTime = function(datetime) {
            return new Intl.DateTimeFormat('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            }).format(new Date(datetime));
        };
    </script>

    @stack('styles')
    @stack('scripts')

</body>
</html>
