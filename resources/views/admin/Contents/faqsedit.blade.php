@extends('layouts.contents')

@section('content')


    <div class="faq-edit-container">
        {{-- Page Header --}}
        <div class="page-header-card mb-4">
            <div class="page-header-content">
                <div class="header-text">
                    <h3 class="page-title">
                        <i class="fa fa-edit me-2"></i>
                        Edit FAQ
                    </h3>
                    <p class="page-subtitle">
                        Update frequently asked question and answer
                    </p>
                </div>
                <a href="{{ route('admin.faqs.index') }}" class="btn btn-outline-primary">
                    <i class="fa fa-arrow-left me-2"></i>
                    Back to FAQs
                </a>
            </div>
        </div>

        {{-- Edit Form --}}
        <div class="content-card">
            <div class="content-card-header">
                <h5 class="card-title">
                    <i class="fa fa-question-circle me-2"></i>
                    FAQ Details
                </h5>
            </div>
            
            <div class="content-card-body">
                <form action="{{ route('admin.faqs.update', $faq->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    {{-- Question Field --}}
                    <div class="form-group">
                        <label for="question" class="form-label">
                            <i class="fa fa-question me-2"></i>Question <span class="required">*</span>
                        </label>
                        <input type="text" 
                               class="form-control form-control-enhanced" 
                               id="question" 
                               name="question" 
                               value="{{ $faq->question }}" 
                               required
                               placeholder="Enter the frequently asked question">
                    </div>

                    {{-- Answer Field --}}
                    <div class="form-group">
                        <label for="answer" class="form-label">
                            <i class="fa fa-comment me-2"></i>Answer <span class="required">*</span>
                        </label>
                        <textarea class="form-control form-control-enhanced content-editor" 
                                  id="answer" 
                                  name="answer" 
                                  rows="8" 
                                  required
                                  placeholder="Provide a detailed answer to the question">{{ $faq->answer }}</textarea>
                        <small class="form-help">You can use rich text formatting for better presentation</small>
                    </div>

                    {{-- Form Row for Category, Order, and Status --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="category_id" class="form-label">
                                    <i class="fa fa-tag me-2"></i>Category <span class="required">*</span>
                                </label>
                                <select class="form-control form-control-enhanced" id="category_id" name="category_id" required>
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
                                <label for="order" class="form-label">
                                    <i class="fa fa-sort-numeric-asc me-2"></i>Display Order
                                </label>
                                <input type="number" 
                                       class="form-control form-control-enhanced" 
                                       id="order" 
                                       name="order" 
                                       value="{{ $faq->order }}"
                                       placeholder="0">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="is_active" class="form-label">
                                    <i class="fa fa-toggle-on me-2"></i>Status
                                </label>
                                <select class="form-control form-control-enhanced" id="is_active" name="is_active">
                                    <option value="1" {{ $faq->is_active ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ !$faq->is_active ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary btn-enhanced">
                            <i class="fa fa-save me-2"></i>
                            Update FAQ
                        </button>
                        <a href="{{ route('admin.faqs.index') }}" class="btn btn-secondary btn-enhanced">
                            <i class="fa fa-times me-2"></i>
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Categories Management Section --}}
        @if(count($categories) > 0)
        <div class="content-card mt-4">
            <div class="content-card-header">
                <h5 class="card-title">
                    <i class="fa fa-list me-2"></i>
                    Available Categories
                </h5>
            </div>
            
            <div class="content-card-body">
                <div class="table-responsive">
                    <table class="enhanced-table">
                        <thead>
                            <tr>
                                <th>
                                    <i class="fa fa-tag me-1"></i>Category Name
                                </th>
                                <th width="150">
                                    <i class="fa fa-cog me-1"></i>Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $category)
                            <tr>
                                <td>
                                    <div class="category-info">
                                        <span class="category-name">{{ $category->name }}</span>
                                        @if($category->is_active)
                                            <span class="status-badge status-active">
                                                <i class="fa fa-check-circle me-1"></i>Active
                                            </span>
                                        @else
                                            <span class="status-badge status-inactive">
                                                <i class="fa fa-times-circle me-1"></i>Inactive
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <form action="{{ route('admin.faq-categories.destroy', $category->id) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this category? This will also remove all FAQs in this category.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn delete-btn" title="Delete Category">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
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

        /* FAQ Edit Container */
        .faq-edit-container {
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

        .content-editor {
            min-height: 200px;
            font-family: inherit;
            resize: vertical;
        }

        .form-help {
            color: var(--light-text);
            font-size: 0.8rem;
            margin-top: 0.25rem;
            display: block;
        }

        /* Form Actions */
        .form-actions {
            display: flex;
            gap: 1rem;
            padding-top: 1rem;
            border-top: 1px solid var(--border-color);
            margin-top: 2rem;
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

        /* Category Info */
        .category-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .category-name {
            font-weight: 500;
            color: var(--dark-text);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.5rem;
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

        .delete-btn {
            background: #FEF2F2;
            color: #DC2626;
        }

        .delete-btn:hover {
            background: #DC2626;
            color: var(--white);
            transform: translateY(-1px);
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

            .form-actions {
                flex-direction: column;
            }

            .category-info {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }
        }
    </style>
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
    });
</script>
@endsection
