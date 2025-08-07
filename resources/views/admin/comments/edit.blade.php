@extends('layouts.app') {{-- Extended main app layout for consistent header/footer/navigation --}}

@section('title', 'Edit Comment')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-8">
            <div class="card shadow-sm border-0 rounded">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-comment-edit me-2"></i>
                        Edit Comment
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.comments.update', $comment) }}" novalidate>
                        @csrf
                        @method('PUT')

                        {{-- Name --}}
                        <div class="mb-4">
                            <label for="name" class="form-label fw-semibold">
                                Name <span class="text-danger" aria-label="required">*</span>
                            </label>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   value="{{ old('name', $comment->name) }}"
                                   class="form-control @error('name') is-invalid @enderror"
                                   required
                                   autofocus
                                   aria-describedby="nameHelp" />
                            <div id="nameHelp" class="form-text">
                                Please enter the commenter's name.
                            </div>
                            @error('name')
                                <div class="invalid-feedback" role="alert">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold">
                                Email <span class="text-danger" aria-label="required">*</span>
                            </label>
                            <input type="email"
                                   id="email"
                                   name="email"
                                   value="{{ old('email', $comment->email) }}"
                                   class="form-control @error('email') is-invalid @enderror"
                                   required
                                   aria-describedby="emailHelp" />
                            <div id="emailHelp" class="form-text">
                                A valid email address for contact.
                            </div>
                            @error('email')
                                <div class="invalid-feedback" role="alert">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Comment Content --}}
                        <div class="mb-4">
                            <label for="content" class="form-label fw-semibold">
                                Comment <span class="text-danger" aria-label="required">*</span>
                            </label>
                            <textarea id="content"
                                      name="content"
                                      rows="6"
                                      class="form-control @error('content') is-invalid @enderror"
                                      required
                                      aria-describedby="contentHelp"
                                      placeholder="Edit your comment here...">{{ old('content', $comment->content) }}</textarea>
                            <div id="contentHelp" class="form-text">
                                Your comment content (minimum 10 characters recommended).
                            </div>
                            @error('content')
                                <div class="invalid-feedback" role="alert">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Form Actions --}}
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('admin.comments.index') }}" class="btn btn-outline-secondary" aria-label="Cancel editing and go back to comments list">
                                <i class="fas fa-times me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary" aria-label="Submit comment updates">
                                <i class="fas fa-save me-1"></i> Update Comment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Additional custom styles for better UX */
    .form-label {
        font-size: 1rem;
    }
    textarea.form-control {
        font-size: 0.95rem;
        resize: vertical;
        min-height: 150px;
    }
    button.btn-primary {
        min-width: 150px;
    }
    a.btn-outline-secondary {
        min-width: 100px;
    }
</style>
@endpush
