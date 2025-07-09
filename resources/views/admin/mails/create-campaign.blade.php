@extends('layouts.contents')

@section('content')

<div class="container-fluid">
    <div class="page-header-card mb-4">
        <div class="page-header-content">
            <h3 class="page-title">
                <i class="fa fa-bullhorn me-2"></i>
                {{ isset($campaign) ? 'Edit Newsletter Campaign' : 'Create Newsletter Campaign' }}
            </h3>
        </div>
    </div>
    <div class="content-card">
        <div class="content-card-header">
            <h5 class="card-title">
                <i class="fa fa-edit me-2"></i>
                {{ isset($campaign) ? 'Edit' : 'Create' }} Campaign
            </h5>
        </div>
        <div class="content-card-body">
            <form action="{{ isset($campaign) 
                ? route('admin.newsletters.update', $campaign) 
                : route('admin.newsletters.store') }}" method="POST">
                @csrf
                @if(isset($campaign)) @method('PUT') @endif

                <div class="form-group">
                    <label for="subject" class="form-label">Subject</label>
                    <input type="text" name="subject" id="subject" class="form-control form-control-enhanced"
                        value="{{ old('subject', $campaign->subject ?? '') }}" required>
                </div>
                <div class="form-group">
                    <label for="content" class="form-label">Content</label>
                    <textarea name="content" id="content" class="form-control form-control-enhanced" rows="10" required>{{ old('content', $campaign->content ?? '') }}</textarea>
                </div>
                <div class="form-actions d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary btn-enhanced">
                        <i class="fa fa-save me-2"></i> {{ isset($campaign) ? 'Update Campaign' : 'Save Campaign' }}
                    </button>
                    <a href="{{ route('admin.newsletters.index') }}" class="btn btn-outline-secondary btn-enhanced">
                        <i class="fa fa-arrow-left me-2"></i>Back
                    </a>
                </div>
            </form>
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

        /* FAQ Categories Container */
        .faq-categories-container {
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
        }

        .page-title {
            font-size: 1.5rem;
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

        /* Table Content Styling */
        .row-number {
            font-weight: 600;
            color: var(--primary-green);
            background: var(--light-green);
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.875rem;
        }

        .category-name {
            font-weight: 500;
            color: var(--dark-text);
        }

        .category-slug {
            font-family: monospace;
            color: var(--light-text);
            font-size: 0.875rem;
        }

        .order-badge {
            background: var(--light-green);
            color: var(--primary-green-dark);
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-weight: 600;
            font-size: 0.875rem;
            border: 1px solid var(--secondary-green);
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

        .status-active {
            background: var(--ultra-light-green);
            color: var(--primary-green);
            border: 1px solid var(--secondary-green);
        }

        .status-inactive {
            background: #FEF2F2;
            color: #DC2626;
            border: 1px solid #FECACA;
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

        .delete-btn {
            background: #FEF2F2;
            color: #DC2626;
        }

        .delete-btn:hover {
            background: #DC2626;
            color: var(--white);
            transform: translateY(-1px);
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

        .btn-secondary.btn-enhanced {
            background: var(--hover-bg);
            color: var(--medium-text);
            border: 2px solid var(--border-color);
        }

        .btn-secondary.btn-enhanced:hover {
            background: var(--medium-text);
            color: var(--white);
        }

        .btn-danger.btn-enhanced {
            background: #DC2626;
            color: var(--white);
        }

        .btn-danger.btn-enhanced:hover {
            background: #B91C1C;
            transform: translateY(-1px);
        }

        /* Custom Modal Styling */
        .custom-modal {
            border-radius: 16px;
            border: none;
            box-shadow: var(--shadow-hover);
        }

        .custom-modal-header {
            background: linear-gradient(135deg, var(--ultra-light-green) 0%, var(--light-green) 100%);
            border-bottom: 1px solid var(--border-color);
            padding: 1.5rem 2rem;
        }

        .custom-modal-header .modal-title {
            color: var(--primary-green-dark);
            font-weight: 600;
            display: flex;
            align-items: center;
        }

        .custom-modal-body {
            padding: 2rem;
        }

        .custom-modal-footer {
            background: var(--ultra-light-green);
            border-top: 1px solid var(--border-color);
            padding: 1rem 2rem;
        }

        /* Form Elements */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: var(--dark-text);
            margin-bottom: 0.5rem;
            display: block;
            font-size: 0.9rem;
        }

        .form-control-enhanced {
            border: 2px solid var(--border-color);
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: var(--white);
        }

        .form-control-enhanced:focus {
            border-color: var(--primary-green);
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
            outline: none;
        }

        /* Alert Styling */
        .alert {
            border-radius: 8px;
            border: none;
            padding: 1rem;
        }

        .alert-danger {
            background: #FEF2F2;
            color: #B91C1C;
            border: 1px solid #FECACA;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .page-header-content {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .content-card-header {
                padding: 1rem;
            }

            .enhanced-table th,
            .enhanced-table td {
                padding: 0.75rem 1rem;
            }

            .action-buttons {
                flex-direction: column;
                gap: 0.25rem;
            }

            .custom-modal-header,
            .custom-modal-body,
            .custom-modal-footer {
                padding: 1rem;
            }
        }
    </style>
@endsection
