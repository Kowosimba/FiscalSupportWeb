<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard')</title>
    <meta name="description" content="Professional Helpdesk System">

    <!-- Bootstrap 5 & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
       <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/img/favi.png') }}">

    <style>
        :root {
            /* Enhanced Dark Green Color Scheme */
            --primary: #065f46;
            --primary-light: #047857;
            --primary-lighter: #059669;
            --primary-dark: #064e3b;
            --secondary: #6B7280;
            --accent: #10B981;
            --success: #065f46;
            --warning: #F59E0B;
            --danger: #DC2626;
            --info: #047857;
            
            /* Neutral Modern Grays */
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
            --gray-900: #111827;
            
            /* Layout & Effects */
            --sidebar-width: 280px;
            --sidebar-collapsed: 80px;
            --navbar-height: 72px;
            --border-radius: 8px;
            --border-radius-lg: 12px;
            --border-radius-xl: 16px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
            --glass-bg: rgba(255, 255, 255, 0.95);
            --glass-border: rgba(255, 255, 255, 0.2);
        }

        /* Dark Mode Variables */
        body.dark-mode {
            --primary: #10B981;
            --primary-light: #34D399;
            --primary-lighter: #6EE7B7;
            --white: #1E293B;
            --gray-50: #334155;
            --gray-100: #1E293B;
            --gray-200: #475569;
            --gray-300: #64748B;
            --gray-400: #94A3B8;
            --gray-500: #CBD5E1;
            --gray-600: #E2E8F0;
            --gray-700: #F1F5F9;
            --gray-800: #F1F5F9;
            --gray-900: #F1F5F9;
            background-color: #0F172A !important;
            --glass-bg: rgba(30, 41, 59, 0.95);
            --glass-border: rgba(255, 255, 255, 0.1);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--gray-50);
            color: var(--gray-800);
            line-height: 1.6;
            overflow-x: hidden;
            transition: var(--transition);
            font-weight: 400;
        }

        /* Enhanced Navbar */
        .navbar {
            height: var(--navbar-height);
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--gray-200);
            padding: 0 2rem;
            z-index: 1030;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            display: flex;
            align-items: center;
            box-shadow: var(--shadow-sm);
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            transition: var(--transition);
        }

        .navbar-brand:hover {
            transform: scale(1.02);
            color: var(--primary);
        }

        .navbar-brand-logo {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: var(--white);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: var(--border-radius-lg);
            font-weight: 800;
            font-size: 1.1rem;
            box-shadow: var(--shadow-md);
        }

        .navbar-search {
            flex: 1;
            max-width: 600px;
            margin: 0 2rem;
            position: relative;
        }

        .navbar-search input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 3rem;
            border: 1px solid var(--gray-300);
            border-radius: var(--border-radius-lg);
            background-color: var(--white);
            transition: var(--transition);
            color: var(--gray-800);
            font-size: 0.95rem;
            box-shadow: var(--shadow-sm);
        }

        .navbar-search input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(6, 95, 70, 0.1), var(--shadow-md);
            transform: translateY(-1px);
        }

        .navbar-search i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-500);
            font-size: 1rem;
        }

        /* Search Results Dropdown */
        .search-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-xl);
            max-height: 400px;
            overflow-y: auto;
            z-index: 1040;
            display: none;
            margin-top: 0.5rem;
        }

        .search-results.show {
            display: block;
        }

        .search-result-item {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid var(--gray-100);
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .search-result-item:hover {
            background: var(--gray-50);
        }

        .search-result-item:last-child {
            border-bottom: none;
        }

        .search-result-icon {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: var(--border-radius);
            font-size: 0.9rem;
        }

        .search-result-icon.contact {
            background: rgba(6, 95, 70, 0.1);
            color: var(--primary);
        }

        .search-result-icon.ticket {
            background: rgba(245, 158, 11, 0.1);
            color: #F59E0B;
        }

        .search-result-icon.job {
            background: rgba(4, 120, 87, 0.1);
            color: var(--primary-light);
        }

        .search-result-content {
            flex: 1;
        }

        .search-result-title {
            font-weight: 600;
            color: var(--gray-800);
            font-size: 0.9rem;
        }

        .search-result-subtitle {
            font-size: 0.8rem;
            color: var(--gray-500);
            margin-top: 0.25rem;
        }

        .search-no-results {
            padding: 2rem 1rem;
            text-align: center;
            color: var(--gray-500);
        }

        .search-loading {
            padding: 1.5rem 1rem;
            text-align: center;
            color: var(--gray-500);
        }

        .navbar-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        /* Current Time Display */
        .navbar-time {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 0.5rem 1rem;
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: var(--border-radius-lg);
            font-size: 0.75rem;
            color: var(--gray-600);
            min-width: 140px;
            box-shadow: var(--shadow-sm);
        }

        .navbar-time-date {
            font-weight: 600;
            color: var(--gray-800);
        }

        .navbar-time-clock {
            font-family: 'Monaco', 'Menlo', monospace;
            color: var(--primary);
            font-weight: 700;
        }

        .navbar-notification, .theme-toggle {
            position: relative;
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: var(--border-radius-lg);
            color: var(--gray-600);
            transition: var(--transition);
            cursor: pointer;
            background: var(--white);
            border: 1px solid var(--gray-200);
            box-shadow: var(--shadow-sm);
        }

        .navbar-notification:hover, .theme-toggle:hover {
            background: var(--gray-100);
            color: var(--primary);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .navbar-notification-badge {
            position: absolute;
            top: 6px;
            right: 6px;
            min-width: 18px;
            height: 18px;
            background: linear-gradient(135deg, var(--danger), #EF4444);
            color: var(--white);
            font-size: 0.65rem;
            font-weight: 700;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid var(--white);
            animation: pulse 2s infinite;
            padding: 0 4px;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .navbar-user {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: var(--border-radius-lg);
            transition: var(--transition);
            background: var(--white);
            border: 1px solid var(--gray-200);
        }

        .navbar-user:hover {
            background: var(--gray-100);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: var(--border-radius-lg);
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: var(--white);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1rem;
            overflow: hidden;
        }

        .user-info {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--gray-800);
        }

        .greeting {
            font-size: 0.75rem;
            color: var(--gray-500);
            font-weight: 500;
        }

        /* Modern Sidebar with Fixed Navigation Button Issues */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--white);
            position: fixed;
            top: var(--navbar-height);
            left: 0;
            bottom: 0;
            border-right: 1px solid var(--gray-200);
            overflow-y: auto;
            overflow-x: hidden;
            transition: var(--transition);
            z-index: 1020;
            padding: 1.5rem 0 0 0;
            display: flex;
            flex-direction: column;
            box-shadow: var(--shadow-sm);
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed);
        }

        .sidebar-nav {
            list-style: none;
            padding: 0;
            flex: 1;
            padding-bottom: 120px; /* Extra space for profile */
        }

        .nav-section {
            margin-bottom: 2rem;
        }

        .nav-section-title {
            padding: 0.75rem 1.5rem 0.5rem 1.5rem;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--gray-500);
            transition: var(--transition);
            position: relative;
        }

        .nav-section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 1.5rem;
            right: 1.5rem;
            height: 1px;
            background: linear-gradient(90deg, var(--primary), transparent);
            opacity: 0.3;
        }

        .sidebar.collapsed .nav-section-title {
            opacity: 0;
            height: 0;
            padding: 0;
            margin: 0;
            overflow: hidden;
        }

        .nav-item {
            margin: 0.25rem 0;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.875rem 1.5rem;
            color: var(--gray-700);
            text-decoration: none;
            border-radius: 0 var(--border-radius-lg) var(--border-radius-lg) 0;
            transition: var(--transition);
            position: relative;
            font-weight: 500;
            margin-right: 1rem;
            min-height: 48px; /* Ensure consistent button height */
            white-space: nowrap; /* Prevent text wrapping */
        }

        .nav-link:hover {
            background: linear-gradient(90deg, rgba(6, 95, 70, 0.08), rgba(6, 95, 70, 0.04));
            color: var(--primary);
            transform: translateX(4px);
            text-decoration: none;
        }

        .nav-link.active {
            background: linear-gradient(90deg, rgba(6, 95, 70, 0.15), rgba(6, 95, 70, 0.08));
            color: var(--primary);
            font-weight: 600;
            box-shadow: var(--shadow-sm);
        }

        .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: linear-gradient(180deg, var(--primary), var(--primary-light));
            border-radius: 0 2px 2px 0;
        }

        .nav-icon {
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-size: 1.1rem;
            transition: var(--transition);
            flex-shrink: 0; /* Prevent icon from shrinking */
        }

        .sidebar.collapsed .nav-icon {
            margin-right: 0;
        }

        .nav-text {
            transition: var(--transition);
            font-size: 0.95rem;
            overflow: hidden; /* Hide text overflow */
        }

        .sidebar.collapsed .nav-text {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }

        /* Enhanced Navigation Groups with Fixed Button Issues */
        .nav-group {
            margin-bottom: 0.5rem;
        }

        .nav-group-toggle {
            cursor: pointer;
            user-select: none;
        }

        .nav-group-toggle .nav-link {
            position: relative;
            padding-right: 3rem; /* Space for arrow */
        }

        .nav-group-toggle .nav-link::after {
            content: '\f107';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            right: 1.5rem;
            transition: transform 0.3s ease;
            font-size: 0.9rem;
            color: var(--gray-500);
        }

        .sidebar.collapsed .nav-group-toggle .nav-link::after {
            display: none;
        }

        .nav-group-toggle.collapsed .nav-link::after {
            transform: rotate(-90deg);
        }

        .nav-group-submenu {
            overflow: hidden;
            transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            max-height: 0;
        }

        .nav-group-submenu.expanded {
            max-height: 500px; /* Large enough for all submenu items */
        }

        .nav-group-submenu .nav-link {
            padding-left: 3.75rem;
            font-size: 0.9rem;
            margin-left: 1rem;
            margin-right: 1.5rem;
            font-weight: 500;
            min-height: 44px; /* Slightly smaller for sub-items */
        }

        .nav-group-submenu .nav-link .nav-icon {
            font-size: 0.9rem;
            width: 20px;
            height: 20px;
        }

        .sidebar.collapsed .nav-group-submenu {
            display: none;
        }

        /* Enhanced Sidebar Profile */
        .sidebar-profile {
            padding: 1.5rem;
            border-top: 1px solid var(--gray-200);
            background: linear-gradient(135deg, var(--gray-50), var(--white));
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 1rem;
            cursor: pointer;
            margin-top: auto;
            flex-shrink: 0;
            position: sticky;
            bottom: 0;
            z-index: 10;
        }

        .sidebar-profile:hover {
            background: linear-gradient(135deg, var(--gray-100), var(--gray-50));
        }

        .sidebar-profile .user-avatar {
            width: 44px;
            height: 44px;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .sidebar-profile-info {
            display: flex;
            flex-direction: column;
            transition: var(--transition);
            overflow: hidden;
            white-space: nowrap;
        }

        .sidebar.collapsed .sidebar-profile-info {
            opacity: 0;
            width: 0;
            height: 0;
            overflow: hidden;
        }

        .sidebar-profile-name {
            font-weight: 700;
            font-size: 0.95rem;
            color: var(--gray-900);
        }

        .sidebar-profile-role {
            font-size: 0.8rem;
            color: var(--gray-600);
            font-weight: 500;
        }

        /* Enhanced Toggle Button */
        #sidebarToggle {
            font-size: 1.25rem;
            color: var(--gray-600);
            background: var(--white);
            border: 1px solid var(--gray-200);
            outline: none;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
            padding: 0.5rem;
            border-radius: var(--border-radius-lg);
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        #sidebarToggle:hover, #sidebarToggle:focus {
            color: var(--white);
            background: var(--primary);
            border-color: var(--primary);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
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

        /* Enhanced Dropdowns - Fixed positioning */
        .dropdown-menu {
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-xl);
            min-width: 240px;
            border: 1px solid var(--gray-200);
            padding: 0.75rem;
            margin-top: 0.5rem;
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            max-height: 400px;
            overflow-y: auto;
            position: absolute !important;
            z-index: 1050;
        }

        .dropdown-item {
            padding: 0.875rem 1rem;
            border-radius: var(--border-radius);
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: var(--gray-700);
            font-weight: 500;
            white-space: nowrap;
        }

        .dropdown-item:hover {
            background: var(--gray-100);
            color: var(--primary);
            transform: translateX(4px);
        }

        .dropdown-item i {
            font-size: 1rem;
            min-width: 18px;
            flex-shrink: 0;
        }

        .dropdown-divider {
            margin: 0.75rem 0;
            border-color: var(--gray-200);
        }

        /* Enhanced Notifications Dropdown */
        .notification-dropdown {
            width: 380px;
            max-height: 500px;
            overflow-y: auto;
            padding: 0;
            right: 0 !important;
            left: auto !important;
        }

        .notification-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--gray-200);
            font-weight: 700;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--gray-50);
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .notification-item {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--gray-200);
            transition: var(--transition);
            cursor: pointer;
            position: relative;
        }

        .notification-item:hover {
            background: var(--gray-50);
        }

        .notification-item.unread {
            background: rgba(6, 95, 70, 0.05);
            border-left: 4px solid var(--primary);
        }

        .notification-item.unread::before {
            content: '';
            position: absolute;
            top: 50%;
            right: 1rem;
            transform: translateY(-50%);
            width: 8px;
            height: 8px;
            background: var(--primary);
            border-radius: 50%;
        }

        .notification-title {
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            font-size: 0.9rem;
        }

        .notification-message {
            font-size: 0.85rem;
            color: var(--gray-600);
            line-height: 1.4;
            margin-bottom: 0.5rem;
        }

        .notification-time {
            font-size: 0.75rem;
            color: var(--gray-500);
            font-weight: 500;
        }

        .notification-priority {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .notification-priority.high {
            background: rgba(220, 38, 38, 0.1);
            color: var(--danger);
        }

        .notification-priority.medium {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }

        .notification-priority.normal {
            background: rgba(6, 95, 70, 0.1);
            color: var(--success);
        }

        .notification-footer {
            padding: 0.75rem;
            text-align: center;
            background: var(--gray-50);
            border-top: 1px solid var(--gray-200);
            position: sticky;
            bottom: 0;
        }

        .notification-empty {
            padding: 2rem 1rem;
            text-align: center;
            color: var(--gray-500);
        }

        .notification-empty i {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: var(--gray-300);
        }

        /* Loading States */
        .notification-loading {
            padding: 2rem 1rem;
            text-align: center;
            color: var(--gray-500);
        }

        .notification-loading i {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        /* Mobile Responsiveness */
        @media (max-width: 991.98px) {
            .sidebar {
                left: -100%;
                width: var(--sidebar-width);
            }
            
            .sidebar.mobile-show {
                left: 0;
                box-shadow: var(--shadow-xl);
            }
            
            .main-content, 
            .sidebar.collapsed ~ .main-content {
                margin-left: 0;
                padding: calc(var(--navbar-height) + 1rem) 1rem 1rem 1rem;
            }
            
            .user-info {
                display: none !important;
            }

            .navbar-search {
                margin: 0 1rem;
                max-width: 300px;
            }

            .notification-dropdown {
                width: 320px;
                right: -20px !important;
            }

            .navbar-time {
                display: none;
            }
        }

        .mobile-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            z-index: 1010;
            backdrop-filter: blur(8px);
        }

        .mobile-overlay.show {
            display: block;
        }

        @media (max-width: 768px) {
            .navbar {
                padding: 0 1rem;
            }
            
            .navbar-search {
                max-width: 200px;
            }
        }

        @media (max-width: 576px) {
            .navbar-search {
                display: none;
            }
            
            .notification-dropdown {
                width: calc(100vw - 2rem);
                right: 1rem !important;
                left: 1rem !important;
            }
        }

        /* Custom Scrollbar */
        .sidebar::-webkit-scrollbar,
        .notification-dropdown::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track,
        .notification-dropdown::-webkit-scrollbar-track {
            background: var(--gray-100);
        }

        .sidebar::-webkit-scrollbar-thumb,
        .notification-dropdown::-webkit-scrollbar-thumb {
            background: var(--gray-300);
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover,
        .notification-dropdown::-webkit-scrollbar-thumb:hover {
            background: var(--gray-400);
        }

        /* Alert Enhancements */
        .alert {
            border: none;
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-md);
            border-left: 4px solid;
        }

        .alert-success {
            background: rgba(6, 95, 70, 0.1);
            color: var(--success);
            border-left-color: var(--success);
        }

        .alert-danger {
            background: rgba(220, 38, 38, 0.1);
            color: var(--danger);
            border-left-color: var(--danger);
        }

        .alert-info {
            background: rgba(4, 120, 87, 0.1);
            color: var(--info);
            border-left-color: var(--info);
        }

        .alert-warning {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
            border-left-color: var(--warning);
        }

        /* Fix for cut-off dropdowns */
        .dropdown {
            position: static;
        }

        .dropdown-menu {
            position: fixed !important;
        }

        /* Ensure proper z-index stacking */
        .navbar {
            z-index: 1030;
        }

        .sidebar {
            z-index: 1020;
        }

        .dropdown-menu {
            z-index: 1050;
        }

        .modal {
            z-index: 1060;
        }

        .mobile-overlay {
            z-index: 1010;
        }

        /* Button color fixes */
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
            color: var(--white);
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
            color: var(--white);
        }

        .btn-outline-primary {
            color: var(--primary);
            border-color: var(--primary);
        }

        .btn-outline-primary:hover {
            background-color: var(--primary);
            border-color: var(--primary);
            color: var(--white);
        }

        .text-primary {
            color: var(--primary) !important;
        }

        .bg-primary {
            background-color: var(--primary) !important;
        }

        .border-primary {
            border-color: var(--primary) !important;
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="d-flex align-items-center">
            <button class="btn btn-sm me-3" id="sidebarToggle" aria-label="Toggle sidebar">
                <i class="fas fa-bars"></i>
            </button>
            <a href="{{ route('admin.index') }}" class="navbar-brand">
                <span class="navbar-brand-logo">H</span>
                <span class="d-none d-md-inline">@yield('brand-title', 'Helpdesk')</span>
            </a>
        </div>
        
        <div class="navbar-search">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Search tickets, contacts, jobs..." id="globalSearch">
            <div class="search-results" id="searchResults"></div>
        </div>
        
        <div class="navbar-actions">
            <!-- Current Date & Time Display -->
          <div class="navbar-time d-none d-lg-flex">
    <div class="navbar-time-date" id="currentDate">{{ now()->addHours(2)->format('Y-m-d') }}</div>
    <div class="navbar-time-clock" id="currentTime">{{ now()->addHours(2)->format('H:i:s') }}</div>
</div>

            <button class="theme-toggle" id="themeToggle" aria-label="Toggle theme">
                <i class="fas fa-moon"></i>
            </button>
            
            <!-- Notifications Dropdown -->
            <div class="dropdown">
                <div class="navbar-notification dropdown-toggle" id="notificationTrigger" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-bell"></i>
                    <span class="navbar-notification-badge" id="notificationBadge" style="display: none;">0</span>
                </div>
                
                <ul class="dropdown-menu dropdown-menu-end notification-dropdown" aria-labelledby="notificationTrigger">
                    <li class="notification-header">
                        <span>Notifications</span>
                        <button class="btn btn-sm btn-link text-primary p-0" id="markAllRead" style="font-size: 0.8rem; font-weight: 600;">
                            Mark all read
                        </button>
                    </li>
                    <div id="notificationsList">
                        <li class="notification-loading">
                            <i class="fas fa-spinner fa-spin"></i>
                            <div>Loading notifications...</div>
                        </li>
                    </div>
                    <li class="notification-footer">
                        <a href="{{ route('notifications.index') }}" class="text-primary" style="font-weight: 600; text-decoration: none;">
                            View all notifications
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- User Dropdown -->
            <div class="dropdown">
                <div class="navbar-user dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="user-avatar">
                        @if(Auth::user() && Auth::user()->avatar)
                            <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: inherit;">
                        @elseif(Auth::user() && Auth::user()->name)
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        @else
                            U
                        @endif
                    </div>
                    <div class="user-info d-none d-lg-block">
                        <div class="user-name" id="greetingText">{{ Auth::user()->name ?? 'User' }}</div>
                        <div class="greeting">{{ ucfirst(Auth::user()->role ?? 'Administrator') }}</div>
                    </div>
                </div>
                
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li><a class="dropdown-item" href="{{ route('profile.show') }}"><i class="fas fa-user-circle"></i> My Profile</a></li>
                    <li><a class="dropdown-item" href="{{ route('notifications.index') }}"><i class="fas fa-bell"></i> Notifications</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item" href="#" id="logoutTrigger">
                            <i class="fas fa-sign-out-alt"></i> Sign Out
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar" aria-label="Main navigation">
        <ul class="sidebar-nav">
            <!-- Dashboard -->
            <li class="nav-section">
                <ul>
                    <li class="nav-item">
                        <a href="{{ route('admin.index') }}" class="nav-link {{ request()->routeIs('admin.index') ? 'active' : '' }}">
                            <span class="nav-icon"><i class="fas fa-chart-pie"></i></span>
                            <span class="nav-text">Dashboard</span>
                        </a>
                    </li>
                </ul>
            </li>
            
            <!-- Management Section -->
            <li class="nav-section">
                <div class="nav-section-title">Management</div>
                <ul>
                    <!-- Tickets Section -->
                    <li class="nav-group">
                        <div class="nav-group-toggle {{ request()->routeIs('admin.tickets.*') ? '' : 'collapsed' }}" data-target="tickets-submenu">
                            <a class="nav-link {{ request()->routeIs('admin.tickets.*') ? 'active' : '' }}" href="javascript:void(0)">
                                <span class="nav-icon"><i class="fas fa-ticket-alt"></i></span>
                                <span class="nav-text">Tickets</span>
                            </a>
                        </div>
                        <div class="nav-group-submenu {{ request()->routeIs('admin.tickets.*') ? 'expanded' : '' }}" id="tickets-submenu">
                            <a class="nav-link {{ request()->routeIs('admin.tickets.open') ? 'active' : '' }}" href="{{ route('admin.tickets.open') }}">
                                <span class="nav-icon"><i class="fas fa-play-circle"></i></span>
                                <span class="nav-text">In Progress</span>
                            </a>
                            <a class="nav-link {{ request()->routeIs('admin.tickets.solved') ? 'active' : '' }}" href="{{ route('admin.tickets.solved') }}">
                                <span class="nav-icon"><i class="fas fa-check-circle"></i></span>
                                <span class="nav-text">Resolved</span>
                            </a>
                            <a class="nav-link {{ request()->routeIs('admin.tickets.pending') ? 'active' : '' }}" href="{{ route('admin.tickets.pending') }}">
                                <span class="nav-icon"><i class="fas fa-clock"></i></span>
                                <span class="nav-text">Pending</span>
                            </a>
                            <a class="nav-link {{ request()->routeIs('admin.tickets.unassigned') ? 'active' : '' }}" href="{{ route('admin.tickets.unassigned') }}">
                                <span class="nav-icon"><i class="fas fa-user-slash"></i></span>
                                <span class="nav-text">Unassigned</span>
                            </a>
                            <a class="nav-link {{ request()->routeIs('admin.tickets.mine') ? 'active' : '' }}" href="{{ route('admin.tickets.mine') }}">
                                <span class="nav-icon"><i class="fas fa-user-check"></i></span>
                                <span class="nav-text">My Tickets</span>
                            </a>
                        </div>
                    </li>

                    <!-- Jobs Section -->
                    <li class="nav-group">
                        <div class="nav-group-toggle {{ request()->routeIs('admin.call-logs.*') ? '' : 'collapsed' }}" data-target="jobs-submenu">
                            <a class="nav-link {{ request()->routeIs('admin.call-logs.*') ? 'active' : '' }}" href="javascript:void(0)">
                                <span class="nav-icon"><i class="fas fa-briefcase"></i></span>
                                <span class="nav-text">Jobs</span>
                            </a>
                        </div>
                        <div class="nav-group-submenu {{ request()->routeIs('admin.call-logs.*') ? 'expanded' : '' }}" id="jobs-submenu">
                            <a class="nav-link {{ request()->routeIs('admin.call-logs.my-jobs') ? 'active' : '' }}" href="{{ route('admin.call-logs.my-jobs') }}">
                                <span class="nav-icon"><i class="fas fa-user-check"></i></span>
                                <span class="nav-text">My Jobs</span>
                            </a>
                            <a class="nav-link {{ request()->routeIs('admin.call-logs.in-progress') ? 'active' : '' }}" href="{{ route('admin.call-logs.in-progress') }}">
                                <span class="nav-icon"><i class="fas fa-play-circle"></i></span>
                                <span class="nav-text">In Progress</span>
                            </a>
                            <a class="nav-link {{ request()->routeIs('admin.call-logs.completed') ? 'active' : '' }}" href="{{ route('admin.call-logs.completed') }}">
                                <span class="nav-icon"><i class="fas fa-check-circle"></i></span>
                                <span class="nav-text">Completed</span>
                            </a>
                            <a class="nav-link {{ request()->routeIs('admin.call-logs.pending') ? 'active' : '' }}" href="{{ route('admin.call-logs.pending') }}">
                                <span class="nav-icon"><i class="fas fa-clock"></i></span>
                                <span class="nav-text">Pending</span>
                            </a>
                            <a class="nav-link {{ request()->routeIs('admin.call-logs.unassigned') ? 'active' : '' }}" href="{{ route('admin.call-logs.unassigned') }}">
                                <span class="nav-icon"><i class="fas fa-user-slash"></i></span>
                                <span class="nav-text">Unassigned</span>
                            </a>
                            @if(in_array(Auth::user()->role ?? '', ['admin', 'accounts']))
                                <a class="nav-link {{ request()->routeIs('admin.call-logs.create') ? 'active' : '' }}" href="{{ route('admin.call-logs.create') }}">
                                    <span class="nav-icon"><i class="fas fa-plus-circle"></i></span>
                                    <span class="nav-text">New Job Card</span>
                                </a>
                            @endif
                        </div>
                    </li>

                    <!-- Contacts -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}" href="{{ route('admin.contacts.index') }}">
                            <span class="nav-icon"><i class="fas fa-address-book"></i></span>
                            <span class="nav-text">Contacts</span>
                        </a>
                    </li>

                    <!-- Content Management -->
                    <li class="nav-group">
                        <div class="nav-group-toggle {{ request()->routeIs('admin.faqs.*') || request()->routeIs('admin.blogs.*') || request()->routeIs('admin.services.*') ? '' : 'collapsed' }}" data-target="content-submenu">
                            <a class="nav-link {{ request()->routeIs('admin.faqs.*') || request()->routeIs('admin.blogs.*') || request()->routeIs('admin.services.*') ? 'active' : '' }}" href="javascript:void(0)">
                                <span class="nav-icon"><i class="fas fa-edit"></i></span>
                                <span class="nav-text">Content</span>
                            </a>
                        </div>
                        <div class="nav-group-submenu {{ request()->routeIs('admin.faqs.*') || request()->routeIs('admin.blogs.*') || request()->routeIs('admin.services.*') ? 'expanded' : '' }}" id="content-submenu">
                            <a class="nav-link {{ request()->routeIs('admin.faqs.*') ? 'active' : '' }}" href="{{ route('admin.faqs.index') }}">
                                <span class="nav-icon"><i class="fas fa-question-circle"></i></span>
                                <span class="nav-text">FAQs</span>
                            </a>
                            <a class="nav-link {{ request()->routeIs('admin.blogs.*') ? 'active' : '' }}" href="{{ route('admin.blogs.index') }}">
                                <span class="nav-icon"><i class="fas fa-blog"></i></span>
                                <span class="nav-text">Blog Posts</span>
                            </a>
                            <a class="nav-link {{ request()->routeIs('admin.services.*') ? 'active' : '' }}" href="{{ route('admin.services.index') }}">
                                <span class="nav-icon"><i class="fas fa-cogs"></i></span>
                                <span class="nav-text">Services</span>
                            </a>
                        </div>
                    </li>

                    <!-- Newsletter -->
                    <li class="nav-group">
                        <div class="nav-group-toggle {{ request()->routeIs('admin.newsletters.*') || request()->routeIs('admin.subscribers.*') ? '' : 'collapsed' }}" data-target="newsletter-submenu">
                            <a class="nav-link {{ request()->routeIs('admin.newsletters.*') || request()->routeIs('admin.subscribers.*') ? 'active' : '' }}" href="javascript:void(0)">
                                <span class="nav-icon"><i class="fas fa-envelope"></i></span>
                                <span class="nav-text">Newsletter</span>
                            </a>
                        </div>
                        <div class="nav-group-submenu {{ request()->routeIs('admin.newsletters.*') || request()->routeIs('admin.subscribers.*') ? 'expanded' : '' }}" id="newsletter-submenu">
                            <a class="nav-link {{ request()->routeIs('admin.newsletters.*') ? 'active' : '' }}" href="{{ route('admin.newsletters.index') }}">
                                <span class="nav-icon"><i class="fas fa-paper-plane"></i></span>
                                <span class="nav-text">Email</span>
                            </a>
                            <a class="nav-link {{ request()->routeIs('admin.subscribers.*') ? 'active' : '' }}" href="{{ route('admin.subscribers.index') }}">
                                <span class="nav-icon"><i class="fas fa-users"></i></span>
                                <span class="nav-text">Subscribers</span>
                            </a>
                        </div>
                    </li>
                </ul>
            </li>

            <!-- Administration Section - Admin Only -->
            @if(Auth::user()->role === 'admin')
            <li class="nav-section">
                <div class="nav-section-title">Administration</div>
                <ul>
                    <!-- User Management -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                            <span class="nav-icon"><i class="fas fa-users-cog"></i></span>
                            <span class="nav-text">Users</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endif
        </ul>

        <!-- Profile Section at Bottom of Sidebar -->
        <div class="sidebar-profile" id="sidebarProfile">
            <div class="user-avatar">
                @if(Auth::user() && Auth::user()->avatar)
                    <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: inherit;">
                @elseif(Auth::user() && Auth::user()->name)
                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                @else
                    U
                @endif
            </div>
            <div class="sidebar-profile-info">
                <div class="sidebar-profile-name">{{ Auth::user()->name ?? 'User' }}</div>
                <div class="sidebar-profile-role">{{ ucfirst(Auth::user()->role ?? 'Administrator') }}</div>
            </div>
        </div>
    </aside>

    <div class="mobile-overlay" id="mobileOverlay" aria-hidden="true"></div>
    
    <!-- Main Content -->
    <main class="main-content" id="mainContent">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="fas fa-info-circle me-2"></i>
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @yield('content')
    </main>

    <!-- Logout Form -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const mainContent = document.getElementById('mainContent');
            const mobileOverlay = document.getElementById('mobileOverlay');
            const themeToggle = document.getElementById('themeToggle');
            const body = document.body;
            
            // Helper function to escape HTML and prevent XSS
            function escapeHtml(unsafe) {
                if (!unsafe) return 'N/A';
                return unsafe
                    .toString()
                    .replace(/&/g, "&amp;")
                    .replace(/</g, "&lt;")
                    .replace(/>/g, "&gt;")
                    .replace(/"/g, "&quot;")
                    .replace(/'/g, "&#039;");
            }
            
           // Update time and greeting
function updateTimeAndGreeting() {
    const now = new Date();
    const hour = now.getHours();
    let greeting = '';
    const userName = "{{ Auth::user()->name ?? 'User' }}";
    const firstName = userName.split(' ')[0];
    
    if (hour < 12) {
        greeting = `Good morning, ${firstName}`;
    } else if (hour < 18) {
        greeting = `Good afternoon, ${firstName}`;
    } else {
        greeting = `Good evening, ${firstName}`;
    }
    
    const greetingElement = document.getElementById('greetingText');
    if (greetingElement) {
        greetingElement.textContent = greeting;
    }

    // Update time display (UTC+2)
    const currentTimeElement = document.getElementById('currentTime');
    const currentDateElement = document.getElementById('currentDate');
    
    if (currentTimeElement && currentDateElement) {
        // Get UTC time and add 2 hours
        const utcNow = new Date();
        const utcPlus2 = new Date(utcNow.getTime() + (2 * 60 * 60 * 1000)); // Add 2 hours in milliseconds
        
        // Format time as HH:MM:SS
        const timeString = utcPlus2.toISOString().substr(11, 8);
        // Format date as YYYY-MM-DD
        const dateString = utcPlus2.toISOString().substr(0, 10);
        
        currentTimeElement.textContent = timeString;
        currentDateElement.textContent = dateString;
    }
}
            // Check if elements exist
            if (!sidebar || !sidebarToggle) return;
            
            // Load saved sidebar state
            const sidebarState = localStorage.getItem('sidebarCollapsed');
            if (sidebarState === 'true') {
                sidebar.classList.add('collapsed');
            }
            
            // Load saved theme preference
            const themePreference = localStorage.getItem('themePreference');
            if (themePreference === 'dark') {
                body.classList.add('dark-mode');
                themeToggle.innerHTML = '<i class="fas fa-sun"></i>';
            }
            
            // Initialize components
            initializeNavGroups();
            updateTimeAndGreeting();
            initializeNotifications();
            initializeGlobalSearch();
            
            // Update time every second
            setInterval(updateTimeAndGreeting, 1000);
            
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
            
            // Toggle theme
            if (themeToggle) {
                themeToggle.addEventListener('click', function() {
                    body.classList.toggle('dark-mode');
                    
                    if (body.classList.contains('dark-mode')) {
                        localStorage.setItem('themePreference', 'dark');
                        themeToggle.innerHTML = '<i class="fas fa-sun"></i>';
                    } else {
                        localStorage.setItem('themePreference', 'light');
                        themeToggle.innerHTML = '<i class="fas fa-moon"></i>';
                    }
                });
            }
            
            // Logout functionality
            const logoutTrigger = document.getElementById('logoutTrigger');
            if (logoutTrigger) {
                logoutTrigger.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (confirm('Are you sure you want to sign out?')) {
                        document.getElementById('logout-form').submit();
                    }
                });
            }
            
            // Auto-hide alerts after 7 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    if (alert && alert.parentNode) {
                        const bsAlert = new bootstrap.Alert(alert);
                        bsAlert.close();
                    }
                }, 7000);
            });
            
            // Sidebar profile dropdown
            const sidebarProfile = document.getElementById('sidebarProfile');
            const userDropdown = document.getElementById('userDropdown');
            
            if (sidebarProfile && userDropdown) {
                sidebarProfile.addEventListener('click', function() {
                    const dropdown = new bootstrap.Dropdown(userDropdown);
                    dropdown.toggle();
                });
            }
            
            function initializeNavGroups() {
                const navGroupToggles = document.querySelectorAll('.nav-group-toggle');
                
                navGroupToggles.forEach(toggle => {
                    const targetId = toggle.getAttribute('data-target');
                    const submenu = document.getElementById(targetId);
                    
                    if (!submenu) return;
                    
                    // Check initial state based on classes
                    const isExpanded = !toggle.classList.contains('collapsed');
                    if (isExpanded) {
                        submenu.classList.add('expanded');
                    }
                    
                    toggle.addEventListener('click', function(e) {
                        e.preventDefault();
                        
                        // Don't toggle if sidebar is collapsed
                        if (sidebar.classList.contains('collapsed')) return;
                        
                        const isCurrentlyCollapsed = toggle.classList.contains('collapsed');
                        
                        if (isCurrentlyCollapsed) {
                            submenu.classList.add('expanded');
                            toggle.classList.remove('collapsed');
                        } else {
                            submenu.classList.remove('expanded');
                            toggle.classList.add('collapsed');
                        }
                    });
                });
            }
            
            function initializeGlobalSearch() {
                const globalSearch = document.getElementById('globalSearch');
                const searchResults = document.getElementById('searchResults');
                let searchTimeout;
                
                if (!globalSearch || !searchResults) return;
                
                globalSearch.addEventListener('input', function() {
                    const query = this.value.trim();
                    
                    // Clear previous timeout
                    clearTimeout(searchTimeout);
                    
                    if (query.length < 2) {
                        searchResults.classList.remove('show');
                        return;
                    }
                    
                    // Debounce search
                    searchTimeout = setTimeout(() => {
                        performSearch(query);
                    }, 300);
                });
                
                // Hide results when clicking outside
                document.addEventListener('click', function(e) {
                    if (!globalSearch.contains(e.target) && !searchResults.contains(e.target)) {
                        searchResults.classList.remove('show');
                                    }
                });
                
                // Show results when focusing on search
                globalSearch.addEventListener('focus', function() {
                    if (this.value.trim().length >= 2) {
                        searchResults.classList.add('show');
                    }
                });
                
                // Handle keyboard navigation
                globalSearch.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        searchResults.classList.remove('show');
                        this.blur();
                    }
                });
                
                function performSearch(query) {
                    // Show loading state
                    searchResults.innerHTML = `
                        <div class="search-loading">
                            <i class="fas fa-spinner fa-spin"></i>
                            <div>Searching...</div>
                        </div>
                    `;
                    searchResults.classList.add('show');
                    
                    fetch(`/admin/global-search?q=${encodeURIComponent(query)}`, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        let html = '';

                        // Check if we have any results
                        const hasResults = (data.contacts && data.contacts.length > 0) || 
                                         (data.tickets && data.tickets.length > 0) || 
                                         (data.jobs && data.jobs.length > 0);

                        if (!hasResults) {
                            html = `
                                <div class="search-no-results">
                                    <i class="fas fa-search mb-2"></i>
                                    <p>No results found for "${escapeHtml(query)}"</p>
                                    <small>Try searching with different keywords</small>
                                </div>
                            `;
                        } else {
                            // Display contacts
                            if (data.contacts && data.contacts.length > 0) {
                                data.contacts.forEach(contact => {
                                    html += `
                                        <div class="search-result-item" onclick="window.location.href='/admin/contacts/${contact.id}'">
                                            <div class="search-result-icon contact">
                                                <i class="fas fa-address-book"></i>
                                            </div>
                                            <div class="search-result-content">
                                                <div class="search-result-title">${escapeHtml(contact.name)}</div>
                                                <div class="search-result-subtitle">Contact • ${escapeHtml(contact.email)} • ${escapeHtml(contact.company)}</div>
                                            </div>
                                        </div>
                                    `;
                                });
                            }

                            // Display tickets
                            if (data.tickets && data.tickets.length > 0) {
                                data.tickets.forEach(ticket => {
                                    html += `
                                        <div class="search-result-item" onclick="window.location.href='/admin/tickets/${ticket.id}'">
                                            <div class="search-result-icon ticket">
                                                <i class="fas fa-ticket-alt"></i>
                                            </div>
                                            <div class="search-result-content">
                                                <div class="search-result-title">#${ticket.id} - ${escapeHtml(ticket.subject)}</div>
                                                <div class="search-result-subtitle">Ticket • ${escapeHtml(ticket.status)} • ${escapeHtml(ticket.company_name)}</div>
                                            </div>
                                        </div>
                                    `;
                                });
                            }

                            // Display jobs
                            if (data.jobs && data.jobs.length > 0) {
                                data.jobs.forEach(job => {
                                    html += `
                                        <div class="search-result-item" onclick="window.location.href='/admin/call-logs/${job.id}'">
                                            <div class="search-result-icon job">
                                                <i class="fas fa-briefcase"></i>
                                            </div>
                                            <div class="search-result-content">
                                                <div class="search-result-title">${escapeHtml(job.job_card)} - ${escapeHtml(job.customer_name)}</div>
                                                <div class="search-result-subtitle">Job • ${escapeHtml(job.status)} • ${escapeHtml(job.job_type)}</div>
                                            </div>
                                        </div>
                                    `;
                                });
                            }
                        }

                        searchResults.innerHTML = html;
                        searchResults.classList.add('show');
                    })
                    .catch(error => {
                        console.error('Search error:', error);
                        searchResults.innerHTML = `
                            <div class="search-no-results">
                                <i class="fas fa-exclamation-circle text-danger mb-2"></i>
                                <p>Search temporarily unavailable</p>
                                <small>Please try again in a moment</small>
                            </div>
                        `;
                        searchResults.classList.add('show');
                    });
                }
            }
            
            function initializeNotifications() {
                const notificationTrigger = document.getElementById('notificationTrigger');
                const notificationsList = document.getElementById('notificationsList');
                const notificationBadge = document.getElementById('notificationBadge');
                const markAllReadBtn = document.getElementById('markAllRead');
                
                if (!notificationTrigger || !notificationsList) return;
                
                // Load notifications on dropdown show
                notificationTrigger.addEventListener('show.bs.dropdown', function() {
                    loadNotifications();
                });
                
                // Load notification count on page load
                loadNotificationCount();
                
                // Mark all as read
                if (markAllReadBtn) {
                    markAllReadBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        markAllNotificationsAsRead();
                    });
                }
                
                // Refresh notifications every 30 seconds
                setInterval(loadNotificationCount, 30000);
                
                function loadNotifications() {
                    notificationsList.innerHTML = `
                        <li class="notification-loading">
                            <i class="fas fa-spinner fa-spin"></i>
                            <div>Loading notifications...</div>
                        </li>
                    `;
                    
                    fetch('{{ route("notifications.recent") }}', {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(notifications => {
                        displayNotifications(notifications);
                    })
                    .catch(error => {
                        console.error('Error loading notifications:', error);
                        notificationsList.innerHTML = `
                            <li class="notification-empty">
                                <i class="fas fa-exclamation-triangle"></i>
                                <div>Failed to load notifications</div>
                            </li>
                        `;
                    });
                }
                
                function loadNotificationCount() {
                    fetch('{{ route("notifications.count") }}', {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        updateNotificationBadge(data.count);
                    })
                    .catch(error => {
                        console.error('Error loading notification count:', error);
                    });
                }
                
                function displayNotifications(notifications) {
                    if (!notifications || notifications.length === 0) {
                        notificationsList.innerHTML = `
                            <li class="notification-empty">
                                <i class="fas fa-bell-slash"></i>
                                <div>No notifications</div>
                                <small>You're all caught up!</small>
                            </li>
                        `;
                        return;
                    }
                    
                    let html = '';
                    notifications.forEach(notification => {
                        const isUnread = !notification.read_at;
                        const priorityClass = notification.priority || 'normal';
                        
                        html += `
                            <li class="notification-item ${isUnread ? 'unread' : ''}" onclick="handleNotificationClick('${notification.url}')">
                                <div class="notification-title">
                                    <span>${escapeHtml(notification.title)}</span>
                                    ${notification.priority !== 'normal' ? `<span class="notification-priority ${priorityClass}">${priorityClass}</span>` : ''}
                                </div>
                                <div class="notification-message">${escapeHtml(notification.message)}</div>
                                ${notification.job_card ? `<div class="notification-message"><strong>Job:</strong> ${escapeHtml(notification.job_card)}</div>` : ''}
                                ${notification.customer_name ? `<div class="notification-message"><strong>Customer:</strong> ${escapeHtml(notification.customer_name)}</div>` : ''}
                                <div class="notification-time">${notification.created_at}</div>
                            </li>
                        `;
                    });
                    
                    notificationsList.innerHTML = html;
                }
                
                function updateNotificationBadge(count) {
                    if (count > 0) {
                        notificationBadge.textContent = count > 99 ? '99+' : count;
                        notificationBadge.style.display = 'flex';
                    } else {
                        notificationBadge.style.display = 'none';
                    }
                }
                
                function markAllNotificationsAsRead() {
                    fetch('{{ route("notifications.mark-all-read") }}', {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            updateNotificationBadge(0);
                            loadNotifications();
                            toastr.success('All notifications marked as read');
                        }
                    })
                    .catch(error => {
                        console.error('Error marking notifications as read:', error);
                        toastr.error('Failed to mark notifications as read');
                    });
                }
                
                // Global function for notification clicks
                window.handleNotificationClick = function(url) {
                    if (url) {
                        window.location.href = url;
                    }
                };
            }
            
            console.log('Enhanced Admin Dashboard initialized successfully');
        });

        // Configure Toastr
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };
    </script>

    @stack('scripts')
</body>
</html>