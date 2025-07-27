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

    <style>
        :root {
            /* Modern Professional Color Scheme */
            --primary: #059669;           /* Emerald Green */
            --primary-light: #10B981;     /* Light Emerald */
            --primary-dark: #047857;      /* Dark Emerald */
            --secondary: #6B7280;         /* Cool Gray */
            --accent: #8B5CF6;            /* Purple accent */
            --success: #059669;
            --warning: #F59E0B;
            --danger: #DC2626;
            --info: #0EA5E9;
            
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
            
            /* Dark Mode Colors */
            --dark-primary: #10B981;
            --dark-bg: #0F172A;           /* Slate 900 */
            --dark-surface: #1E293B;      /* Slate 800 */
            --dark-surface-light: #334155; /* Slate 700 */
            --dark-text: #F1F5F9;         /* Slate 100 */
            --dark-text-muted: #94A3B8;   /* Slate 400 */
            
            /* Status Colors */
            --ticket-open: #3B82F6;       /* Blue */
            --ticket-pending: #F59E0B;    /* Amber */
            --ticket-solved: #059669;     /* Emerald */
            --ticket-closed: #6B7280;     /* Gray */
            --priority-high: #DC2626;     /* Red */
            --priority-medium: #F59E0B;   /* Amber */
            --priority-low: #059669;      /* Emerald */
            
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
            --primary: var(--dark-primary);
            --white: var(--dark-surface);
            --gray-50: var(--dark-surface-light);
            --gray-100: var(--dark-surface);
            --gray-200: #475569;
            --gray-300: #64748B;
            --gray-400: #94A3B8;
            --gray-500: #CBD5E1;
            --gray-600: #E2E8F0;
            --gray-700: #F1F5F9;
            --gray-800: var(--dark-text);
            --gray-900: var(--dark-text);
            background-color: var(--dark-bg) !important;
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
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1), var(--shadow-md);
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

        .navbar-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
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
            top: 4px;
            right: 4px;
            width: 18px;
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

        /* Modern Sidebar */
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
        }

        .nav-link:hover {
            background: linear-gradient(90deg, rgba(5, 150, 105, 0.08), rgba(5, 150, 105, 0.04));
            color: var(--primary);
            transform: translateX(4px);
        }

        .nav-link.active {
            background: linear-gradient(90deg, rgba(5, 150, 105, 0.15), rgba(5, 150, 105, 0.08));
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
        }

        .sidebar.collapsed .nav-icon {
            margin-right: 0;
        }

        .nav-text {
            transition: var(--transition);
            font-size: 0.95rem;
        }

        .sidebar.collapsed .nav-text {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }

        .nav-badge {
            margin-left: auto;
            background: var(--gray-200);
            color: var(--gray-700);
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.25rem 0.5rem;
            border-radius: var(--border-radius);
            min-width: 20px;
            text-align: center;
        }

        /* Enhanced Navigation Groups */
        .nav-group {
            margin-bottom: 0.5rem;
        }

        .nav-group-toggle {
            cursor: pointer;
            user-select: none;
        }

        .nav-group-toggle .nav-link::after {
            content: '\f107';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            right: 1.5rem;
            transition: transform 0.3s ease;
            font-size: 0.9rem;
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
        }

        .nav-group-submenu .nav-link {
            padding-left: 3.75rem;
            font-size: 0.9rem;
            margin-left: 1rem;
            margin-right: 1.5rem;
            font-weight: 500;
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
            position: sticky;
            bottom: 0;
            margin-top: auto;
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

        /* Enhanced Dropdowns */
        .dropdown-menu {
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-xl);
            min-width: 240px;
            border: 1px solid var(--gray-200);
            padding: 0.75rem;
            margin-top: 0.5rem;
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
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
        }

        .dropdown-item:hover {
            background: var(--gray-100);
            color: var(--primary);
            transform: translateX(4px);
        }

        .dropdown-item i {
            font-size: 1rem;
            min-width: 18px;
        }

        .dropdown-divider {
            margin: 0.75rem 0;
            border-color: var(--gray-200);
        }

        /* Enhanced Notifications */
        .notification-dropdown {
            width: 380px;
            max-height: 500px;
            overflow-y: auto;
            padding: 0;
        }

        .notification-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--gray-200);
            font-weight: 700;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--gray-50);
        }

        .notification-item {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--gray-200);
            transition: var(--transition);
            cursor: pointer;
        }

        .notification-item:hover {
            background: var(--gray-50);
        }

        .notification-item.unread {
            background: rgba(5, 150, 105, 0.05);
            border-left: 4px solid var(--primary);
        }

        .notification-title {
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .notification-message {
            font-size: 0.9rem;
            color: var(--gray-600);
            line-height: 1.5;
        }

        .notification-time {
            font-size: 0.8rem;
            color: var(--gray-500);
            margin-top: 0.5rem;
            font-weight: 500;
        }

        .notification-footer {
            padding: 0.75rem;
            text-align: center;
            background: var(--gray-50);
            border-top: 1px solid var(--gray-200);
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
                right: -50px !important;
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
        }

        /* Custom Scrollbar */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: var(--gray-100);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: var(--gray-300);
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
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
            background: rgba(5, 150, 105, 0.1);
            color: var(--success);
            border-left-color: var(--success);
        }

        .alert-danger {
            background: rgba(220, 38, 38, 0.1);
            color: var(--danger);
            border-left-color: var(--danger);
        }

        .alert-info {
            background: rgba(14, 165, 233, 0.1);
            color: var(--info);
            border-left-color: var(--info);
        }

        .alert-warning {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
            border-left-color: var(--warning);
        }

        /* Tickets View - Modern Styling */
