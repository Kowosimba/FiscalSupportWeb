@extends('layouts.calllogs')

@section('title', 'Create New Job Card')

@section('content')

<div class="container-fluid">
    <div class="page-header-card mb-4">
        <div class="page-header-content">
            <div class="header-text">
                <h3 class="page-title">
                    <i class="fa fa-plus me-2"></i>
                    Create New Job Card
                </h3>
                <p class="page-subtitle">
                    Add a new IT support job to the system
                </p>
            </div>
            <a href="{{ route('admin.call-logs.index') }}" class="btn btn-outline-secondary btn-enhanced">
                <i class="fa fa-arrow-left me-2"></i>
                Back to Job Cards
            </a>
        </div>
    </div>

    <div class="content-card">
        <div class="content-card-header">
            <h5 class="card-title">
                <i class="fa fa-clipboard me-2"></i>
                Job Card Details
            </h5>
        </div>
        <div class="content-card-body">
            <form action="{{ route('admin.call-logs.store') }}" method="POST" id="createJobCardForm">
                @csrf
                
                <div class="row">
                    <!-- Company Information -->
                    <div class="col-md-6">
                        <div class="form-section mb-4">
                            <h6 class="section-title mb-3">
                                <i class="fa fa-building me-2"></i>
                                Company Information
                            </h6>
                            
                            <div class="form-group">
                                <label for="company_name" class="form-label">Company Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-enhanced @error('company_name') is-invalid @enderror" 
                                       id="company_name" name="company_name" value="{{ old('company_name') }}" required>
                                @error('company_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="zimra_ref" class="form-label">ZIMRA Reference</label>
                                <input type="text" class="form-control form-control-enhanced @error('zimra_ref') is-invalid @enderror" 
                                       id="zimra_ref" name="zimra_ref" value="{{ old('zimra_ref') }}" 
                                       placeholder="Optional ZIMRA reference number">
                                @error('zimra_ref')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="fault_description" class="form-label">Fault Description</label>
                                <textarea class="form-control form-control-enhanced @error('fault_description') is-invalid @enderror" 
                                          id="fault_description" name="fault_description" rows="4" 
                                          placeholder="Describe the technical issue or service required...">{{ old('fault_description') }}</textarea>
                                @error('fault_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Job Information -->
                    <div class="col-md-6">
                        <div class="form-section mb-4">
                            <h6 class="section-title mb-3">
                                <i class="fa fa-cogs me-2"></i>
                                Job Information
                            </h6>
                            
                            <div class="form-group">
                                <label for="job_card" class="form-label">Job Card Number</label>
                                <input type="text" class="form-control form-control-enhanced @error('job_card') is-invalid @enderror" 
                                       id="job_card" name="job_card" value="{{ old('job_card') }}" 
                                       placeholder="Leave blank for auto-generation">
                                @error('job_card')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="type" class="form-label">Job Type <span class="text-danger">*</span></label>
                                        <select class="form-control form-control-enhanced @error('type') is-invalid @enderror" id="type" name="type" required>
                                            <option value="">Select job type...</option>
                                            <option value="normal" {{ old('type') == 'normal' ? 'selected' : '' }}>Normal</option>
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
                                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                        <select class="form-control form-control-enhanced @error('status') is-invalid @enderror" id="status" name="status" required>
                                            <option value="">Select status...</option>
                                            <option value="pending" {{ old('status', 'pending') == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="assigned" {{ old('status') == 'assigned' ? 'selected' : '' }}>Assigned</option>
                                            <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                            <option value="complete" {{ old('status') == 'complete' ? 'selected' : '' }}>Complete</option>
                                            <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="engineer" class="form-label">Assigned Technician/Manager</label>
                                <select class="form-control form-control-enhanced @error('engineer') is-invalid @enderror" id="engineer" name="engineer">
                                    <option value="">Select technician or manager...</option>
                                    @foreach($technicians as $tech)
                                        <option value="{{ $tech->name }}" {{ old('engineer') == $tech->name ? 'selected' : '' }}>
                                            {{ $tech->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('engineer')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="approved_by" class="form-label">Approved By <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-enhanced @error('approved_by') is-invalid @enderror" 
                                       id="approved_by" name="approved_by" value="{{ old('approved_by', auth()->user()->name) }}" required readonly>
                                @error('approved_by')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Schedule & Billing -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-section mb-4">
                            <h6 class="section-title mb-3">
                                <i class="fa fa-calendar me-2"></i>
                                Schedule Details
                            </h6>
                            
                            <div class="form-group">
                                <label for="date_booked" class="form-label">Date Booked <span class="text-danger">*</span></label>
                                <input type="date" class="form-control form-control-enhanced @error('date_booked') is-invalid @enderror" 
                                       id="date_booked" name="date_booked" value="{{ old('date_booked', now()->format('Y-m-d')) }}" required>
                                @error('date_booked')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="date_resolved" class="form-label">Date Resolved</label>
                                <input type="date" class="form-control form-control-enhanced @error('date_resolved') is-invalid @enderror" 
                                       id="date_resolved" name="date_resolved" value="{{ old('date_resolved') }}">
                                @error('date_resolved')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="time_start" class="form-label">Start Time</label>
                                        <input type="time" class="form-control form-control-enhanced @error('time_start') is-invalid @enderror" 
                                               id="time_start" name="time_start" value="{{ old('time_start') }}">
                                        @error('time_start')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="time_finish" class="form-label">Finish Time</label>
                                        <input type="time" class="form-control form-control-enhanced @error('time_finish') is-invalid @enderror" 
                                               id="time_finish" name="time_finish" value="{{ old('time_finish') }}">
                                        @error('time_finish')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-section mb-4">
                            <h6 class="section-title mb-3">
                                <i class="fa fa-dollar-sign me-2"></i>
                                Billing Information
                            </h6>
                            
                            <div class="form-group">
                                <label for="billed_hours" class="form-label">Billed Hours</label>
                                <input type="number" class="form-control form-control-enhanced @error('billed_hours') is-invalid @enderror" 
                                       id="billed_hours" name="billed_hours" value="{{ old('billed_hours') }}" 
                                       step="0.25" min="0" placeholder="0.00">
                                @error('billed_hours')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="amount_charged" class="form-label">Amount Charged (USD) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input type="number" class="form-control form-control-enhanced @error('amount_charged') is-invalid @enderror" 
                                           id="amount_charged" name="amount_charged" value="{{ old('amount_charged') }}" 
                                           step="0.01" min="0" required>
                                    @error('amount_charged')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="engineer_comments" class="form-label">Engineer Comments</label>
                                <textarea class="form-control form-control-enhanced @error('engineer_comments') is-invalid @enderror" 
                                          id="engineer_comments" name="engineer_comments" rows="3" 
                                          placeholder="Technical notes and resolution details...">{{ old('engineer_comments') }}</textarea>
                                @error('engineer_comments')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="booked_by" class="form-label">Booked By</label>
                                <input type="text" class="form-control form-control-enhanced @error('booked_by') is-invalid @enderror" 
                                       id="booked_by" name="booked_by" value="{{ old('booked_by', auth()->user()->name) }}" 
                                       placeholder="Person who booked this job">
                                @error('booked_by')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions d-flex justify-content-between mt-4">
                    <a href="{{ route('admin.call-logs.index') }}" class="btn btn-outline-secondary btn-enhanced">
                        <i class="fa fa-times me-2"></i>
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary btn-enhanced">
                        <i class="fa fa-save me-2"></i>
                        Create Job Card
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .dashboard-nav-wrapper { background: var(--white); border-radius: 12px; box-shadow: var(--shadow); padding: 0.5rem; margin-bottom: 2rem;}
    .panel-nav { border: none; gap: 0.5rem; }
    .panel-nav .nav-link { border: none; padding: 0.75rem 1.5rem; border-radius: 8px; color: var(--light-text); font-weight: 500; transition: all 0.3s ease; display: flex; align-items: center; }
    .panel-nav .nav-link:hover { background: var(--hover-bg); color: var(--medium-text);}
    .panel-nav .nav-link.active { background: linear-gradient(135deg, var(--primary-green) 0%, var(--primary-green-dark) 100%); color: var(--white); box-shadow: var(--shadow-hover);}
    .page-header-card { background: var(--white); border-radius: 16px; box-shadow: var(--shadow); border: 1px solid var(--border-color); overflow: hidden;}
    .page-header-content { padding: 2rem; background: linear-gradient(135deg, var(--ultra-light-green) 0%, var(--light-green) 100%); display: flex; justify-content: space-between; align-items: center;}
    .page-title { font-size: 1.5rem; font-weight: 600; color: var(--primary-green-dark); margin: 0;}
    .page-subtitle { color: var(--light-text); margin: 0.5rem 0 0 0; font-size: 0.95rem;}
    .content-card { background: var(--white); border-radius: 16px; box-shadow: var(--shadow); overflow: hidden; border: 1px solid var(--border-color);}
    .content-card-header { padding: 1.5rem 2rem; background: linear-gradient(135deg, var(--ultra-light-green) 0%, var(--light-green) 100%); border-bottom: 1px solid var(--border-color);}
    .content-card-header .card-title { font-size: 1.25rem; font-weight: 600; color: var(--primary-green); margin: 0;}
    .content-card-body { padding: 2rem;}
    .form-section { background: var(--ultra-light-green); border-radius: 12px; padding: 1.5rem; border: 1px solid var(--light-green);}
    .section-title { color: var(--primary-green-dark); font-weight: 600; display: flex; align-items: center;}
    .form-group { margin-bottom: 1.5rem;}
    .form-label { font-weight: 600; color: var(--dark-text); margin-bottom: 0.5rem; display: block;}
    .form-control-enhanced { border: 2px solid var(--border-color); border-radius: 8px; padding: 0.75rem 1rem; font-size: 0.95rem; transition: all 0.3s ease; background: var(--white);}
    .form-control-enhanced:focus { border-color: var(--primary-green); box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1); outline: none;}
    .btn-enhanced { padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 500; transition: all 0.3s ease; border: none; display: flex; align-items: center; text-decoration: none;}
    .btn-primary.btn-enhanced { background: linear-gradient(135deg, var(--primary-green) 0%, var(--primary-green-dark) 100%); color: var(--white);}
    .btn-primary.btn-enhanced:hover { transform: translateY(-2px); box-shadow: var(--shadow-hover);}
    .btn-outline-secondary.btn-enhanced { border: 2px solid var(--border-color); color: var(--medium-text); background: transparent;}
    .btn-outline-secondary.btn-enhanced:hover { background: var(--medium-text); color: var(--white);}
    .input-group-text { background: var(--light-green); color: var(--primary-green); border: 2px solid var(--border-color); font-weight: 600;}
    .text-danger { color: #dc3545 !important;}
    .invalid-feedback { display: block; color: #dc3545; font-size: 0.875rem; margin-top: 0.25rem;}
    .is-invalid { border-color: #dc3545 !important;}
    @media (max-width: 768px) {
        .page-header-content { flex-direction: column; align-items: flex-start; gap: 1rem;}
        .content-card-header, .content-card-body { padding: 1rem;}
        .form-section { padding: 1rem;}
        .form-actions { flex-direction: column; gap: 1rem;}
    }
</style>

@push('scripts')
<script>
    $('#createJobCardForm').on('submit', function(e) {
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-2"></i> Creating...');
        
        setTimeout(() => {
            submitBtn.prop('disabled', false).html(originalText);
        }, 5000);
    });
    
    $('#company_name').on('input', function() {
        const words = $(this).val().split(' ');
        const capitalizedWords = words.map(word => 
            word.charAt(0).toUpperCase() + word.slice(1).toLowerCase()
        );
        $(this).val(capitalizedWords.join(' '));
    });
    
    $('#type').on('change', function() {
        const type = $(this).val();
        const amountField = $('#amount_charged');
        
        if (type === 'emergency') {
            if (!amountField.val()) {
                amountField.val('300.00');
            }
        } else if (type === 'installation') {
            if (!amountField.val()) {
                amountField.val('200.00');
            }
        } else if (type === 'repair') {
            if (!amountField.val()) {
                amountField.val('150.00');
            }
        } else if (type === 'maintenance') {
            if (!amountField.val()) {
                amountField.val('100.00');
            }
        } else if (type === 'consultation') {
            if (!amountField.val()) {
                amountField.val('80.00');
            }
        } else if (type === 'normal') {
            if (!amountField.val()) {
                amountField.val('120.00');
            }
        }
    });

    // Auto-assign engineer when status changes to assigned
    $('#status').on('change', function() {
        if ($(this).val() === 'assigned' && !$('#engineer').val()) {
            $('#engineer').focus();
        }
    });

    // Calculate billed hours from time inputs
    $('#time_start, #time_finish').on('change', function() {
        const startTime = $('#time_start').val();
        const finishTime = $('#time_finish').val();
        
        if (startTime && finishTime) {
            const start = new Date('2000-01-01 ' + startTime);
            const finish = new Date('2000-01-01 ' + finishTime);
            const diffMs = finish - start;
            const diffHours = diffMs / (1000 * 60 * 60);
            
            if (diffHours > 0) {
                $('#billed_hours').val(diffHours.toFixed(2));
            }
        }
    });
</script>
@endpush
@endsection
