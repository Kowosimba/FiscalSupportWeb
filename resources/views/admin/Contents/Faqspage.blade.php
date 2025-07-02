@extends('layouts.contents')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="mb-0">FAQ Management</h3>
                <a href="{{ route('admin.faqs.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New FAQ
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="faqsTable">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="25%">Question</th>
                                    <th width="40%">Answer</th>
                                    <th width="15%">Category</th>
                                    <th width="10%">Status</th>
                                    <th width="15%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($faqs as $index => $faq)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $faq->question }}</td>
                                    <td>{!! Str::limit(strip_tags($faq->answer), 100) !!}</td>
                                    <td>{{ $faq->category->name ?? 'Uncategorized' }}</td>
                                    <td>
                                        <span class="badge badge-{{ $faq->is_active ? 'success' : 'danger' }}">
                                            {{ $faq->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.faqs.edit', $faq->id) }}" 
                                           class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button class="btn btn-sm btn-danger delete-faq" 
                                                data-id="{{ $faq->id }}">
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
                <p>Are you sure you want to delete this FAQ? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete FAQ</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#faqsTable').DataTable({
            responsive: true
        });

        // Handle delete button click
        $('.delete-faq').click(function() {
            var faqId = $(this).data('id');
            $('#deleteForm').attr('action', '/admin/faqs/' + faqId);
            $('#deleteModal').modal('show');
        });
    });
</script>
@endsection