@extends('layouts.calllogs')

@section('title', 'Edit Job - ' . $job->job_card)

@php
/** @var \Illuminate\Support\ViewErrorBag $errors */
@endphp

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">Edit Job</h1>
                    <p class="mb-0 text-muted">{{ $job->job_card }}</p>
                </div>
                <div class="btn-group">
                    <a href="{{ route('jobs.show', $job) }}" class="btn btn-secondary">
                        <i class="fas fa-eye"></i> View Job
                    </a>
                    <a href="{{ route('jobs.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Jobs
                    </a>
                </div>
            </div>

            <!-- Edit Job Form -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Edit Job Details</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('jobs.update', $job) }}" method="POST" id="editJobForm">
                        @csrf
                        @method('PUT')
                        
                        @if(auth()->user()->role === 'technician')
                            <!-- Technician View -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card mb-4">
                                        <div class="card-header">
                                            <h6 class="m-0 font-weight-bold text-info">Job Information (Read Only)</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label>Job Card</label>
                                                <input type="text" class="form-control" value="{{ $job->job_card }}" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label>Customer Name</label>
                                                <input type="text" class="form-control" value="{{ $job->customer_name }}" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label>Fault Description</label>
                                                <textarea class="form-control" rows="4" readonly>{{ $job->fault_description }}</textarea>
                                            </div>
                                            <div class="form-group">
                                                <label>Amount Charged</label>
                                                <input type="text" class="form-control" value="${{ number_format($job->amount_charged, 2) }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="card mb-4">
                                        <div class="card-header">
                                            <h6 class="m-0 font-weight-bold text-success">Technician Updates</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="status">Job Status</label>
                                                <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                                                    <option value="assigned" {{ $job->status == 'assigned' ? 'selected' : '' }}>Assigned</option>
                                                    <option value="in_progress" {{ $job->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                                    <option value="completed" {{ $job->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                                </select>
                                                @error('status')
                                                    <div class="invalid-feedback">{{ $errors->first('status') }}</div>
                                                @enderror
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="billed_hours">Billed Hours</label>
                                                <input type="number" class="form-control @error('billed_hours') is-invalid @enderror" 
                                                       id="billed_hours" name="billed_hours" value="{{ old('billed_hours', $job->billed_hours) }}" 
                                                       step="0.5" min="0" placeholder="Enter hours worked">
                                                @error('billed_hours')
                                                    <div class="invalid-feedback">{{ $errors->first('billed_hours') }}</div>
                                                @enderror
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="engineer_comments">Engineer Comments</label>
                                                <textarea class="form-control @error('engineer_comments') is-invalid @enderror" 
                                                          id="engineer_comments" name="engineer_comments" rows="6" 
                                                          placeholder="Add your comments about the work performed, parts used, etc.">{{ old('engineer_comments', $job->engineer_comments) }}</textarea>
                                                @error('engineer_comments')
                                                    <div class="invalid-feedback">{{ $errors->first('engineer_comments') }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Manager/Accountant/Admin View -->
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
                                                       id="customer_name" name="customer_name" value="{{ old('customer_name', $job->customer_name) }}" required>
                                                @error('customer_name')
                                                    <div class="invalid-feedback">{{ $errors->first('customer_name') }}</div>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label for="customer_email">Customer Email <span class="text-danger">*</span></label>
                                                <input type="email" class="form-control @error('customer_email') is-invalid @enderror" 
                                                       id="customer_email" name="customer_email" value="{{ old('customer_email', $job->customer_email) }}" required>
                                                @error('customer_email')
                                                    <div class="invalid-feedback">{{ $errors->first('customer_email') }}</div>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label for="customer_phone">Customer Phone</label>
                                                <input type="text" class="form-control @error('customer_phone') is-invalid @enderror" 
                                                       id="customer_phone" name="customer_phone" value="{{ old('customer_phone', $job->customer_phone) }}">
                                                @error('customer_phone')
                                                    <div class="invalid-feedback">{{ $errors->first('customer_phone') }}</div>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label for="customer_address">Customer Address</label>
                                                <textarea class="form-control @error('customer_address') is-invalid @enderror" 
                                                          id="customer_address" name="customer_address" rows="3">{{ old('customer_address', $job->customer_address) }}</textarea>
                                                @error('customer_address')
                                                    <div class="invalid-feedback">{{ $errors->first('customer_address') }}</div>
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
                                                          id="fault_description" name="fault_description" rows="4" required>{{ old('fault_description', $job->fault_description) }}</textarea>
                                                @error('fault_description')
                                                    <div class="invalid-feedback">{{ $errors->first('fault_description') }}</div>
                                                @enderror
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="type">Job Type <span class="text-danger">*</span></label>
                                                        <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                                                            <option value="maintenance" {{ $job->type == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                                            <option value="repair" {{ $job->type == 'repair' ? 'selected' : '' }}>Repair</option>
                                                            <option value="installation" {{ $job->type == 'installation' ? 'selected' : '' }}>Installation</option>
                                                            <option value="consultation" {{ $job->type == 'consultation' ? 'selected' : '' }}>Consultation</option>
                                                            <option value="emergency" {{ $job->type == 'emergency' ? 'selected' : '' }}>Emergency</option>
                                                        </select>
                                                        @error('type')
                                                            <div class="invalid-feedback">{{ $errors->first('type') }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="priority">Priority <span class="text-danger">*</span></label>
                                                        <select class="form-control @error('priority') is-invalid @enderror" id="priority" name="priority" required>
                                                            <option value="low" {{ $job->priority == 'low' ? 'selected' : '' }}>Low</option>
                                                            <option value="medium" {{ $job->priority == 'medium' ? 'selected' : '' }}>Medium</option>
                                                            <option value="high" {{ $job->priority == 'high' ? 'selected' : '' }}>High</option>
                                                            <option value="urgent" {{ $job->priority == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                                        </select>
                                                        @error('priority')
                                                            <div class="invalid-feedback">{{ $errors->first('priority') }}</div>
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
                                                           id="amount_charged" name="amount_charged" value="{{ old('amount_charged', $job->amount_charged) }}" 
                                                           step="0.01" min="0" required>
                                                    @error('amount_charged')
                                                        <div class="invalid-feedback">{{ $errors->first('amount_charged') }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="zimra_ref">ZIMRA Reference</label>
                                                <input type="text" class="form-control @error('zimra_ref') is-invalid @enderror" 
                                                       id="zimra_ref" name="zimra_ref" value="{{ old('zimra_ref', $job->zimra_ref) }}">
                                                @error('zimra_ref')
                                                    <div class="invalid-feedback">{{ $errors->first('zimra_ref') }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Assignment Section -->
                            @if(auth()->user()->role === 'manager' || auth()->user()->role === 'admin')
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card mb-4">
                                            <div class="card-header">
                                                <h6 class="m-0 font-weight-bold text-success">Assignment</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label for="assigned_to">Assign to Technician</label>
                                                    <select class="form-control @error('assigned_to') is-invalid @enderror" id="assigned_to" name="assigned_to">
                                                        <option value="">Select technician...</option>
                                                        @foreach($technicians as $technician)
                                                            <option value="{{ $technician->id }}" {{ $job->assigned_to == $technician->id ? 'selected' : '' }}>
                                                                {{ $technician->name }} ({{ $technician->email }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('assigned_to')
                                                        <div class="invalid-feedback">{{ $errors->first('assigned_to') }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif

                        <!-- Form Actions -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end">
                                    <a href="{{ route('jobs.show', $job) }}" class="btn btn-secondary mr-2">Cancel</a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Update Job
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
    $('#editJobForm').on('submit', function(e) {
        // Show loading state
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Updating...');
        
        // Re-enable button after 5 seconds as fallback
        setTimeout(() => {
            submitBtn.prop('disabled', false).html(originalText);
        }, 5000);
    });
    
    // Status change handler for technicians
    $('#status').on('change', function() {
        const status = $(this).val();
        const billedHoursGroup = $('#billed_hours').closest('.form-group');
        
        if (status === 'completed') {
            billedHoursGroup.show();
            $('#billed_hours').prop('required', true);
        } else {
            billedHoursGroup.hide();
            $('#billed_hours').prop('required', false);
        }
    });
    
    // Initialize status change handler
    $('#status').trigger('change');
</script>
@endpush
@endsection