.tickets-view {
    padding: 0;
    max-width: 100%;
}

/* Enhanced Filter Card */
.filter-card {
    background: var(--white);
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--gray-200);
    margin-bottom: 1.5rem;
    overflow: hidden;
    transition: var(--transition);
}

.filter-card:hover {
    box-shadow: var(--shadow-md);
}

.filter-header {
    padding: 1rem 1.5rem;
    background: var(--gray-50);
    border-bottom: 1px solid var(--gray-200);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.filter-title {
    font-size: 0.95rem;
    font-weight: 600;
    color: var(--gray-800);
    margin: 0;
    display: flex;
    align-items: center;
}

.filter-body {
    padding: 1.5rem;
}

.filter-form {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.form-group {
    margin-bottom: 0;
}

.form-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--gray-600);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 0.5rem;
    display: block;
}

/* Enhanced Select */
.enhanced-select {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 0.5rem center;
    background-repeat: no-repeat;
    background-size: 1.5em 1.5em;
    padding-right: 2.5rem !important;
}

.select-wrapper {
    position: relative;
}

.select-arrow {
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
    pointer-events: none;
    color: var(--gray-500);
}

/* Enhanced Search Input */
.search-wrapper {
    position: relative;
}

.search-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--gray-500);
    font-size: 0.9rem;
}

.enhanced-input {
    padding-left: 2.5rem !important;
    transition: var(--transition);
}

.enhanced-input:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
}

/* Form Actions */
.form-actions {
    display: flex;
    align-items: flex-end;
    gap: 0.75rem;
}

/* Tickets Table */
.tickets-table-card {
    background: var(--white);
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--gray-200);
    overflow: hidden;
}

.table-header {
    padding: 1rem 1.5rem;
    background: var(--gray-50);
    border-bottom: 1px solid var(--gray-200);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.table-title {
    font-size: 0.95rem;
    font-weight: 600;
    color: var(--gray-800);
    margin: 0;
    display: flex;
    align-items: center;
}

.table-meta {
    font-size: 0.8rem;
    color: var(--gray-600);
}

.table-container {
    overflow-x: auto;
}

.enhanced-tickets-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    min-width: 800px;
}

.enhanced-tickets-table thead th {
    background: var(--gray-50);
    color: var(--gray-700);
    font-weight: 600;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 0.75rem 1rem;
    border-bottom: 1px solid var(--gray-200);
    position: sticky;
    top: 0;
    z-index: 10;
}

.enhanced-tickets-table tbody tr {
    transition: var(--transition);
    border-bottom: 1px solid var(--gray-200);
}

.enhanced-tickets-table tbody tr:last-child {
    border-bottom: none;
}

.enhanced-tickets-table tbody tr:hover {
    background: var(--gray-50);
}

.enhanced-tickets-table tbody td {
    padding: 1rem;
    vertical-align: middle;
    font-size: 0.85rem;
}

/* Ticket ID Badge */
.ticket-id-badge {
    font-family: 'Monaco', 'Menlo', monospace;
    background: var(--primary);
    color: var(--white);
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
    display: inline-block;
}

/* Priority Badges */
.priority-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.35rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: capitalize;
}

