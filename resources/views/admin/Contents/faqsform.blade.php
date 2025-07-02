@extends('layouts.contents')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="mb-0">Create New FAQ</h3>
                <a href="{{ route('admin.faqs.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to FAQs
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.faqs.store') }}" method="POST">
                        @csrf
                        
                        <div class="form-group">
                            <label for="question">Question</label>
                            <input type="text" class="form-control" id="question" name="question" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="answer">Answer</label>
                            <textarea class="form-control" id="answer" name="answer" rows="5" required></textarea>
                        </div>
                        
                        <div class="form-group">
        <label for="faq_category_id">Category</label>
        <select name="faq_category_id" id="faq_category_id" class="form-control" required>
            <option value="">Select a category</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
    </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category_input">Category</label>
                                    <input type="text" class="form-control" id="category_input" name="category" 
                                           list="category_suggestions" autocomplete="off" required>
                                    <datalist id="category_suggestions">
                                        @foreach($categories as $category)
                                            <option value="{{ $category->name }}">{{ $category->name }}</option>
                                        @endforeach
                                    </datalist>
                                    <input type="hidden" id="category_id" name="category_id">
                                    <small class="text-muted">Type to select existing category or enter a new one</small>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="order">Display Order</label>
                                    <input type="number" class="form-control" id="order" name="order" value="0">
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="is_active">Status</label>
                                    <select class="form-control" id="is_active" name="is_active">
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save FAQ
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

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

    // Category input handling
    const categoryInput = $('#category_input');
    const categoryIdInput = $('#category_id');
    const categorySuggestions = {!! json_encode($categories->pluck('name', 'id')) !!};

    // When category is selected from datalist or new one entered
    categoryInput.on('change input', function() {
        const inputValue = $(this).val().trim();
        let foundId = null;
        
        // Find if the entered value matches any existing category
        $.each(categorySuggestions, function(id, name) {
            if (name.toLowerCase() === inputValue.toLowerCase()) {
                foundId = id;
                return false; // break the loop
            }
        });
        
        // Update the hidden category_id field
        categoryIdInput.val(foundId || '');
    });
});
</script>
@endsection