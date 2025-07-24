<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Faults Allocation - Dashboard</title>
    <meta name="description" content="Modern Faults Allocation Dashboard">

    <!-- Bootstrap 5 & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* Base Variables */
        :root {
            /* Green Theme Palette */
            --primary-green: #065f46;
            --primary-green-light: #047857;
            --primary-green-dark: #064e3b;
            --secondary-green: #10b981;
            --accent-green: #34d399;
            --light-green: #d1fae5;
            --ultra-light-green: #f0fdf4;
            --dark-text: #1f2937;
            --medium-text: #374151;
            --light-text: #6b7280;
            --border-color: #e5e7eb;
            --hover-bg: #f3f4f6;
            --white: #ffffff;
            --shadow: 0 2px 8px rgba(0,0,0,0.06);
            --shadow-hover: 0 4px 12px rgba(6, 95, 70, 0.15);
            
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

        /* Success Card */
.success-card {
    background: #F0FDF4;
    border: 1px solid #BBF7D0;
    border-radius: 12px;
    overflow: hidden;
}

.success-content {
    padding: 1.5rem;
    display: flex;
    gap: 1rem;
}

.success-icon {
    color: #16A34A;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.success-title {
    color: #16A34A;
    font-size: 1rem;
    font-weight: 600;
    margin: 0 0 0.25rem 0;
}

.success-message {
    color: #15803D;
    margin: 0;
    font-size: 0.9rem;
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
            border: 1px solid var(--neutral-200);
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

        .stat-icon.green { background: linear-gradient(135deg, var(--success-green), var(--primary-green-light)); }
        .stat-icon.blue { background: linear-gradient(135deg, var(--info-blue), #60a5fa); }
        .stat-icon.yellow { background: linear-gradient(135deg, var(--warning-amber), #fbbf24); }
        .stat-icon.purple { background: linear-gradient(135deg, var(--purple), #a78bfa); }
        .stat-icon.orange { background: linear-gradient(135deg, var(--orange), #fb923c); }

        .stat-content {
            flex: 1;
        }

        .stat-number {
            font-size: 2.25rem;
            font-weight: 800;
            color: var(--neutral-800);
            line-height: 1;
            margin-bottom: 0.25rem;
        }

        .stat-label {
            font-size: 1rem;
            color: var(--neutral-600);
            font-weight: 500;
        }

        .stat-footer {
            font-size: 0.825rem;
            color: var(--neutral-500);
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

        .badge.status-open { 
            background: rgba(59, 130, 246, 0.1); 
            color: var(--info-blue);
            border: 1px solid rgba(59, 130, 246, 0.2);
        }
        .badge.status-solved { 
            background: rgba(34, 197, 94, 0.1); 
            color: var(--success-green);
            border: 1px solid rgba(34, 197, 94, 0.2);
        }
        .badge.status-pending { 
            background: rgba(245, 158, 11, 0.1); 
            color: var(--warning-amber);
            border: 1px solid rgba(245, 158, 11, 0.2);
        }
        .badge.status-unassigned { 
            background: rgba(139, 92, 246, 0.1); 
            color: var(--purple);
            border: 1px solid rgba(139, 92, 246, 0.2);
        }
        .badge.priority-high { 
            background: rgba(239, 68, 68, 0.1); 
            color: var(--danger-red);
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        /* Enhanced Modal */
        .modal-content {
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-xl);
            border: 1px solid var(--neutral-200);
        }

        .modal-header {
            background: var(--neutral-50);
            border-bottom: 1px solid var(--neutral-200);
            border-radius: var(--border-radius-lg) var(--border-radius-lg) 0 0;
        }

        .modal-title {
            font-weight: 600;
            color: var(--neutral-800);
        }

        .modal-footer {
            background: var(--neutral-50);
            border-top: 1px solid var(--neutral-200);
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
            background: var(--neutral-200);
            border-color: var(--neutral-200);
            color: var(--neutral-700);
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: var(--border-radius);
            transition: var(--transition);
        }

        .btn-secondary:hover {
            background: var(--neutral-300);
            border-color: var(--neutral-300);
            color: var(--neutral-800);
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
            background: var(--neutral-100);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--neutral-300);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--neutral-400);
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
            background-color: rgb(147, 7, 7)
        }

        /* Content container */
        .content-container {
            background: var(--white);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--neutral-200);
            padding: 2rem;
            margin-bottom: 2rem;
        }

        /* Page header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--neutral-200);
        }

        .page-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--neutral-800);
            margin: 0;
        }

        .page-actions {
            display: flex;
            gap: 1rem;
        }

        /* Dashboard Navigation */
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

        /* Tickets Card */
        .tickets-card {
            background: var(--white);
            border-radius: 16px;
            box-shadow: var(--shadow);
            overflow: hidden;
            border: 1px solid var(--border-color);
        }

        .tickets-card-header {
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

        .tickets-card-header .btn {
            padding: 0.4rem 0.8rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--primary-green-dark) 100%);
            color: var(--white);
        }

        .tickets-card-header .btn:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-hover);
        }

        .tickets-card-body {
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

        .ticket-id {
            font-family: 'Monaco', 'Menlo', monospace;
            background: var(--light-green);
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.875rem;
            color: var(--primary-green-dark);
            font-weight: 600;
            border: 1px solid var(--secondary-green);
        }

        .ticket-subject {
            font-weight: 500;
            color: var(--dark-text);
            max-width: 250px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .customer-name {
            color: var(--medium-text);
            font-weight: 500;
        }

        .priority-badge, .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .priority-high {
            background: #FEF2F2;
            color: #DC2626;
            border: 1px solid #fecaca;
        }

        .priority-medium {
            background: #FFFBEB;
            color: #D97706;
            border: 1px solid #fde68a;
        }

        .priority-low {
            background: var(--light-green);
            color: var(--primary-green);
            border: 1px solid var(--accent-green);
        }

        .status-in_progress {
            background: var(--light-green);
            color: var(--primary-green-dark);
            border: 1px solid var(--accent-green);
        }

        .status-resolved {
            background: var(--ultra-light-green);
            color: var(--primary-green);
            border: 1px solid var(--secondary-green);
        }

        .status-pending {
            background: #FFFBEB;
            color: #D97706;
            border: 1px solid #fde68a;
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

        /* Filter Card */
        .filter-card {
            background: var(--white);
            border-radius: 12px;
            box-shadow: var(--shadow);
            overflow: hidden;
            border: 1px solid var(--border-color);
        }

        .filter-header {
            background: linear-gradient(135deg, var(--ultra-light-green) 0%, #f7fee7 100%);
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border-color);
        }

        .filter-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--primary-green);
            margin: 0;
            display: flex;
            align-items: center;
        }

        .filter-body {
            padding: 1.25rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.25rem;
            align-items: end;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-label {
            font-weight: 600;
            color: var(--primary-green);
            margin-bottom: 0.5rem;
            font-size: 0.8rem;
        }

        .select-wrapper {
            position: relative;
        }

        .enhanced-select {
            width: 100%;
            padding: 0.625rem 2rem 0.625rem 0.75rem;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            background: var(--white);
            font-size: 0.8rem;
            color: var(--dark-text);
            transition: all 0.3s ease;
            appearance: none;
        }

        .enhanced-select:focus {
            border-color: var(--primary-green);
            box-shadow: 0 0 0 3px rgba(6, 95, 70, 0.1);
            outline: none;
        }

        .select-arrow {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary-green);
            pointer-events: none;
        }

        .search-wrapper {
            position: relative;
        }

        .search-icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary-green);
        }

        .enhanced-input {
            width: 100%;
            padding: 0.625rem 0.75rem 0.625rem 2rem;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            background: var(--white);
            font-size: 0.8rem;
            color: var(--dark-text);
            transition: all 0.3s ease;
        }

        .enhanced-input:focus {
            border-color: var(--primary-green);
            box-shadow: 0 0 0 3px rgba(6, 95, 70, 0.1);
            outline: none;
        }

        .enhanced-input::placeholder {
            color: var(--light-text);
        }

        .form-actions {
            display: flex;
            gap: 0.75rem;
            align-self: end;
        }

        .btn {
            padding: 0.625rem 1.25rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.8rem;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--primary-green-light) 100%);
            color: var(--white);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-hover);
        }

        .btn-outline {
            background: var(--white);
            color: var(--primary-green);
            border: 2px solid var(--primary-green);
        }

        .btn-outline:hover {
            background: var(--light-green);
            border-color: var(--primary-green-light);
        }

        /* Tickets Table Card */
        .tickets-table-card {
            background: var(--white);
            border-radius: 12px;
            box-shadow: var(--shadow);
            overflow: hidden;
            border: 1px solid var(--border-color);
        }

        .table-header {
            background: linear-gradient(135deg, var(--ultra-light-green) 0%, #f7fee7 100%);
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--primary-green);
            display: flex;
            align-items: center;
        }

        .table-meta {
            color: var(--medium-text);
            font-size: 0.8rem;
        }

        .table-container {
            overflow: hidden;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .enhanced-tickets-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            min-width: 800px;
        }

        .enhanced-tickets-table thead th {
            background: var(--ultra-light-green);
            color: var(--primary-green);
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.875rem 1rem;
            border-bottom: 2px solid var(--light-green);
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .ticket-row {
            transition: all 0.2s ease;
            border-bottom: 1px solid #f3f4f6;
        }

        .ticket-row:hover {
            background: var(--ultra-light-green);
            transform: translateX(2px);
        }

        .enhanced-tickets-table tbody td {
            padding: 0.875rem 1rem;
            vertical-align: middle;
            font-size: 0.8rem;
        }

        .ticket-id-badge {
            font-family: 'Monaco', 'Menlo', monospace;
            background: linear-gradient(135deg, var(--light-green) 0%, #a7f3d0 100%);
            color: var(--primary-green-dark);
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            border: 1px solid var(--secondary-green);
        }

        .ticket-subject {
            font-weight: 500;
            color: var(--dark-text);
            max-width: 250px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .customer-info {
            color: var(--medium-text);
            font-weight: 500;
            display: flex;
            align-items: center;
        }

        .customer-info i {
            color: var(--primary-green);
        }

        .priority-badge, .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .priority-low {
            background: var(--light-green);
            color: var(--primary-green);
            border: 1px solid var(--accent-green);
        }

        .status-pending {
            background: #fef3c7;
            color: #d97706;
            border: 1px solid #fde68a;
        }

        .technician-info {
            color: var(--medium-text);
            font-weight: 500;
            display: flex;
            align-items: center;
        }

        .technician-info i {
            color: var(--primary-green);
        }

        .unassigned-badge {
            color: #dc2626;
            background: #fee2e2;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.7rem;
            font-weight: 600;
            display: flex;
            align-items: center;
        }

        .update-time {
            color: var(--light-text);
            font-size: 0.75rem;
            display: flex;
            align-items: center;
        }

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
            border-radius: 6px;
            transition: all 0.2s ease;
            text-decoration: none;
            border: none;
            cursor: pointer;
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

        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
        }

        .empty-content i {
            font-size: 3rem;
            color: var(--light-text);
            margin-bottom: 1rem;
        }

        .empty-content h4 {
            color: var(--primary-green);
            margin-bottom: 0.5rem;
        }

        .empty-content p {
            color: var(--light-text);
            margin: 0;
        }

        .pagination-wrapper {
            padding: 1.25rem 1.5rem;
            background: var(--ultra-light-green);
            border-top: 1px solid var(--border-color);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
                padding: 1rem;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .form-actions {
                flex-direction: column;
            }

            .table-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
                padding: 1rem;
            }

            .enhanced-tickets-table th,
            .enhanced-tickets-table td {
                padding: 0.75rem 0.5rem;
            }

            .ticket-subject {
                max-width: 150px;
            }
        }

        @media (max-width: 480px) {
            .page-header {
                padding: 0.75rem;
            }

            .filter-body {
                padding: 1rem;
            }

            .form-row {
                gap: 0.75rem;
            }

            .enhanced-tickets-table th,
            .enhanced-tickets-table td {
                padding: 0.5rem 0.25rem;
                font-size: 0.75rem;
            }

            .ticket-subject {
                max-width: 120px;
            }
        }
    </style>
</head>
{{-- Success Messages --}}
@if (session('success'))
    <div class="success-card mb-4">
        <div class="success-content">
            <div class="success-icon">
                <i class="fa fa-check-circle"></i>
            </div>
            <div class="success-text">
                <h5 class="success-title">Success!</h5>
                <p class="success-message">{{ session('success') }}</p>
            </div>
        </div>
    </div>
@endif

<body>
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container-fluid">
            <div class="d-flex align-items-center">
                <a class="navbar-brand" href="{{ route('admin.index') }}">Faults Allocation</a>
                <button class="btn" id="sidebarToggle" aria-label="Toggle sidebar">
                    <i class="fas fa-bars"></i>
                </button>
            </div>

            <ul class="navbar-nav ms-auto align-items-center flex-row gap-3">
                
               <!-- Add this to your navigation/header section -->
<div class="notification-wrapper">
    <div class="notification-dropdown position-relative">
        <button class="btn btn-link notification-trigger p-2" id="notificationTrigger" type="button">
            <i class="fas fa-bell fs-5"></i>
            <span class="notification-badge position-absolute translate-middle badge rounded-pill bg-danger" id="notificationBadge" style="display: none;">
                0
            </span>
        </button>
        
        <div class="notification-dropdown-menu position-absolute end-0 mt-2 shadow-lg bg-white rounded-3 border" id="notificationDropdown" style="display: none; width: 400px; max-height: 500px; z-index: 1050;">
            <div class="notification-header d-flex justify-content-between align-items-center p-3 border-bottom">
                <h6 class="mb-0 fw-bold">Recent Notifications</h6>
                <div class="notification-actions">
                    <button class="btn btn-sm btn-outline-primary me-2" id="markAllReadBtn">
                        <i class="fas fa-check-double me-1"></i>
                        Mark All Read
                    </button>
                    <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-list me-1"></i>
                        View All
                    </a>
                </div>
            </div>
            
            <div class="notification-list" id="notificationList" style="max-height: 400px; overflow-y: auto;">
                <div class="text-center p-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Notification Styles -->
<style>
.notification-trigger {
    border: none !important;
    color: #6c757d;
    position: relative;
    transition: color 0.3s ease;
}

.notification-trigger:hover {
    color: #495057;
}

.notification-badge {
    top: 8px;
    left: 20px;
    font-size: 0.75rem;
    min-width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.notification-dropdown-menu {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    border: 1px solid rgba(0, 0, 0, 0.1);
}

.notification-item {
    padding: 1rem;
    border-bottom: 1px solid #f8f9fa;
    cursor: pointer;
    transition: background-color 0.2s ease;
    display: flex;
    align-items: start;
    gap: 1rem;
}

.notification-item:hover {
    background-color: #f8f9fa;
}

.notification-item:last-child {
    border-bottom: none;
}

.notification-item.unread {
    background-color: #f8f9ff;
    border-left: 3px solid #0d6efd;
}

.notification-item.high {
    border-left: 3px solid #dc3545;
}

.notification-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    flex-shrink: 0;
}

.notification-content {
    flex: 1;
    min-width: 0;
}

.notification-title {
    font-weight: 600;
    color: #212529;
    font-size: 0.9rem;
    margin-bottom: 0.25rem;
}

.notification-message {
    color: #6c757d;
    font-size: 0.85rem;
    line-height: 1.4;
    margin-bottom: 0.5rem;
}

.notification-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.75rem;
    color: #6c757d;
}

.notification-time {
    font-size: 0.75rem;
    color: #6c757d;
}

.no-notifications {
    text-align: center;
    padding: 2rem;
    color: #6c757d;
}

.no-notifications i {
    font-size: 2rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

@media (max-width: 576px) {
    .notification-dropdown-menu {
        width: 320px !important;
        right: -100px !important;
    }
    
    .notification-actions {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .notification-actions .btn {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
}
</style>

<!-- Notification JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const trigger = document.getElementById('notificationTrigger');
    const dropdown = document.getElementById('notificationDropdown');
    const badge = document.getElementById('notificationBadge');
    const list = document.getElementById('notificationList');
    const markAllReadBtn = document.getElementById('markAllReadBtn');
    
    let isDropdownOpen = false;
    
    // Load initial data
    loadNotificationCount();
    loadRecentNotifications();
    
    // Refresh every 30 seconds
    setInterval(() => {
        loadNotificationCount();
        if (isDropdownOpen) {
            loadRecentNotifications();
        }
    }, 30000);
    
    // Toggle dropdown
    trigger.addEventListener('click', function(e) {
        e.stopPropagation();
        isDropdownOpen = !isDropdownOpen;
        dropdown.style.display = isDropdownOpen ? 'block' : 'none';
        
        if (isDropdownOpen) {
            loadRecentNotifications();
        }
    });
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!trigger.contains(e.target) && !dropdown.contains(e.target)) {
            isDropdownOpen = false;
            dropdown.style.display = 'none';
        }
    });
    
    // Mark all as read
    markAllReadBtn.addEventListener('click', function(e) {
        e.preventDefault();
        markAllAsRead();
    });
    
    function loadNotificationCount() {
        fetch('{{ route("notifications.api.unread-count") }}')
            .then(response => response.json())
            .then(data => {
                badge.textContent = data.count;
                badge.style.display = data.count > 0 ? 'flex' : 'none';
            })
            .catch(error => console.error('Error loading notification count:', error));
    }
    
    function loadRecentNotifications() {
        list.innerHTML = '<div class="text-center p-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';
        
        fetch('{{ route("notifications.api.recent") }}')
            .then(response => response.json())
            .then(notifications => {
                list.innerHTML = '';
                
                if (notifications.length === 0) {
                    list.innerHTML = `
                        <div class="no-notifications">
                            <i class="fas fa-bell-slash"></i>
                            <div>No new notifications</div>
                        </div>
                    `;
                    return;
                }
                
                notifications.forEach(notification => {
                    const item = document.createElement('div');
                    item.className = `notification-item ${notification.read_at ? 'read' : 'unread'} ${notification.priority || ''}`;
                    
                    item.innerHTML = `
                        <div class="notification-icon">
                            ${getNotificationIcon(notification.type)}
                        </div>
                        <div class="notification-content">
                            <div class="notification-title">${notification.title}</div>
                            <div class="notification-message">${notification.message}</div>
                            <div class="notification-meta">
                                <span class="notification-time">${notification.created_at}</span>
                                ${notification.job_card ? `<span class="badge bg-secondary">${notification.job_card}</span>` : ''}
                            </div>
                        </div>
                    `;
                    
                    item.addEventListener('click', (e) => {
                        e.preventDefault();
                        window.location.href = notification.url;
                    });
                    
                    list.appendChild(item);
                });
            })
            .catch(error => {
                console.error('Error loading notifications:', error);
                list.innerHTML = '<div class="text-center p-4 text-danger">Error loading notifications</div>';
            });
    }
    
    function markAllAsRead() {
        fetch('{{ route("notifications.read-all") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadNotificationCount();
                loadRecentNotifications();
                showToast('All notifications marked as read', 'success');
            }
        })
        .catch(error => {
            console.error('Error marking notifications as read:', error);
            showToast('Error marking notifications as read', 'error');
        });
    }
    
    function getNotificationIcon(type) {
        const icons = {
            'job_assigned': '<i class="fas fa-user-plus text-primary"></i>',
            'job_status_updated': '<i class="fas fa-sync-alt text-info"></i>',
            'job_completed': '<i class="fas fa-check-circle text-success"></i>',
            'ticket_created': '<i class="fas fa-ticket-alt text-warning"></i>',
            'ticket_updated': '<i class="fas fa-edit text-info"></i>'
        };
        return icons[type] || '<i class="fas fa-bell text-secondary"></i>';
    }
    
    function showToast(message, type = 'info') {
        // Simple toast notification
        const toast = document.createElement('div');
        toast.className = `alert alert-${type === 'error' ? 'danger' : type === 'success' ? 'success' : 'info'} position-fixed`;
        toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        toast.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-triangle' : 'info-circle'} me-2"></i>
                <span>${message}</span>
                <button type="button" class="btn-close ms-auto" onclick="this.parentElement.parentElement.remove()"></button>
            </div>
        `;
        
        document.body.appendChild(toast);
        
        setTimeout(() => {
            if (toast.parentNode) {
                toast.remove();
            }
        }, 5000);
    }
});
</script>



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
                <a class="nav-link {{ request()->routeIs('admin.index') ? 'active' : '' }}" href="{{ route('admin.index') }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            
            <li class="nav-header">Faults</li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.tickets.open') ? 'active' : '' }}" href="{{ route('admin.tickets.open') }}">
                    <i class="fas fa-play-circle"></i>
                    <span>In Progress</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.tickets.solved') ? 'active' : '' }}" href="{{ route('admin.tickets.solved') }}">
                    <i class="fas fa-check-circle"></i>
                    <span>Resolved</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.tickets.pending') ? 'active' : '' }}" href="{{ route('admin.tickets.pending') }}">
                    <i class="fas fa-clock"></i>
                    <span>Pending</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.tickets.unassigned') ? 'active' : '' }}" href="{{ route('admin.tickets.unassigned') }}">
                    <i class="fas fa-user-slash"></i>
                    <span>Unassigned</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('tickets.mine') ? 'active' : '' }}" href="{{ route('admin.tickets.mine') }}">
                    <i class="fas fa-user-check"></i>
                    <span>My Faults</span>
                </a>
            </li>
            
            <li class="nav-header">Administration</li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.users.index') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                    <i class="fas fa-users-cog"></i>
                    <span>Users</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.reports') ? 'active' : '' }}" href="">
                    <i class="fas fa-chart-line"></i>
                    <span>Reports</span>
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

        @yield('ticket-content')
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

            // Notification Handling
            if (notificationsDropdown) {
                notificationsDropdown.addEventListener('shown.bs.dropdown', function() {
                    if (typeof window.route === 'function' && window.route('notifications.read-all')) {
                        fetch(route('notifications.read-all'), {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => {
                            if (!response.ok) throw new Error('Network response was not ok');
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                const unreadBadge = notificationsDropdown.querySelector('.badge');
                                if (unreadBadge) {
                                    unreadBadge.remove();
                                }
                                
                                // Update all notification items to appear as read
                                document.querySelectorAll('.dropdown-notifications .list-group-item-primary')
                                    .forEach(item => item.classList.remove('list-group-item-primary'));
                            }
                        })
                        .catch(error => console.error('Error:', error));
                    }
                });
            }
        });
    </script>
</body>
</html>