.priority-high {
    background: rgba(220, 38, 38, 0.1);
    color: var(--danger);
    border: 1px solid rgba(220, 38, 38, 0.2);
}

.priority-medium {
    background: rgba(245, 158, 11, 0.1);
    color: var(--warning);
    border: 1px solid rgba(245, 158, 11, 0.2);
}

.priority-low {
    background: rgba(5, 150, 105, 0.1);
    color: var(--success);
    border: 1px solid rgba(5, 150, 105, 0.2);
}

/* Status Badges */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.35rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: capitalize;
}

.status-open {
    background: rgba(59, 130, 246, 0.1);
    color: #3B82F6;
    border: 1px solid rgba(59, 130, 246, 0.2);
}

.status-resolved {
    background: rgba(5, 150, 105, 0.1);
    color: var(--success);
    border: 1px solid rgba(5, 150, 105, 0.2);
}

.status-pending {
    background: rgba(245, 158, 11, 0.1);
    color: var(--warning);
    border: 1px solid rgba(245, 158, 11, 0.2);
}

.status-closed {
    background: rgba(107, 114, 128, 0.1);
    color: var(--gray-600);
    border: 1px solid rgba(107, 114, 128, 0.2);
}

/* Update Time */
.update-time {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    color: var(--gray-600);
    font-size: 0.8rem;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.action-btn {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: var(--transition);
    color: var(--gray-600);
    background: transparent;
    border: none;
    cursor: pointer;
}

.action-btn:hover {
    background: var(--gray-100);
    color: var(--primary);
    transform: translateY(-1px);
}

.view-btn {
    color: var(--primary);
}

/* Empty State */
.empty-state {
    padding: 3rem 1rem;
    text-align: center;
}

.empty-content i {
    font-size: 2.5rem;
    color: var(--gray-300);
    margin-bottom: 1rem;
}

.empty-content h4 {
    color: var(--gray-700);
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.empty-content p {
    color: var(--gray-600);
    margin: 0;
    font-size: 0.9rem;
}

/* Pagination */
.pagination-wrapper {
    padding: 1.5rem;
    border-top: 1px solid var(--gray-200);
}

.pagination {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
}

.page-item.active .page-link {
    background: var(--primary);
    border-color: var(--primary);
    color: white;
}

.page-link {
    padding: 0.5rem 0.75rem;
    border-radius: var(--border-radius) !important;
    border: 1px solid var(--gray-300);
    color: var(--gray-700);
    font-size: 0.85rem;
    transition: var(--transition);
}

.page-link:hover {
    background: var(--gray-100);
    color: var(--primary);
}

/* Dark Mode Adjustments */
body.dark-mode {
    .filter-card,
    .tickets-table-card {
        background: var(--dark-surface);
        border-color: var(--dark-surface-light);
    }
    
    .filter-header,
    .table-header {
        background: var(--dark-surface-light);
        border-color: var(--dark-surface-light);
    }
    
    .enhanced-tickets-table thead th {
        background: var(--dark-surface-light);
        color: var(--dark-text);
    }
    
    .enhanced-tickets-table tbody tr:hover {
        background: rgba(255, 255, 255, 0.05);
    }
    
    .priority-badge,
    .status-badge {
        opacity: 0.9;
    }
    
    .page-link {
        background: var(--dark-surface);
        border-color: var(--dark-surface-light);
        color: var(--dark-text);
    }
    
    .page-link:hover {
        background: var(--dark-surface-light);
    }
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .filter-form {
        grid-template-columns: 1fr;
    }
    
    .form-actions {
        justify-content: flex-start;
    }
    
    .table-header {
        flex-direction: column;
        align-items: flex-start;
    }
}

@media (max-width: 576px) {
    .filter-header,
    .table-header {
        padding: 1rem;
    }
    
    .filter-body {
        padding: 1rem;
    }
    
    .enhanced-tickets-table tbody td {
        padding: 0.75rem;
    }
    
    .action-buttons {
        gap: 0.25rem;
    }
    
    .action-btn {
        width: 28px;
        height: 28px;
    }
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
            <input type="text" placeholder="Search tickets, customers, articles..." id="globalSearch">
        </div>
        
        <div class="navbar-actions">
            <button class="theme-toggle" id="themeToggle" aria-label="Toggle theme">
                <i class="fas fa-moon"></i>
            </button>
            
            <div class="dropdown">
                <div class="navbar-notification dropdown-toggle" id="notificationTrigger" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-bell"></i>
                    @auth
                        @php
                            $unreadCount = 0; // Replace with actual notification count from database
                        @endphp
                        @if($unreadCount > 0)
                            <span class="navbar-notification-badge">{{ $unreadCount }}</span>
                        @endif
                    @endauth
                </div>
                
                <ul class="dropdown-menu dropdown-menu-end notification-dropdown" aria-labelledby="notificationTrigger">
                    <li class="notification-header">
                        <span>Notifications</span>
                        <a href="{{ route('notifications.read-all') }}" class="text-primary" style="font-size: 0.8rem; font-weight: 600;">Mark all read</a>
                    </li>
                    <!-- Dynamic notifications would go here -->
                    <li class="notification-item">
                        <div class="notification-title">
                            <span>No new notifications</span>
                        </div>
                        <div class="notification-message">You're all caught up!</div>
                    </li>
                    <li class="notification-footer">
                        <a href="{{ route('notifications.index') }}" class="text-primary" style="font-weight: 600;">View all notifications</a>
                    </li>
                </ul>
            </div>
            
            <div class="dropdown">
                <div class="navbar-user dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="user-avatar">
                        @if(Auth::user() && Auth::user()->name)
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        @else
                            <i class="fas fa-user"></i>
                        @endif
                    </div>
                    <div class="user-info d-none d-lg-block">
                        <div class="user-name" id="greetingText">{{ Auth::user()->name ?? 'User' }}</div>
                        <div class="greeting">{{ ucfirst(Auth::user()->role ?? 'Administrator') }}</div>
                    </div>
                </div>
                
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li><a class="dropdown-item" href="#"><i class="fas fa-user-circle"></i> My Profile</a></li>
                    <li><a class="dropdown-item" href="#"><i class="fas fa-cog"></i> Account Settings</a></li>
                    <li><a class="dropdown-item" href="#"><i class="fas fa-bell"></i> Preferences</a></li>
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
                        <div class="nav-group-toggle" data-target="tickets-submenu">
                            <a class="nav-link {{ request()->routeIs('admin.tickets.*') ? 'active' : '' }}" href="javascript:void(0)">
                                <span class="nav-icon"><i class="fas fa-ticket-alt"></i></span>
                                <span class="nav-text">Tickets</span>
                            </a>
                        </div>
                        <div class="nav-group-submenu" id="tickets-submenu">
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
                        <div class="nav-group-toggle" data-target="jobs-submenu">
                            <a class="nav-link {{ request()->routeIs('admin.call-logs.*') ? 'active' : '' }}" href="javascript:void(0)">
                                <span class="nav-icon"><i class="fas fa-briefcase"></i></span>
                                <span class="nav-text">Jobs</span>
                            </a>
                        </div>
                        <div class="nav-group-submenu" id="jobs-submenu">
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
                            <a class="nav-link {{ request()->routeIs('admin.call-logs.create') ? 'active' : '' }}" href="{{ route('admin.call-logs.create') }}">
                                <span class="nav-icon"><i class="fas fa-plus-circle"></i></span>
                                <span class="nav-text">New Job Card</span>
                            </a>
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
                        <div class="nav-group-toggle" data-target="content-submenu">
                            <a class="nav-link {{ request()->routeIs('admin.faqs.*') || request()->routeIs('admin.blogs.*') || request()->routeIs('admin.services.*') ? 'active' : '' }}" href="javascript:void(0)">
                                <span class="nav-icon"><i class="fas fa-edit"></i></span>
                                <span class="nav-text">Content</span>
                            </a>
                        </div>
                        <div class="nav-group-submenu" id="content-submenu">
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
                        <div class="nav-group-toggle" data-target="newsletter-submenu">
                            <a class="nav-link {{ request()->routeIs('admin.newsletters.*') || request()->routeIs('admin.subscribers.*') ? 'active' : '' }}" href="javascript:void(0)">
                                <span class="nav-icon"><i class="fas fa-envelope"></i></span>
                                <span class="nav-text">Newsletter</span>
                            </a>
                        </div>
                        <div class="nav-group-submenu" id="newsletter-submenu">
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

            <!-- Administration Section -->
            <li class="nav-section">
                <div class="nav-section-title">Administration</div>
                <ul>
                    <!-- Reports Section -->
                    <li class="nav-group">
                        <div class="nav-group-toggle" data-target="reports-submenu">
                            <a class="nav-link {{ request()->routeIs('admin.call-reports.*') || request()->routeIs('admin.call-logs.reports') ? 'active' : '' }}" href="javascript:void(0)">
                                <span class="nav-icon"><i class="fas fa-chart-line"></i></span>
                                <span class="nav-text">Reports</span>
                            </a>
                        </div>
                        <div class="nav-group-submenu" id="reports-submenu">
                            <a class="nav-link {{ request()->routeIs('admin.call-reports.*') ? 'active' : '' }}" href="{{ route('admin.call-reports.index') }}">
                                <span class="nav-icon"><i class="fas fa-chart-bar"></i></span>
                                <span class="nav-text">Tickets Reports</span>
                            </a>
                            <a class="nav-link {{ request()->routeIs('admin.call-logs.reports') ? 'active' : '' }}" href="{{ route('admin.call-logs.reports') }}">
                                <span class="nav-icon"><i class="fas fa-chart-pie"></i></span>
                                <span class="nav-text">Jobs Reports</span>
                            </a>
                        </div>
                    </li>

                    <!-- User Management -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                            <span class="nav-icon"><i class="fas fa-users-cog"></i></span>
                            <span class="nav-text">Users</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>

        <!-- Profile Section at Bottom of Sidebar -->
        <div class="sidebar-profile" id="sidebarProfile">
            <div class="user-avatar">
                @if(Auth::user() && Auth::user()->name)
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                @else
                    <i class="fas fa-user"></i>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const mainContent = document.getElementById('mainContent');
            const mobileOverlay = document.getElementById('mobileOverlay');
            const themeToggle = document.getElementById('themeToggle');
            const body = document.body;
            
            // Set greeting based on time of day
            function setGreeting() {
                const hour = new Date().getHours();
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
            
            // Initialize collapsible navigation groups
            initializeNavGroups();
            
            // Set initial greeting
            setGreeting();
            
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
            
            // Enhanced search functionality
            const globalSearch = document.getElementById('globalSearch');
            if (globalSearch) {
                globalSearch.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        const searchTerm = this.value.trim();
                        if (searchTerm) {
                            // Implement global search functionality
                            console.log('Searching for:', searchTerm);
                            // You can redirect to a search results page or implement live search
                        }
                    }
                });
            }
            
            function initializeNavGroups() {
                const navGroupToggles = document.querySelectorAll('.nav-group-toggle');
                
                navGroupToggles.forEach(toggle => {
                    const targetId = toggle.getAttribute('data-target');
                    const submenu = document.getElementById(targetId);
                    const navLink = toggle.querySelector('.nav-link');
                    
                    if (!submenu) return;
                    
                    // Check if any submenu item is active
                    const hasActiveChild = submenu.querySelector('.nav-link.active');
                    if (hasActiveChild) {
                        submenu.style.maxHeight = submenu.scrollHeight + 'px';
                        toggle.classList.remove('collapsed');
                    } else {
                        submenu.style.maxHeight = '0px';
                        toggle.classList.add('collapsed');
                    }
                    
                    toggle.addEventListener('click', function(e) {
                        e.preventDefault();
                        
                        // Don't toggle if sidebar is collapsed
                        if (sidebar.classList.contains('collapsed')) return;
                        
                        const isCollapsed = toggle.classList.contains('collapsed');
                        
                        if (isCollapsed) {
                            submenu.style.maxHeight = submenu.scrollHeight + 'px';
                            toggle.classList.remove('collapsed');
                        } else {
                            submenu.style.maxHeight = '0px';
                            toggle.classList.add('collapsed');
                        }
                    });
                });
            }
            
            // Sidebar profile dropdown
            const sidebarProfile = document.getElementById('sidebarProfile');
            const userDropdown = document.getElementById('userDropdown');
            
            if (sidebarProfile && userDropdown) {
                sidebarProfile.addEventListener('click', function() {
                    // Trigger the navbar user dropdown
                    const dropdown = new bootstrap.Dropdown(userDropdown);
                    dropdown.toggle();
                });
            }
            
            // Add smooth scroll behavior
            document.documentElement.style.scrollBehavior = 'smooth';
            
            console.log('Modern Admin Dashboard initialized successfully');
        });

        // Add some interactive elements
        document.addEventListener('click', function(e) {
            if (e.target.matches('.btn')) {
                e.target.style.transform = 'scale(0.98)';
                setTimeout(() => {
                    e.target.style.transform = '';
                }, 150);
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
