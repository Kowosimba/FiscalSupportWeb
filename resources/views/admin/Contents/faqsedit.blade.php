@extends('layouts.contents')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="mb-0">Edit FAQ</h3>
                <a href="{{ route('admin.faqs.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to FAQs
                </a>
            </div>
        </div>
    </div>

    <div class="form-group">
    <label for="faq_category_id">Category</label>
    <select name="faq_category_id" id="faq_category_id" class="form-control" required>
        <option value="">Select a category</option>
        @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ $faq->category_id == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach
    </select>
</div>


    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.faqs.update', $faq->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="question">Question</label>
                            <input type="text" class="form-control" id="question" name="question" 
                                   value="{{ $faq->question }}" required>
                        </div>
                        <div class="form-group">
                            <label for="answer">Answer</label>
                            <textarea class="form-control" id="answer" name="answer" rows="5" required>
                                {{ $faq->answer }}
                            </textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category_id">Category</label>
                                    <select class="form-control" id="category_id" name="category_id" required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" 
                                                {{ $faq->category_id == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="order">Display Order</label>
                                    <input type="number" class="form-control" id="order" name="order" 
                                           value="{{ $faq->order }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="is_active">Status</label>
                                    <select class="form-control" id="is_active" name="is_active">
                                        <option value="1" {{ $faq->is_active ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ !$faq->is_active ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Update FAQ</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@foreach($categories as $category)
    <tr>
        <td>{{ $category->name }}</td>
        <td>
            <form action="{{ route('admin.faq-categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this category?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
            </form>
        </td>
    </tr>
@endforeach


@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize Summernote for rich text editing
        $('#answer').summernote({
            height: 200,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
    });
</script>
@endsection
@endsection