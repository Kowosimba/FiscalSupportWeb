@extends('layouts.contents')

@section('content')
   

    <div class="faq-categories-container">
        {{-- Page Header --}}
        <div class="page-header-card mb-4">
            <div class="page-header-content">
                <div class="header-text">
                    <h3 class="page-title">
                        <i class="fa fa-question-circle me-2"></i>
                        FAQ Categories Management
                    </h3>
                    <p class="page-subtitle">
                        Organize your frequently asked questions into categories
                    </p>
                </div>
                <a href="{{ route('faq-categories.create') }}" class="btn btn-primary btn-enhanced">
                    <i class="fa fa-plus me-2"></i>
                    Add New Category
                </a>
            </div>
        </div>

        {{-- Categories Table --}}
        <div class="content-card">
            <div class="content-card-header">
                <h5 class="card-title">
                    <i class="fa fa-list me-2"></i>
                    FAQ Categories
                </h5>
            </div>
            
            <div class="content-card-body">
                <div class="table-responsive">
                    <table class="enhanced-table" id="categoriesTable">
                        <thead>
                            <tr>
                                <th width="5%">
                                    <i class="fa fa-hashtag me-1"></i>#
                                </th>
                                <th width="35%">
                                    <i class="fa fa-tag me-1"></i>Name
                                </th>
                                <th width="20%">
                                    <i class="fa fa-link me-1"></i>Slug
                                </th>
                                <th width="15%">
                                    <i class="fa fa-sort-numeric-asc me-1"></i>Order
                                </th>
                                <th width="15%">
                                    <i class="fa fa-toggle-on me-1"></i>Status
                                </th>
                                <th width="20%">
                                    <i class="fa fa-cog me-1"></i>Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $index => $category)
                            <tr>
                                <td>
                                    <span class="row-number">{{ $index + 1 }}</span>
                                </td>
                                <td>
                                    <div class="category-name">{{ $category->name }}</div>
                                </td>
                                <td>
                                    <div class="category-slug">{{ $category->slug }}</div>
                                </td>
                                <td>
                                    <span class="order-badge">{{ $category->order }}</span>
                                </td>
                                <td>
                                    <span class="status-badge status-{{ $category->is_active ? 'active' : 'inactive' }}">
                                        <i class="fa fa-{{ $category->is_active ? 'check-circle' : 'times-circle' }} me-1"></i>
                                        {{ $category->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('faq-categories.edit', $category->id) }}" 
                                           class="action-btn edit-btn"
                                           title="Edit Category">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <button class="action-btn delete-btn delete-category" 
                                                data-id="{{ $category->id }}"
                                                title="Delete Category">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content custom-modal">
                <div class="modal-header custom-modal-header">
                    <h5 class="modal-title">
                        <i class="fa fa-exclamation-triangle me-2 text-danger"></i>
                        Confirm Deletion
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body custom-modal-body">
                    <p class="mb-3">Are you sure you want to delete this category? This action cannot be undone.</p>
                    <div class="alert alert-danger">
                        <i class="fa fa-warning me-2"></i>
                        <strong>Warning:</strong> Any FAQs in this category will also be removed.
                    </div>
                </div>
                <div class="modal-footer custom-modal-footer">
                    <button type="button" class="btn btn-secondary btn-enhanced" data-bs-dismiss="modal">
                        <i class="fa fa-times me-2"></i>Cancel
                    </button>
                    <form id="deleteForm" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-enhanced">
                            <i class="fa fa-trash me-2"></i>Delete Category
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Create/Edit Category Modal -->
    <div class="modal fade" id="categoryModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content custom-modal">
                <div class="modal-header custom-modal-header">
                    <h5 class="modal-title" id="modalTitle">
                        <i class="fa fa-plus me-2"></i>Add New Category
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="categoryForm" method="POST">
                    @csrf
                    <div id="formMethod"></div>
                    <div class="modal-body custom-modal-body">
                        <div class="form-group">
                            <label for="name" class="form-label">
                                <i class="fa fa-tag me-2"></i>Category Name
                            </label>
                            <input type="text" class="form-control form-control-enhanced" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="order" class="form-label">
                                <i class="fa fa-sort-numeric-asc me-2"></i>Display Order
                            </label>
                            <input type="number" class="form-control form-control-enhanced" id="order" name="order" value="0">
                        </div>
                        <div class="form-group">
                            <label for="is_active" class="form-label">
                                <i class="fa fa-toggle-on me-2"></i>Status
                            </label>
                            <select class="form-control form-control-enhanced" id="is_active" name="is_active">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer custom-modal-footer">
                        <button type="button" class="btn btn-secondary btn-enhanced" data-bs-dismiss="modal">
                            <i class="fa fa-times me-2"></i>Close
                        </button>
                        <button type="submit" class="btn btn-primary btn-enhanced">
                            <i class="fa fa-save me-2"></i>Save Changes
                        </button>
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

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#categoriesTable').DataTable({
            responsive: true,
            order: [[3, 'asc']] // Default sort by order column
        });

        // Handle delete button click
        $('.delete-category').click(function() {
            var categoryId = $(this).data('id');
            $('#deleteForm').attr('action', '/admin/faq-categories/' + categoryId);
            $('#deleteModal').modal('show');
        });

        // Handle create button click
        $('a[href="{{ route('faq-categories.create') }}"]').click(function(e) {
            e.preventDefault();
            $('#modalTitle').html('<i class="fa fa-plus me-2"></i>Add New Category');
            $('#categoryForm').attr('action', '{{ route('faq-categories.store') }}');
            $('#formMethod').html('');
            $('#name').val('');
            $('#order').val('0');
            $('#is_active').val('1');
            $('#categoryModal').modal('show');
        });

        // Handle edit button click
        $('a.edit-btn').click(function(e) {
            e.preventDefault();
            var editUrl = $(this).attr('href');
            
            // Fetch category data via AJAX
            $.get(editUrl, function(data) {
                $('#modalTitle').html('<i class="fa fa-edit me-2"></i>Edit Category');
                $('#categoryForm').attr('action', '/admin/faq-categories/' + data.id);
                $('#formMethod').html('@method("PUT")');
                $('#name').val(data.name);
                $('#order').val(data.order);
                $('#is_active').val(data.is_active ? '1' : '0');
                $('#categoryModal').modal('show');
            });
        });
    });
</script>
@endsection
