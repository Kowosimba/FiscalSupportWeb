@extends('layouts.calllogs')

@section('title', 'Create New Job Card')

@section('content')
<div class="container-fluid px-4">
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">
                        <i class="fas fa-plus-circle text-success me-2"></i>
                        Create New Job Card
                    </h5>
                    <p class="text-muted mb-0">Add a new support job to the system</p>
                </div>
                <a href="{{ route('admin.call-logs.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Back to Jobs
                </a>
            </div>
        </div>
        
        <div class="card-body">
            <form action="{{ route('admin.call-logs.store') }}" method="POST" id="createJobCardForm">
                @csrf
                
                <div class="row">
                    <!-- Left Column -->
                    <div class="col-md-6">
                        <!-- Customer Information -->
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2 mb-3 text-success">
                                <i class="fas fa-user me-2"></i>
                                Customer Information
                            </h6>
                            
                            <div class="mb-3">
                                <label for="customer_name" class="form-label">Customer Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('customer_name') is-invalid @enderror"
                                    id="customer_name" name="customer_name" value="{{ old('customer_name') }}" required>
                                @error('customer_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="customer_email" class="form-label">Customer Email</label>
                                <input type="email" class="form-control @error('customer_email') is-invalid @enderror"
                                    id="customer_email" name="customer_email" value="{{ old('customer_email') }}">
                                @error('customer_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="customer_phone" class="form-label">Customer Phone</label>
                                <input type="text" class="form-control @error('customer_phone') is-invalid @enderror"
                                    id="customer_phone" name="customer_phone" value="{{ old('customer_phone') }}">
                                @error('customer_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Fault Information -->
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2 mb-3 text-success">
                                <i class="fas fa-bug me-2"></i>
                                Fault Information
                            </h6>
                            
                            <div class="mb-3">
                                <label for="fault_description" class="form-label">Fault Description <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('fault_description') is-invalid @enderror"
                                    id="fault_description" name="fault_description" rows="4" required>{{ old('fault_description') }}</textarea>
                                @error('fault_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="zimra_ref" class="form-label">ZIMRA Reference</label>
                                <input type="text" class="form-control @error('zimra_ref') is-invalid @enderror"
                                    id="zimra_ref" name="zimra_ref" value="{{ old('zimra_ref') }}">
                                @error('zimra_ref')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right Column -->
                    <div class="col-md-6">
                        <!-- Job Details -->
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2 mb-3 text-success">
                                <i class="fas fa-clipboard-list me-2"></i>
                                Job Details
                            </h6>
                            
                            <div class="mb-3">
                                <label for="type" class="form-label">Job Type <span class="text-danger">*</span></label>
                                <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                    <option value="">Select job type...</option>
                                    <option value="normal" {{ old('type') == 'normal' ? 'selected' : '' }}>Normal</option>
                                    <option value="emergency" {{ old('type') == 'emergency' ? 'selected' : '' }}>Emergency</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            @if(auth()->user()->isAdmin() || auth()->user()->isAccounts())
                                <!-- Hidden approved_by field with current user's ID -->
                               <div class="mb-3">
                                    <label class="form-label">Approved By</label>
                                    <input type="text" class="form-control" 
                                        value="{{ auth()->user()->name }}" readonly>
                                    <input type="hidden" name="approved_by" value="{{ auth()->id() }}">
                                    <!-- No need for a separate approved_by_name field in the form -->
                                </div>
                                
                                <div class="mb-3">
                                    <label for="amount_charged" class="form-label">Amount Charged (USD) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control @error('amount_charged') is-invalid @enderror"
                                            id="amount_charged" name="amount_charged" value="{{ old('amount_charged') }}"
                                            step="0.01" min="0" required>
                                        @if($errors->has('amount_charged'))
                                            <div class="invalid-feedback">{{ $errors->first('amount_charged') }}</div>
                                        @endif
                                    </div>
                                </div>
                                
                                <input type="hidden" name="status" value="pending">
                            @endif
                        </div>
                        
                        <!-- Date Booked (auto-set to current date) -->
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2 mb-3 text-success">
                                <i class="fas fa-calendar me-2"></i>
                                Job Date
                            </h6>
                            
                            <div class="mb-3">
                                <label for="date_booked" class="form-label">Date Booked</label>
                                <input type="date" class="form-control @error('date_booked') is-invalid @enderror"
                                    id="date_booked" name="date_booked" value="{{ old('date_booked', now()->format('Y-m-d')) }}" readonly>
                                @error('date_booked')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Form Actions -->
                <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                    <button type="reset" class="btn btn-outline-secondary">
                        <i class="fas fa-undo me-2"></i>
                        Reset
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i>
                        Create Job Card
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection