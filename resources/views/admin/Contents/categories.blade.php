@extends('layouts.contents')

@section('content')
s
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="mb-0">FAQ Categories Management</h3>
                <a href="{{ route('faq-categories.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Category
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="categoriesTable">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="35%">Name</th>
                                    <th width="20%">Slug</th>
                                    <th width="15%">Order</th>
                                    <th width="15%">Status</th>
                                    <th width="20%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categories as $index => $category)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $category->name }}</td>
                                    <td>{{ $category->slug }}</td>
                                    <td>{{ $category->order }}</td>
                                    <td>
                                        <span class="badge badge-{{ $category->is_active ? 'success' : 'danger' }}">
                                            {{ $category->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('faq-categories.edit', $category->id) }}" 
                                           class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button class="btn btn-sm btn-danger delete-category" 
                                                data-id="{{ $category->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Deletion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this category? This action cannot be undone.</p>
                <p class="text-danger"><strong>Warning:</strong> Any FAQs in this category will also be removed.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Category</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Create/Edit Category Modal -->
<div class="modal fade" id="categoryModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Add New Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="categoryForm" method="POST">
                @csrf
                <div id="formMethod"></div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Category Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="order">Display Order</label>
                        <input type="number" class="form-control" id="order" name="order" value="0">
                    </div>
                    <div class="form-group">
                        <label for="is_active">Status</label>
                        <select class="form-control" id="is_active" name="is_active">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
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
            $('#modalTitle').text('Add New Category');
            $('#categoryForm').attr('action', '{{ route('faq-categories.store') }}');
            $('#formMethod').html('');
            $('#name').val('');
            $('#order').val('0');
            $('#is_active').val('1');
            $('#categoryModal').modal('show');
        });

        // Handle edit button click
        $('a.btn-primary').click(function(e) {
            if ($(this).hasClass('delete-category')) return;
            e.preventDefault();
            var editUrl = $(this).attr('href');
            
            // Fetch category data via AJAX
            $.get(editUrl, function(data) {
                $('#modalTitle').text('Edit Category');
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

<style>
    .badge-success {
        background-color: #28a745;
        color: white;
    }
    .badge-danger {
        background-color: #dc3545;
        color: white;
    }
    .table-responsive {
        overflow-x: auto;
    }
    .card {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        line-height: 1.5;
        border-radius: 0.2rem;
    }
</style>