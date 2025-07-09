@extends('layouts.contents')

@section('content')
<div class="faq-create-container">
    {{-- Page Header --}}
    <div class="page-header-card mb-4">
        <div class="page-header-content">
            <div class="header-text">
                <h2 class="page-title">
                    <i class="fa fa-{{ isset($faq) ? 'edit' : 'plus' }} me-2"></i>
                    {{ isset($faq) ? 'Edit FAQ' : 'Create New FAQ' }}
                </h2>
                <p class="page-subtitle">
                    {{ isset($faq) ? 'Update FAQ information and content' : 'Add a new frequently asked question' }}
                </p>
            </div>
            <a href="{{ route('admin.faqs.index') }}" class="btn btn-outline-primary">
                <i class="fa fa-arrow-left me-2"></i>
                Back to FAQs
            </a>
        </div>
    </div>

    {{-- Success Messages --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Error Messages --}}
    @if ($errors->any())
        <div class="error-card mb-4">
            <div class="error-content">
                <div class="error-icon">
                    <i class="fa fa-exclamation-triangle"></i>
                </div>
                <div class="error-text">
                    <h5 class="error-title">Please fix the following errors:</h5>
                    <ul class="error-list">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ isset($faq) ? route('admin.faqs.update', $faq->id) : route('admin.faqs.store') }}" 
          method="POST">
        @csrf
        @if(isset($faq)) @method('PUT') @endif

        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <div class="content-card mb-4">
                    <div class="content-card-header">
                        <h5 class="card-title">
                            <i class="fa fa-question-circle me-2"></i>
                            FAQ Content
                        </h5>
                    </div>
                    <div class="content-card-body">
                        <!-- Question -->
                        <div class="form-group">
                            <label for="question" class="form-label">
                                Question <span class="required">*</span>
                            </label>
                            <input type="text" 
                                   name="question" 
                                   id="question" 
                                   value="{{ old('question', $faq->question ?? '') }}" 
                                   required 
                                   class="form-control form-control-enhanced"
                                   placeholder="Enter the frequently asked question">
                        </div>

                        <!-- Answer -->
                        <div class="form-group">
                            <label for="answer" class="form-label">
                                Answer <span class="required">*</span>
                            </label>
                            <textarea name="answer" 
                                      id="answer" 
                                      rows="8" 
                                      required
                                      class="form-control form-control-enhanced"
                                      placeholder="Provide a detailed answer to the question">{{ old('answer', $faq->answer ?? '') }}</textarea>
                            <small class="form-help">You can use HTML tags for formatting</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Category Settings -->
                <div class="content-card mb-4">
                    <div class="content-card-header">
                        <h5 class="card-title">
                            <i class="fa fa-tag me-2"></i>
                            Category Settings
                        </h5>
                    </div>
                    <div class="content-card-body">
                        {{-- Category Selection with Dynamic Creation --}}
                        <div class="form-group">
                            <label for="category_selection" class="form-label">
                                <i class="fa fa-tag me-2"></i>Category <span class="required">*</span>
                            </label>
                            
                            {{-- Existing Category Selection --}}
                            <div class="category-selection-wrapper">
                                <select name="faq_category_id" id="faq_category_id" class="form-control form-control-enhanced mb-2">
                                    <option value="">Select existing category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" 
                                                {{ old('faq_category_id', $faq->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                
                                <div class="text-center my-2">
                                    <span class="text-muted">OR</span>
                                </div>
                                
                                {{-- New Category Creation --}}
                                <div class="new-category-section">
                                    <div class="input-group">
                                        <input type="text" 
                                               class="form-control form-control-enhanced" 
                                               id="new_category_name" 
                                               name="new_category_name"
                                               placeholder="Enter new category name">
                                        <button type="button" 
                                                class="btn btn-outline-primary" 
                                                id="create_category_btn">
                                            <i class="fa fa-plus me-1"></i>Create
                                        </button>
                                    </div>
                                    <small class="form-help">Create a new category if it doesn't exist above</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Display Settings -->
                <div class="content-card mb-4">
                    <div class="content-card-header">
                        <h5 class="card-title">
                            <i class="fa fa-cog me-2"></i>
                            Display Settings
                        </h5>
                    </div>
                    <div class="content-card-body">
                        <!-- Status -->
                        <div class="form-group">
                            <label class="form-label">Status</label>
                            <div class="radio-group">
                                <div class="custom-radio">
                                    <input class="radio-input" 
                                           type="radio" 
                                           name="is_active" 
                                           id="statusActive" 
                                           value="1" 
                                           {{ old('is_active', $faq->is_active ?? '1') == '1' ? 'checked' : '' }}>
                                    <label class="radio-label" for="statusActive">
                                        <span class="radio-button"></span>
                                        <span class="radio-text">Active</span>
                                    </label>
                                </div>
                                <div class="custom-radio">
                                    <input class="radio-input" 
                                           type="radio" 
                                           name="is_active" 
                                           id="statusInactive" 
                                           value="0" 
                                           {{ old('is_active', $faq->is_active ?? '1') == '0' ? 'checked' : '' }}>
                                    <label class="radio-label" for="statusInactive">
                                        <span class="radio-button"></span>
                                        <span class="radio-text">Inactive</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Order -->
                        <div class="form-group">
                            <label for="order" class="form-label">
                                Display Order
                            </label>
                            <input type="number" 
                                   name="order" 
                                   id="order" 
                                   value="{{ old('order', $faq->order ?? '0') }}"
                                   min="0"
                                   class="form-control form-control-enhanced"
                                   placeholder="0">
                            <small class="form-help">Lower numbers appear first</small>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="content-card">
                    <div class="content-card-body">
                        <button type="submit" 
                                class="btn btn-primary btn-enhanced btn-block mb-3">
                            <i class="fa fa-save me-2"></i> 
                            {{ isset($faq) ? 'Update FAQ' : 'Create FAQ' }}
                        </button>
                        
                        @if(isset($faq))
                            <a href="{{ route('admin.faqs.index') }}" 
                               class="btn btn-outline-secondary btn-enhanced btn-block">
                                <i class="fa fa-times me-2"></i> 
                                Cancel
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
    /* FAQ Create Container */
    .faq-create-container {
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

    /* Error Card */
    .error-card {
        background: #FEF2F2;
        border: 1px solid #FECACA;
        border-radius: 12px;
        overflow: hidden;
    }

    .error-content {
        padding: 1.5rem;
        display: flex;
        gap: 1rem;
    }

    .error-icon {
        color: #DC2626;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .error-title {
        color: #DC2626;
        font-size: 1rem;
        font-weight: 600;
        margin: 0 0 0.5rem 0;
    }

    .error-list {
        margin: 0;
        padding-left: 1.25rem;
        color: #B91C1C;
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
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--primary-green);
        margin: 0;
        display: flex;
        align-items: center;
    }

    .content-card-body {
        padding: 2rem;
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

    .required {
        color: #DC2626;
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

    .form-help {
        color: var(--light-text);
        font-size: 0.8rem;
        margin-top: 0.25rem;
        display: block;
    }

    /* Category Selection */
    .category-selection-wrapper {
        border: 2px solid var(--border-color);
        border-radius: 12px;
        padding: 1rem;
        background: var(--ultra-light-green);
    }

    .new-category-section .input-group {
        border-radius: 8px;
        overflow: hidden;
    }

    .new-category-section .form-control {
        border-right: none;
    }

    .new-category-section .btn {
        border-left: none;
        white-space: nowrap;
    }

    /* Custom Radio Buttons */
    .radio-group {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .custom-radio {
        position: relative;
    }

    .radio-input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }

    .radio-label {
        display: flex;
        align-items: center;
        cursor: pointer;
        padding: 0.75rem;
        border-radius: 8px;
        transition: all 0.3s ease;
        border: 2px solid var(--border-color);
    }

    .radio-label:hover {
        background: var(--ultra-light-green);
        border-color: var(--light-green);
    }

    .radio-button {
        width: 20px;
        height: 20px;
        border: 2px solid var(--border-color);
        border-radius: 50%;
        margin-right: 0.75rem;
        position: relative;
        transition: all 0.3s ease;
    }

    .radio-input:checked + .radio-label .radio-button {
        border-color: var(--primary-green);
        background: var(--primary-green);
    }

    .radio-input:checked + .radio-label .radio-button::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--white);
    }

    .radio-input:checked + .radio-label {
        background: var(--ultra-light-green);
        border-color: var(--primary-green);
    }

    .radio-text {
        font-weight: 500;
        color: var(--dark-text);
    }

    /* Enhanced Buttons */
    .btn-enhanced {
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
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

    .btn-outline-primary {
        border: 2px solid var(--primary-green);
        color: var(--primary-green);
        background: transparent;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
        text-decoration: none;
        display: flex;
        align-items: center;
    }

    .btn-outline-primary:hover {
        background: var(--primary-green);
        color: var(--white);
        transform: translateY(-1px);
    }

    .btn-outline-secondary.btn-enhanced {
        border: 2px solid var(--border-color);
        color: var(--medium-text);
        background: transparent;
    }

    .btn-outline-secondary.btn-enhanced:hover {
        background: var(--light-text);
        color: var(--white);
        border-color: var(--light-text);
    }

    .btn-block {
        width: 100%;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .page-header-content {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .content-card-header,
        .content-card-body {
            padding: 1rem;
        }

        .page-header-content {
            padding: 1.5rem;
        }
    }
</style>

<script>
$(document).ready(function() {
    // Handle category selection logic
    $('#faq_category_id').on('change', function() {
        if ($(this).val()) {
            $('#new_category_name').val('').prop('disabled', true);
            $('#create_category_btn').prop('disabled', true);
        } else {
            $('#new_category_name').prop('disabled', false);
            $('#create_category_btn').prop('disabled', false);
        }
    });

    $('#new_category_name').on('input', function() {
        if ($(this).val().trim()) {
            $('#faq_category_id').val('').prop('disabled', true);
        } else {
            $('#faq_category_id').prop('disabled', false);
        }
    });

    // Create category via AJAX
    $('#create_category_btn').on('click', function() {
        const categoryName = $('#new_category_name').val().trim();
        
        if (!categoryName) {
            alert('Please enter a category name');
            return;
        }

        // Disable button during request
        $(this).prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i>Creating...');

        $.ajax({
            url: '{{ route("admin.faq-categories.store") }}',
            method: 'POST',
            data: {
                name: categoryName,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                // Add new option to select
                $('#faq_category_id').append(
                    `<option value="${response.id}" selected>${response.name}</option>`
                );
                
                // Clear and disable new category input
                $('#new_category_name').val('').prop('disabled', true);
                
                // Show success message
                showSuccessMessage('Category created successfully!');
            },
            error: function(xhr) {
                let errorMessage = 'Failed to create category';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMessage = Object.values(xhr.responseJSON.errors).flat().join(', ');
                }
                alert(errorMessage);
            },
            complete: function() {
                // Re-enable button
                $('#create_category_btn').prop('disabled', false).html('<i class="fa fa-plus me-1"></i>Create');
            }
        });
    });

    function showSuccessMessage(message) {
        // Create and show success alert
        const alert = $(`
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa fa-check-circle me-2"></i>${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `);
        
        $('.faq-create-container').prepend(alert);
        
        // Auto-hide after 3 seconds
        setTimeout(() => {
            alert.fadeOut();
        }, 3000);
    }

    // Initialize form state
    if ($('#faq_category_id').val()) {
        $('#new_category_name').prop('disabled', true);
        $('#create_category_btn').prop('disabled', true);
    }
});
</script>
@endsection
