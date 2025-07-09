@extends('layouts.calllogs')

@section('title', 'Create New Job')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">Create New Job</h1>
                    <p class="mb-0 text-muted">Add a new service request to the system</p>
                </div>
                <a href="{{ route('jobs.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Jobs
                </a>
            </div>

            <!-- Create Job Form -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Job Details</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('jobs.store') }}" method="POST" id="createJobForm">
                        @csrf
                        
                        <div class="row">
                            <!-- Customer Information -->
                            <div class="col-md-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h6 class="m-0 font-weight-bold text-info">Customer Information</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="customer_name">Customer Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('customer_name') is-invalid @enderror" 
                                                   id="customer_name" name="customer_name" value="{{ old('customer_name') }}" required>
                                            @error('customer_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="customer_email">Customer Email <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control @error('customer_email') is-invalid @enderror" 
                                                   id="customer_email" name="customer_email" value="{{ old('customer_email') }}" required>
                                            @error('customer_email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="customer_phone">Customer Phone</label>
                                            <input type="text" class="form-control @error('customer_phone') is-invalid @enderror" 
                                                   id="customer_phone" name="customer_phone" value="{{ old('customer_phone') }}">
                                            @error('customer_phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="customer_address">Customer Address</label>
                                            <textarea class="form-control @error('customer_address') is-invalid @enderror" 
                                                      id="customer_address" name="customer_address" rows="3">{{ old('customer_address') }}</textarea>
                                            @error('customer_address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Job Information -->
                            <div class="col-md-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h6 class="m-0 font-weight-bold text-warning">Job Information</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="fault_description">Fault Description <span class="text-danger">*</span></label>
                                            <textarea class="form-control @error('fault_description') is-invalid @enderror" 
                                                      id="fault_description" name="fault_description" rows="4" required 
                                                      placeholder="Describe the problem or service request in detail...">{{ old('fault_description') }}</textarea>
                                            @error('fault_description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="type">Job Type <span class="text-danger">*</span></label>
                                                    <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                                                        <option value="">Select job type...</option>
                                                        <option value="maintenance" {{ old('type') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                                        <option value="repair" {{ old('type') == 'repair' ? 'selected' : '' }}>Repair</option>
                                                        <option value="installation" {{ old('type') == 'installation' ? 'selected' : '' }}>Installation</option>
                                                        <option value="consultation" {{ old('type') == 'consultation' ? 'selected' : '' }}>Consultation</option>
                                                        <option value="emergency" {{ old('type') == 'emergency' ? 'selected' : '' }}>Emergency</option>
                                                    </select>
                                                    @error('type')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="priority">Priority <span class="text-danger">*</span></label>
                                                    <select class="form-control @error('priority') is-invalid @enderror" id="priority" name="priority" required>
                                                        <option value="">Select priority...</option>
                                                        <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                                        <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                                                        <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                                                        <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                                    </select>
                                                    @error('priority')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="amount_charged">Amount Charged <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">$</span>
                                                </div>
                                                <input type="number" class="form-control @error('amount_charged') is-invalid @enderror" 
                                                       id="amount_charged" name="amount_charged" value="{{ old('amount_charged') }}" 
                                                       step="0.01" min="0" required>
                                                @error('amount_charged')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="zimra_ref">ZIMRA Reference</label>
                                            <input type="text" class="form-control @error('zimra_ref') is-invalid @enderror" 
                                                   id="zimra_ref" name="zimra_ref" value="{{ old('zimra_ref') }}" 
                                                   placeholder="Optional reference number">
                                            @error('zimra_ref')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end">
                                    <a href="{{ route('jobs.index') }}" class="btn btn-secondary mr-2">Cancel</a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Create Job
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $('#createJobForm').on('submit', function(e) {
        // Show loading state
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Creating...');
        
        // Re-enable button after 5 seconds as fallback
        setTimeout(() => {
            submitBtn.prop('disabled', false).html(originalText);
        }, 5000);
    });
    
    // Auto-capitalize customer name
    $('#customer_name').on('input', function() {
        const words = $(this).val().split(' ');
        const capitalizedWords = words.map(word => 
            word.charAt(0).toUpperCase() + word.slice(1).toLowerCase()
        );
        $(this).val(capitalizedWords.join(' '));
    });
    
    // Priority change handler
    $('#priority').on('change', function() {
        const priority = $(this).val();
        const amountField = $('#amount_charged');
        
        // Suggest pricing based on priority
        if (priority === 'urgent') {
            if (!amountField.val()) {
                amountField.val('150.00');
            }
        } else if (priority === 'high') {
            if (!amountField.val()) {
                amountField.val('100.00');
            }
        }
    });
</script>
@endpush
@endsection
