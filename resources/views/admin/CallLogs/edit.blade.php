@extends('layouts.calllogs')

@section('title', 'Edit Job Card - ' . ($callLog->job_card ?? 'TBD-' . $callLog->id))

@section('content')
<div class="container-fluid">
    <div class="page-header-card mb-4">
        <div class="page-header-content">
            <div class="header-text">
                <h3 class="page-title">
                    <i class="fas fa-edit me-2"></i>
                    Edit Job Card
                </h3>
                <p class="page-subtitle">
                    Job Card: {{ $callLog->job_card ?? 'TBD-' . $callLog->id }}
                </p>
            </div>
            <div class="header-actions d-flex gap-2">
                <a href="{{ route('admin.call-logs.show', $callLog) }}" class="btn btn-outline-secondary btn-enhanced">
                    <i class="fas fa-eye me-2"></i>
                    View Job Card
                </a>
                <a href="{{ route('admin.call-logs.all') }}" class="btn btn-outline-secondary btn-enhanced">
                    <i class="fas fa-arrow-left me-2"></i>
                    Back to Jobs
                </a>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-triangle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <h6><i class="fas fa-exclamation-triangle me-2"></i>Please fix the following errors:</h6>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="content-card">
        <div class="content-card-header">
            <h5 class="card-title">
                <i class="fas fa-clipboard me-2"></i>
                Edit Job Card Details
            </h5>
        </div>
        <div class="content-card-body">
            <form action="{{ route('admin.call-logs.update', $callLog) }}" method="POST" id="editJobCardForm">
                @csrf
                @method('PUT')
                
                {{-- Hidden fields for essential data that all forms need --}}
                <input type="hidden" name="customer_name" value="{{ $callLog->customer_name ?? $callLog->company_name ?? 'Unknown Customer' }}">
                <input type="hidden" name="type" value="{{ $callLog->type ?? 'normal' }}">
                <input type="hidden" name="amount_charged" value="{{ $callLog->amount_charged ?? 0 }}">
                <input type="hidden" name="date_booked" value="{{ $callLog->date_booked ? $callLog->date_booked->format('Y-m-d') : now()->format('Y-m-d') }}">
                
                @if($callLog->assigned_to == auth()->id() && in_array(auth()->user()->role, ['technician', 'manager']))
                    {{-- Engineer View - Only assigned engineer can edit --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-section mb-4">
                                <h6 class="section-title mb-3">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Job Information (Read Only)
                                </h6>
                                
                                <div class="form-group">
                                    <label class="form-label">Job Card Number</label>
                                    <input type="text" class="form-control form-control-enhanced" 
                                           value="{{ $callLog->job_card ?? 'TBD-' . $callLog->id }}" readonly>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Customer Name</label>
                                    <input type="text" class="form-control form-control-enhanced" 
                                           value="{{ $callLog->customer_name ?? $callLog->company_name }}" readonly>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Customer Email</label>
                                    <input type="text" class="form-control form-control-enhanced" 
                                           value="{{ $callLog->customer_email ?? 'N/A' }}" readonly>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Customer Phone</label>
                                    <input type="text" class="form-control form-control-enhanced" 
                                           value="{{ $callLog->customer_phone ?? 'N/A' }}" readonly>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Fault Description</label>
                                    <textarea class="form-control form-control-enhanced" rows="4" readonly>{{ $callLog->fault_description }}</textarea>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Job Type</label>
                                    <input type="text" class="form-control form-control-enhanced" 
                                           value="{{ ucfirst($callLog->type ?? 'normal') }}" readonly>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Amount Charged</label>
                                    <input type="text" class="form-control form-control-enhanced" 
                                           value="USD ${{ number_format($callLog->amount_charged ?? 0, 2) }}" readonly>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Date Booked</label>
                                    <input type="text" class="form-control form-control-enhanced" 
                                           value="{{ $callLog->date_booked ? $callLog->date_booked->format('Y-m-d') : 'N/A' }}" readonly>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-section mb-4">
                                <h6 class="section-title mb-3">
                                    <i class="fas fa-user-cog me-2"></i>
                                    Engineer Updates
                                </h6>
                                
                                <div class="form-group">
                                    <label for="job_card" class="form-label">
                                        Job Card Number (Update with Physical Card) 
                                        <span class="text-danger" id="job_card_required" style="display: none;">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control form-control-enhanced @error('job_card') is-invalid @enderror" 
                                           id="job_card" 
                                           name="job_card" 
                                           value="{{ old('job_card', $callLog->job_card) }}"
                                           placeholder="Enter the actual job card number from hardcopy">
                                    <small class="form-text text-muted">
                                        Update this with the physical job card number when completing the job
                                    </small>
                                    @if ($errors->has('job_card'))
                                        <div class="invalid-feedback">{{ $errors->first('job_card') }}</div>
                                    @endif
                                </div>
                                
                                <div class="form-group">
                                    <label for="status" class="form-label">Job Status <span class="text-danger">*</span></label>
                                    <select class="form-control form-control-enhanced @error('status') is-invalid @enderror" 
                                            id="status" name="status" required>
                                        <option value="pending" {{ old('status', $callLog->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="assigned" {{ old('status', $callLog->status) == 'assigned' ? 'selected' : '' }}>Assigned</option>
                                        <option value="in_progress" {{ old('status', $callLog->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="completed" {{ old('status', $callLog->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                    </select>
                                    @if ($errors->has('status'))
                                        <div class="invalid-feedback">{{ $errors->first('status') }}</div>
                                    @endif
                                </div>
                                
                                <div class="form-group">
                                    <label for="time_start" class="form-label">
                                        Start Time 
                                        <span class="text-danger" id="time_start_required" style="display: none;">*</span>
                                    </label>
                                    <input type="time" 
                                           class="form-control form-control-enhanced @error('time_start') is-invalid @enderror" 
                                           id="time_start" 
                                           name="time_start" 
                                           value="{{ old('time_start', $callLog->time_start ? $callLog->time_start->format('H:i') : '') }}">
                                    @if ($errors->has('time_start'))
                                        <div class="invalid-feedback">{{ $errors->first('time_start') }}</div>
                                    @endif
                                </div>
                                
                                <div class="form-group">
                                    <label for="time_finish" class="form-label">
                                        Finish Time 
                                        <span class="text-danger" id="time_finish_required" style="display: none;">*</span>
                                    </label>
                                    <input type="time" 
                                           class="form-control form-control-enhanced @error('time_finish') is-invalid @enderror" 
                                           id="time_finish" 
                                           name="time_finish" 
                                           value="{{ old('time_finish', $callLog->time_finish ? $callLog->time_finish->format('H:i') : '') }}">
                                    @if ($errors->has('time_finish'))
                                        <div class="invalid-feedback">{{ $errors->first('time_finish') }}</div>
                                    @endif
                                </div>
                                
                                <div class="form-group">
                                    <label for="billed_hours" class="form-label">
                                        Billed Hours 
                                        <span class="text-danger" id="billed_hours_required" style="display: none;">*</span>
                                    </label>
                                    
                                    <!-- Quick Selection Buttons -->
                                    <div class="billed-hours-options mb-2">
                                        <div class="btn-group" role="group" aria-label="Quick billed hours selection">
                                            <button type="button" class="btn btn-outline-secondary btn-sm quick-hours" data-value="10%">
                                                10%
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm quick-hours" data-value="1">
                                                1 Hour
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm quick-hours" data-value="2">
                                                2 Hours
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm" id="calculateHours">
                                                <i class="fas fa-calculator me-1"></i>
                                                Calculate
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <input type="text" 
                                           class="form-control form-control-enhanced @error('billed_hours') is-invalid @enderror" 
                                           id="billed_hours" 
                                           name="billed_hours" 
                                           value="{{ old('billed_hours', $callLog->billed_hours) }}" 
                                           placeholder="e.g., 10%, 1, 2, or custom value">
                                    
                                    <small class="form-text text-muted">
                                        Common values: <strong>10%</strong> (percentage), <strong>1</strong> or <strong>2</strong> (hours), or enter custom value
                                    </small>

                                    @if ($errors->has('billed_hours'))
                                        <div class="invalid-feedback">{{ $errors->first('billed_hours') }}</div>
                                    @endif
                                </div>
                                
                                <div class="form-group">
                                    <label for="date_resolved" class="form-label">
                                        Date Resolved 
                                        <span class="text-danger" id="date_resolved_required" style="display: none;">*</span>
                                    </label>
                                    <input type="date" 
                                           class="form-control form-control-enhanced @error('date_resolved') is-invalid @enderror" 
                                           id="date_resolved" 
                                           name="date_resolved" 
                                           value="{{ old('date_resolved', $callLog->date_resolved ? $callLog->date_resolved->format('Y-m-d') : '') }}">
                                    @if ($errors->has('date_resolved'))
                                        <div class="invalid-feedback">{{ $errors->first('date_resolved') }}</div>
                                    @endif
                                </div>
                                
                                <div class="form-group">
                                    <label for="engineer_comments" class="form-label">
                                        Engineer Comments 
                                        <span class="text-danger" id="engineer_comments_required" style="display: none;">*</span>
                                    </label>
                                    <textarea class="form-control form-control-enhanced @error('engineer_comments') is-invalid @enderror" 
                                              id="engineer_comments" 
                                              name="engineer_comments" 
                                              rows="6" 
                                              placeholder="Required: Describe the work done, parts used, resolution steps, etc.">{{ old('engineer_comments', $callLog->engineer_comments) }}</textarea>
                                    <small class="form-text text-muted">
                                        Required when marking job as complete. Describe the technical work performed.
                                    </small>
                                    @if ($errors->has('engineer_comments'))
                                        <div class="invalid-feedback">{{ $errors->first('engineer_comments') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                @elseif(in_array(auth()->user()->role, ['admin', 'accounts']))
                    {{-- Admin/Accounts View - Full access --}}
                    <div class="row">
                        <!-- Customer Information -->
                        <div class="col-md-6">
                            <div class="form-section mb-4">
                                <h6 class="section-title mb-3">
                                    <i class="fas fa-user me-2"></i>
                                    Customer Information
                                </h6>
                                
                                <div class="form-group">
                                    <label for="job_card_admin" class="form-label">Job Card Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-enhanced @error('job_card') is-invalid @enderror" 
                                           id="job_card_admin" name="job_card" 
                                           value="{{ old('job_card', $callLog->job_card) }}" required>
                                    @if ($errors->has('job_card'))
                                        <div class="invalid-feedback">{{ $errors->first('job_card') }}</div>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="customer_name_admin" class="form-label">Customer Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-enhanced @error('customer_name') is-invalid @enderror" 
                                           id="customer_name_admin" name="customer_name" 
                                           value="{{ old('customer_name', $callLog->customer_name ?? $callLog->company_name) }}" required>
                                    @if ($errors->has('customer_name'))
                                        <div class="invalid-feedback">{{ $errors->first('customer_name') }}</div>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="customer_email_admin" class="form-label">Customer Email</label>
                                    <input type="email" class="form-control form-control-enhanced @error('customer_email') is-invalid @enderror" 
                                           id="customer_email_admin" name="customer_email" 
                                           value="{{ old('customer_email', $callLog->customer_email) }}">
                                    @if ($errors->has('customer_email'))
                                        <div class="invalid-feedback">{{ $errors->first('customer_email') }}</div>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="customer_phone_admin" class="form-label">Customer Phone</label>
                                    <input type="text" class="form-control form-control-enhanced @error('customer_phone') is-invalid @enderror" 
                                           id="customer_phone_admin" name="customer_phone" 
                                           value="{{ old('customer_phone', $callLog->customer_phone) }}">
                                    @if ($errors->has('customer_phone'))
                                        <div class="invalid-feedback">{{ $errors->first('customer_phone') }}</div>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="zimra_ref_admin" class="form-label">ZIMRA Reference</label>
                                    <input type="text" class="form-control form-control-enhanced @error('zimra_ref') is-invalid @enderror" 
                                           id="zimra_ref_admin" name="zimra_ref" 
                                           value="{{ old('zimra_ref', $callLog->zimra_ref) }}">
                                    @if ($errors->has('zimra_ref'))
                                        <div class="invalid-feedback">{{ $errors->first('zimra_ref') }}</div>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="fault_description_admin" class="form-label">Fault Description</label>
                                    <textarea class="form-control form-control-enhanced @error('fault_description') is-invalid @enderror" 
                                              id="fault_description_admin" name="fault_description" rows="4">{{ old('fault_description', $callLog->fault_description) }}</textarea>
                                    @if ($errors->has('fault_description'))
                                        <div class="invalid-feedback">{{ $errors->first('fault_description') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Job Information -->
                        <div class="col-md-6">
                            <div class="form-section mb-4">
                                <h6 class="section-title mb-3">
                                    <i class="fas fa-cogs me-2"></i>
                                    Job Information
                                </h6>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="type_admin" class="form-label">Job Type <span class="text-danger">*</span></label>
                                            <select class="form-control form-control-enhanced @error('type') is-invalid @enderror" 
                                                    id="type_admin" name="type" required>
                                                <option value="normal" {{ old('type', $callLog->type) == 'normal' ? 'selected' : '' }}>Normal</option>
                                                <option value="maintenance" {{ old('type', $callLog->type) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                                <option value="repair" {{ old('type', $callLog->type) == 'repair' ? 'selected' : '' }}>Repair</option>
                                                <option value="installation" {{ old('type', $callLog->type) == 'installation' ? 'selected' : '' }}>Installation</option>
                                                <option value="consultation" {{ old('type', $callLog->type) == 'consultation' ? 'selected' : '' }}>Consultation</option>
                                                <option value="emergency" {{ old('type', $callLog->type) == 'emergency' ? 'selected' : '' }}>Emergency</option>
                                            </select>
                                            @if ($errors->has('type'))
                                                <div class="invalid-feedback">{{ $errors->first('type') }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="status_admin" class="form-label">Status <span class="text-danger">*</span></label>
                                            <select class="form-control form-control-enhanced @error('status') is-invalid @enderror" 
                                                    id="status_admin" name="status" required>
                                                <option value="pending" {{ old('status', $callLog->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="assigned" {{ old('status', $callLog->status) == 'assigned' ? 'selected' : '' }}>Assigned</option>
                                                <option value="in_progress" {{ old('status', $callLog->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                                <option value="complete" {{ old('status', $callLog->status) == 'complete' ? 'selected' : '' }}>Complete</option>
                                                <option value="cancelled" {{ old('status', $callLog->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                            </select>
                                            @if ($errors->has('status'))
                                                <div class="invalid-feedback">{{ $errors->first('status') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="assigned_to_admin" class="form-label">Assigned Engineer</label>
                                    <select class="form-control form-control-enhanced @error('assigned_to') is-invalid @enderror" 
                                            id="assigned_to_admin" name="assigned_to">
                                        <option value="">Select engineer...</option>
                                        @foreach(\App\Models\User::whereIn('role', ['technician', 'manager'])->get() as $user)
                                            <option value="{{ $user->id }}" {{ old('assigned_to', $callLog->assigned_to) == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('assigned_to'))
                                        <div class="invalid-feedback">{{ $errors->first('assigned_to') }}</div>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="approved_by_admin" class="form-label">Approved By <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-enhanced @error('approved_by') is-invalid @enderror" 
                                           id="approved_by_admin" name="approved_by" 
                                           value="{{ old('approved_by', $callLog->approved_by ?? auth()->user()->name) }}" required>
                                    @if ($errors->has('approved_by'))
                                        <div class="invalid-feedback">{{ $errors->first('approved_by') }}</div>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="booked_by_admin" class="form-label">Booked By</label>
                                    <input type="text" class="form-control form-control-enhanced @error('booked_by') is-invalid @enderror" 
                                           id="booked_by_admin" name="booked_by" 
                                           value="{{ old('booked_by', $callLog->booked_by) }}">
                                    @if ($errors->has('booked_by'))
                                        <div class="invalid-feedback">{{ $errors->first('booked_by') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Schedule & Billing -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-section mb-4">
                                <h6 class="section-title mb-3">
                                    <i class="fas fa-calendar me-2"></i>
                                    Schedule Details
                                </h6>
                                
                                <div class="form-group">
                                    <label for="date_booked_admin" class="form-label">Date Booked <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control form-control-enhanced @error('date_booked') is-invalid @enderror" 
                                           id="date_booked_admin" name="date_booked" 
                                           value="{{ old('date_booked', $callLog->date_booked ? $callLog->date_booked->format('Y-m-d') : now()->format('Y-m-d')) }}" required>
                                    @if ($errors->has('date_booked'))
                                        <div class="invalid-feedback">{{ $errors->first('date_booked') }}</div>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="date_resolved_admin" class="form-label">Date Resolved</label>
                                    <input type="date" class="form-control form-control-enhanced @error('date_resolved') is-invalid @enderror" 
                                           id="date_resolved_admin" name="date_resolved" 
                                           value="{{ old('date_resolved', $callLog->date_resolved ? $callLog->date_resolved->format('Y-m-d') : '') }}">
                                    @if ($errors->has('date_resolved'))
                                        <div class="invalid-feedback">{{ $errors->first('date_resolved') }}</div>
                                    @endif
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="time_start_admin" class="form-label">Start Time</label>
                                            <input type="time" class="form-control form-control-enhanced @error('time_start') is-invalid @enderror" 
                                                   id="time_start_admin" name="time_start" 
                                                   value="{{ old('time_start', $callLog->time_start ? $callLog->time_start->format('H:i') : '') }}">
                                            @if ($errors->has('time_start'))
                                                <div class="invalid-feedback">{{ $errors->first('time_start') }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="time_finish_admin" class="form-label">Finish Time</label>
                                            <input type="time" class="form-control form-control-enhanced @error('time_finish') is-invalid @enderror" 
                                                   id="time_finish_admin" name="time_finish" 
                                                   value="{{ old('time_finish', $callLog->time_finish ? $callLog->time_finish->format('H:i') : '') }}">
                                            @if ($errors->has('time_finish'))
                                                <div class="invalid-feedback">{{ $errors->first('time_finish') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-section mb-4">
                                <h6 class="section-title mb-3">
                                    <i class="fas fa-dollar-sign me-2"></i>
                                    Billing Information
                                </h6>
                                
                                <div class="form-group">
                                    <label for="billed_hours_admin" class="form-label">Billed Hours</label>
                                    
                                    <!-- Quick Selection Buttons -->
                                    <div class="billed-hours-options mb-2">
                                        <div class="btn-group" role="group" aria-label="Quick billed hours selection">
                                            <button type="button" class="btn btn-outline-secondary btn-sm quick-hours-admin" data-value="10%">
                                                10%
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm quick-hours-admin" data-value="1">
                                                1 Hour
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm quick-hours-admin" data-value="2">
                                                2 Hours
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm" id="calculateHoursAdmin">
                                                <i class="fas fa-calculator me-1"></i>
                                                Calculate
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <input type="text" class="form-control form-control-enhanced @error('billed_hours') is-invalid @enderror" 
                                           id="billed_hours_admin" name="billed_hours" 
                                           value="{{ old('billed_hours', $callLog->billed_hours) }}" 
                                           placeholder="e.g., 10%, 1, 2, or custom value">
                                    
                                    <small class="form-text text-muted">
                                        Common values: <strong>10%</strong> (percentage), <strong>1</strong> or <strong>2</strong> (hours), or enter custom value
                                    </small>

                                    @if($errors->has('billed_hours'))
                                        <div class="invalid-feedback">{{ $errors->first('billed_hours') }}</div>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="amount_charged_admin" class="form-label">Amount Charged (USD) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control form-control-enhanced @error('amount_charged') is-invalid @enderror" 
                                               id="amount_charged_admin" name="amount_charged" 
                                               value="{{ old('amount_charged', $callLog->amount_charged ?? 0) }}" 
                                               step="0.01" min="0" required>
                                    </div>
                                    @if($errors->has('amount_charged'))
                                        <div class="invalid-feedback">{{ $errors->first('amount_charged') }}</div>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="engineer_comments_admin" class="form-label">Engineer Comments</label>
                                    <textarea class="form-control form-control-enhanced @error('engineer_comments') is-invalid @enderror" 
                                              id="engineer_comments_admin" name="engineer_comments" rows="4" 
                                              placeholder="Technical notes and resolution details...">{{ old('engineer_comments', $callLog->engineer_comments) }}</textarea>
                                    @if($errors->has('engineer_comments'))
                                        <div class="invalid-feedback">{{ $errors->first('engineer_comments') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Unauthorized Access -->
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        You are not authorized to edit this job card. Only the assigned engineer, admin, or accounts can edit job cards.
                    </div>
                @endif

                <!-- Form Actions -->
                @if(($callLog->assigned_to == auth()->id() && in_array(auth()->user()->role, ['technician', 'manager'])) || in_array(auth()->user()->role, ['admin', 'accounts']))
                    <div class="form-actions d-flex justify-content-between mt-4">
                        <a href="{{ route('admin.call-logs.show', $callLog) }}" class="btn btn-outline-secondary btn-enhanced">
                            <i class="fas fa-times me-2"></i>
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary btn-enhanced">
                            <i class="fas fa-save me-2"></i>
                            Update Job Card
                        </button>
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
:root {
    --primary-green: #22c55e;
    --primary-green-dark: #16a34a;
    --success-green: #10b981;
    --light-green: #dcfce7;
    --ultra-light-green: #f0fdf4;
    --secondary-green: #a7f3d0;
    --white: #ffffff;
    --light-gray: #f8fafc;
    --border-color: #e2e8f0;
    --text-primary: #1e293b;
    --text-secondary: #64748b;
    --text-muted: #94a3b8;
    --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
    --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
    --shadow-hover: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
}

.page-header-card {
    background: var(--white);
    border-radius: 16px;
    box-shadow: var(--shadow-lg);
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
}

.page-subtitle {
    color: var(--text-secondary);
    margin: 0.5rem 0 0 0;
    font-size: 0.95rem;
}

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
}

.content-card-body {
    padding: 2rem;
}

.form-section {
    background: var(--ultra-light-green);
    border-radius: 12px;
    padding: 1.5rem;
    border: 1px solid var(--light-green);
}

.section-title {
    color: var(--primary-green-dark);
    font-weight: 600;
    display: flex;
    align-items: center;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
    display: block;
}

.form-control-enhanced {
    border: 2px solid var(--border-color);
    border-radius: 8px;
    padding: 0.75rem 1rem;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    background: var(--white);
    width: 100%;
}

.form-control-enhanced:focus {
    border-color: var(--primary-green);
    box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
    outline: none;
}

.form-control-enhanced[readonly] {
    background: var(--light-gray);
    color: var(--text-secondary);
}

.form-control-enhanced.is-invalid {
    border-color: #dc3545;
}

.invalid-feedback {
    display: block;
    color: #dc3545;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

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

.btn-outline-secondary.btn-enhanced {
    border: 2px solid var(--border-color);
    color: var(--text-secondary);
    background: transparent;
}

.btn-outline-secondary.btn-enhanced:hover {
    background: var(--text-secondary);
    color: var(--white);
}

.input-group-text {
    background: var(--light-green);
    color: var(--primary-green);
    border: 2px solid var(--border-color);
    font-weight: 600;
}

.text-danger {
    color: #dc3545 !important;
}

.form-text {
    font-size: 0.875rem;
    color: var(--text-muted);
    margin-top: 0.25rem;
}

.alert {
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1rem;
}

.alert-warning {
    background: #fff3cd;
    border: 1px solid #ffeaa7;
    color: #856404;
}

.alert-success {
    background: var(--ultra-light-green);
    border: 1px solid var(--light-green);
    color: var(--primary-green-dark);
}

.alert-danger {
    background: #f8d7da;
    border: 1px solid #f5c6cb;
    color: #721c24;
}

.billed-hours-options {
    margin-bottom: 0.5rem;
}

.btn-group .btn {
    font-size: 0.875rem;
    padding: 0.375rem 0.75rem;
}

.quick-hours.active, .quick-hours-admin.active {
    background-color: var(--primary-green) !important;
    border-color: var(--primary-green) !important;
    color: white !important;
}

.quick-hours:hover, .quick-hours-admin:hover {
    background-color: var(--primary-green);
    border-color: var(--primary-green);
    color: white;
}

#calculateHours, #calculateHoursAdmin {
    background-color: #6c757d;
    border-color: #6c757d;
    color: white;
}

#calculateHours:hover, #calculateHoursAdmin:hover {
    background-color: #5a6268;
    border-color: #545b62;
}

@media (max-width: 768px) {
    .page-header-content {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .header-actions {
        flex-direction: column;
        width: 100%;
    }
    
    .content-card-header, .content-card-body {
        padding: 1rem;
    }
    
    .form-section {
        padding: 1rem;
    }
    
    .form-actions {
        flex-direction: column;
        gap: 1rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get elements based on current user type
    const statusSelect = document.getElementById('status') || document.getElementById('status_admin');
    const jobCardField = document.getElementById('job_card') || document.getElementById('job_card_admin');
    const timeStartField = document.getElementById('time_start') || document.getElementById('time_start_admin');
    const timeFinishField = document.getElementById('time_finish') || document.getElementById('time_finish_admin');
    const billedHoursField = document.getElementById('billed_hours') || document.getElementById('billed_hours_admin');
    const dateResolvedField = document.getElementById('date_resolved') || document.getElementById('date_resolved_admin');
    const engineerCommentsField = document.getElementById('engineer_comments') || document.getElementById('engineer_comments_admin');
    
    // Required field indicators (only for engineer view)
    const jobCardRequired = document.getElementById('job_card_required');
    const timeStartRequired = document.getElementById('time_start_required');
    const timeFinishRequired = document.getElementById('time_finish_required');
    const billedHoursRequired = document.getElementById('billed_hours_required');
    const dateResolvedRequired = document.getElementById('date_resolved_required');
    const engineerCommentsRequired = document.getElementById('engineer_comments_required');
    
    // Quick hours buttons
    const quickHoursButtons = document.querySelectorAll('.quick-hours, .quick-hours-admin');
    const calculateButton = document.getElementById('calculateHours') || document.getElementById('calculateHoursAdmin');
    
    function updateRequiredFields() {
        if (!statusSelect) return;
        
        const status = statusSelect.value;
        
        if (status === 'complete') {
            // Make fields required for completion (engineer view only)
            if (jobCardField && jobCardRequired) {
                jobCardField.required = true;
                jobCardRequired.style.display = 'inline';
            }
            if (timeStartField && timeStartRequired) {
                timeStartField.required = true;
                timeStartRequired.style.display = 'inline';
            }
            if (timeFinishField && timeFinishRequired) {
                timeFinishField.required = true;
                timeFinishRequired.style.display = 'inline';
            }
            if (billedHoursField && billedHoursRequired) {
                billedHoursField.required = true;
                billedHoursRequired.style.display = 'inline';
            }
            if (dateResolvedField && dateResolvedRequired) {
                dateResolvedField.required = true;
                dateResolvedRequired.style.display = 'inline';
                if (!dateResolvedField.value) {
                    dateResolvedField.value = new Date().toISOString().split('T')[0];
                }
            }
            if (engineerCommentsField && engineerCommentsRequired) {
                engineerCommentsField.required = true;
                engineerCommentsRequired.style.display = 'inline';
            }
        } else {
            // Remove required for non-complete status
            if (timeStartRequired) timeStartRequired.style.display = 'none';
            if (timeFinishRequired) timeFinishRequired.style.display = 'none';
            if (billedHoursRequired) billedHoursRequired.style.display = 'none';
            if (dateResolvedRequired) dateResolvedRequired.style.display = 'none';
            if (engineerCommentsRequired) engineerCommentsRequired.style.display = 'none';
            
            if (timeStartField) timeStartField.required = false;
            if (timeFinishField) timeFinishField.required = false;
            if (billedHoursField) billedHoursField.required = false;
            if (dateResolvedField) dateResolvedField.required = false;
            if (engineerCommentsField) engineerCommentsField.required = false;
        }
    }
    
    // Validate time finish is after time start
    function validateTimes() {
        if (timeStartField && timeFinishField) {
            const startTime = timeStartField.value;
            const finishTime = timeFinishField.value;
            
            if (startTime && finishTime) {
                const start = new Date('2000-01-01 ' + startTime);
                const finish = new Date('2000-01-01 ' + finishTime);
                
                if (finish <= start) {
                    timeFinishField.setCustomValidity('Finish time must be after start time');
                    timeFinishField.classList.add('is-invalid');
                    
                    // Show error message
                    let errorDiv = timeFinishField.nextElementSibling;
                    if (!errorDiv || !errorDiv.classList.contains('invalid-feedback')) {
                        errorDiv = document.createElement('div');
                        errorDiv.classList.add('invalid-feedback');
                        timeFinishField.parentNode.appendChild(errorDiv);
                    }
                    errorDiv.textContent = 'Finish time must be after start time';
                    
                    return false;
                } else {
                    timeFinishField.setCustomValidity('');
                    timeFinishField.classList.remove('is-invalid');
                    
                    // Remove error message
                    let errorDiv = timeFinishField.nextElementSibling;
                    if (errorDiv && errorDiv.classList.contains('invalid-feedback') && errorDiv.textContent.includes('Finish time')) {
                        errorDiv.remove();
                    }
                    
                    return true;
                }
            }
        }
        return true;
    }
    
    // Calculate billed hours from time inputs
    function calculateBilledHours() {
        if (timeStartField && timeFinishField && billedHoursField) {
            const startTime = timeStartField.value;
            const finishTime = timeFinishField.value;
            
            if (startTime && finishTime && validateTimes()) {
                const start = new Date('2000-01-01 ' + startTime);
                const finish = new Date('2000-01-01 ' + finishTime);
                const diffMs = finish - start;
                const diffHours = diffMs / (1000 * 60 * 60);
                
                if (diffHours > 0) {
                    // Round to nearest 0.25 hour
                    const roundedHours = Math.round(diffHours * 4) / 4;
                    billedHoursField.value = roundedHours.toString();
                }
            }
        }
    }
    
    // Quick hours button functionality
    quickHoursButtons.forEach(button => {
        button.addEventListener('click', function() {
            const value = this.dataset.value;
            if (billedHoursField) {
                billedHoursField.value = value;
            }
            
            // Remove active class from all buttons
            quickHoursButtons.forEach(btn => {
                btn.classList.remove('active', 'btn-primary');
                btn.classList.add('btn-outline-secondary');
            });
            
            // Add active class to clicked button
            this.classList.remove('btn-outline-secondary');
            this.classList.add('btn-primary', 'active');
        });
    });
    
    // Calculate button functionality
    if (calculateButton) {
        calculateButton.addEventListener('click', function() {
            calculateBilledHours();
            
            // Remove active class from quick buttons
            quickHoursButtons.forEach(btn => {
                btn.classList.remove('active', 'btn-primary');
                btn.classList.add('btn-outline-secondary');
            });
        });
    }
    
    // Event listeners
    if (statusSelect) {
        statusSelect.addEventListener('change', updateRequiredFields);
        updateRequiredFields(); // Initialize on page load
    }
    
    if (timeStartField) {
        timeStartField.addEventListener('change', function() {
            validateTimes();
        });
    }
    
    if (timeFinishField) {
        timeFinishField.addEventListener('change', function() {
            validateTimes();
        });
    }
    
    // Form submission with enhanced validation
    const form = document.getElementById('editJobCardForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            const status = statusSelect ? statusSelect.value : '';
            
            // Validate times before submission
            if (!validateTimes()) {
                e.preventDefault();
                showNotification('Please fix the time validation errors before submitting.', 'error');
                return false;
            }
            
            if (status === 'complete') {
                let missingFields = [];
                
                if (jobCardField && !jobCardField.value.trim()) {
                    missingFields.push('Job Card Number');
                }
                if (timeStartField && !timeStartField.value) {
                    missingFields.push('Start Time');
                }
                if (timeFinishField && !timeFinishField.value) {
                    missingFields.push('Finish Time');
                }
                if (billedHoursField && !billedHoursField.value.trim()) {
                    missingFields.push('Billed Hours');
                }
                if (dateResolvedField && !dateResolvedField.value) {
                    missingFields.push('Date Resolved');
                }
                if (engineerCommentsField && !engineerCommentsField.value.trim()) {
                    missingFields.push('Engineer Comments');
                }
                
                if (missingFields.length > 0) {
                    e.preventDefault();
                    showNotification('To mark this job as complete, you must fill in the following fields:\n\n' + missingFields.join('\n'), 'error');
                    return false;
                }
                
                // Validate engineer comments length
                if (engineerCommentsField && engineerCommentsField.value.trim().length < 10) {
                    e.preventDefault();
                    showNotification('Engineer comments must be at least 10 characters long when completing a job.', 'error');
                    return false;
                }
            }
            
            // Show loading state
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Updating...';
                
                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }, 10000);
            }
        });
    }
    
    // Set current value as active if it matches a quick option
    if (billedHoursField && billedHoursField.value) {
        const currentValue = billedHoursField.value;
        quickHoursButtons.forEach(button => {
            if (button.dataset.value === currentValue) {
                button.classList.remove('btn-outline-secondary');
                button.classList.add('btn-primary', 'active');
            }
        });
    }
});

// Notification function
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'error' ? 'danger' : type === 'success' ? 'success' : 'info'} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px; max-width: 500px;';
    notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}
</script>
@endpush
