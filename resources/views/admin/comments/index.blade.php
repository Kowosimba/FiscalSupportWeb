@extends('layouts.contents')

@section('content')
<div class="container my-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm rounded">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Manage Comments</h4>
                    {{-- Optional: Add New Comment or Filter buttons here --}}
                </div>
                <div class="card-body p-3">

                    {{-- Success Message --}}
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" style="width: 60px;">ID</th>
                                    <th scope="col">Blog Post</th>
                                    <th scope="col" style="width: 150px;">Name</th>
                                    <th scope="col" style="width: 180px;">Email</th>
                                    <th scope="col">Comment</th>
                                    <th scope="col" style="width: 130px;">Date</th>
                                    <th scope="col" style="width: 130px;" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($comments as $comment)
                                    <tr title="Click to view the blog post">
                                        <td>{{ $comment->id }}</td>
                                        <td>
                                            <a href="{{ route('blog.show', $comment->blog->slug) }}" target="_blank" class="text-decoration-none">
                                                {{ Str::limit($comment->blog->title, 40) }}
                                            </a>
                                        </td>
                                        <td>{{ $comment->name }}</td>
                                        <td>
                                            <a href="mailto:{{ $comment->email }}" class="text-decoration-none">
                                                {{ Str::limit($comment->email, 30) }}
                                            </a>
                                        </td>
                                        <td>{{ Str::limit($comment->content, 60) }}</td>
                                        <td>{{ $comment->created_at->format('M d, Y') }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.comments.edit', $comment) }}" 
                                               class="btn btn-sm btn-primary me-1" 
                                               data-bs-toggle="tooltip" 
                                               data-bs-placement="top" 
                                               title="Edit Comment">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST" style="display:inline;" 
                                                  onsubmit="return confirm('Are you sure you want to delete this comment?');" 
                                                  class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-sm btn-danger" 
                                                        data-bs-toggle="tooltip" 
                                                        data-bs-placement="top" 
                                                        title="Delete Comment">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted p-4">
                                            No comments found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-3">
                        {{ $comments->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

{{-- Enable Bootstrap tooltips --}}
@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.forEach(tooltipTriggerEl => {
            new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });
</script>
@endpush

@push('styles')
<style>
    /* Optional: cursor pointer on rows linked to blog posts */
    tbody tr:hover {
        cursor: pointer;
        background-color: #f8f9fa !important;
    }
    /* Prevent highlight on buttons inside the row */
    tbody tr td a, 
    tbody tr td form button {
        cursor: default;
    }
</style>
@endpush
