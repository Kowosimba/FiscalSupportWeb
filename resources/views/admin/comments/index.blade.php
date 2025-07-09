@extends('layouts.contents')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Manage Comments</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Blog Post</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Comment</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($comments as $comment)
                                    <tr>
                                        <td>{{ $comment->id }}</td>
                                        <td>
                                            <a href="{{ route('blog.show', $comment->blog->slug) }}" target="_blank">
                                                {{ Str::limit($comment->blog->title, 30) }}
                                            </a>
                                        </td>
                                        <td>{{ $comment->name }}</td>
                                        <td>{{ $comment->email }}</td>
                                        <td>{{ Str::limit($comment->content, 50) }}</td>
                                        <td>{{ $comment->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <a href="{{ route('admin.comments.edit', $comment) }}" 
                                               class="btn btn-sm btn-primary">Edit</a>
                                            
                                            <form method="POST" 
                                                  action="{{ route('admin.comments.destroy', $comment) }}" 
                                                  style="display: inline-block;"
                                                  onsubmit="return confirm('Are you sure you want to delete this comment?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No comments found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $comments->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
