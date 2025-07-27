@extends('layouts.app')

@section('title', 'Create New Job')

@section('content')
<div class="dashboard-container">
    {{-- Compact Header --}}
    <div class="dashboard-header mb-2">
        <div class="header-content">
            <h1 class="dashboard-title">
                <i class="fas fa-plus-circle me-2"></i>
                Create New Job
            </h1>
            <div class="header-meta">
                <span class="badge bg-info me-2">
                    <i class="fas fa-file-plus me-1"></i>
                    New Job
                </span>
                <small class="text-muted">Add a new support job to the system</small>
            </div>
        </div>
        <div class="header-actions">
            <button onclick="window.location.href='{{ route('admin.call-logs.index') }}'" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>
                Back to Jobs
            </button>
        </div>
    </div>

    {{-- Progress Indicator --}}
    <div class="progress-indicator mb-2">
        <div class="progress-bar">
            <div class="progress-fill" style="width: 0%"></div>
        </div>
        <small class="progress-text text-muted">Complete the form to create job</small>
    </div>

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-2">
            <i class="fas fa-exclamation-circle me-2"></i>
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Job Card Form --}}
    <div class="content-card">
        <div class="content-card-header">
            <div class="header-content">
                <h4 class="card-title">
                    <i class="fas fa-clipboard-list me-2"></i>
                    Job Card Information
                </h4>
                <p class="card-subtitle mb-0">
                    Enter the job details and customer information
                </p>
            </div>
            <div class="header-actions">
                <span class="completion-badge">
                    <i class="fas fa-tasks me-1"></i>
                    <span class="completion-count">0/6</span> Complete
                </span>
            </div>
        </div>
        
        <div class="content-card-body" style="padding: 1.5rem;">
            <form action="{{ route('admin.call-logs.store') }}" method="POST" id="createJobCardForm">
                @csrf
                
                <div class="form-wizard">
                    {{-- Customer Information Section --}}
                    <div class="form-section" data-section="customer">
                        <div class="section-header">
                            <div class="section-indicator">
                                <div class="indicator-number">1</div>
                                <div class="indicator-line"></div>
                            </div>
                            <div class="section-content">
                                <h6 class="section-title">
                                    <i class="fas fa-user me-2"></i>
                                    Customer Information
                                </h6>
                                <p class="section-description">Enter customer details and contact information</p>
                            </div>
                        </div>
                        
                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <div class="form-group enhanced">
                                    <label for="customer_name" class="form-label required">
                                        <i class="fas fa-user me-1"></i>
                                        Customer Name
                                    </label>
                                    <div class="input-wrapper">
                                        <input type="text" 
                                               class="form-control @error('customer_name') is-invalid @enderror"
                                               id="customer_name" 
                                               name="customer_name" 
                                               value="{{ old('customer_name') }}" 
                                               required
                                               placeholder="Enter customer name">
                                        <div class="input-icon">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    </div>
                                    @error('customer_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group enhanced">
                                    <label for="customer_email" class="form-label">
                                        <i class="fas fa-envelope me-1"></i>
                                        Customer Email
                                    </label>
                                    <div class="input-wrapper">
                                        <input type="email" 
                                               class="form-control @error('customer_email') is-invalid @enderror"
                                               id="customer_email" 
                                               name="customer_email" 
                                               value="{{ old('customer_email') }}"
                                               placeholder="customer@example.com">
                                        <div class="input-icon">
                                            <i class="fas fa-envelope"></i>
                                        </div>
                                    </div>
                                    @error('customer_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group enhanced">
                                    <label for="customer_phone" class="form-label">
                                        <i class="fas fa-phone me-1"></i>
                                        Customer Phone
                                    </label>
                                    <div class="input-wrapper">
                                        <input type="text" 
                                               class="form-control @error('customer_phone') is-invalid @enderror"
                                               id="customer_phone" 
                                               name="customer_phone" 
                                               value="{{ old('customer_phone') }}"
                                               placeholder="+263 77 123 4567">
                                        <div class="input-icon">
                                            <i class="fas fa-phone"></i>
                                        </div>
                                    </div>
                                    @error('customer_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group enhanced">
                                    <label for="company_name" class="form-label">
                                        <i class="fas fa-building me-1"></i>
                                        Company Name
                                    </label>
                                    <div class="input-wrapper">
                                        <input type="text" 
                                               class="form-control @error('company_name') is-invalid @enderror"
                                               id="company_name" 
                                               name="company_name" 
                                               value="{{ old('company_name') }}"
                                               placeholder="Enter company name">
                                        <div class="input-icon">
                                            <i class="fas fa-building"></i>
                                        </div>
                                    </div>
                                    @error('company_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Job Information Section --}}
                    <div class="form-section" data-section="job">
                        <div class="section-header">
                            <div class="section-indicator">
                                <div class="indicator-number">2</div>
                                <div class="indicator-line"></div>
                            </div>
                            <div class="section-content">
                                <h6 class="section-title">
                                    <i class="fas fa-clipboard-check me-2"></i>
                                    Job Information
                                </h6>
                                <p class="section-description">Specify job type and scheduling details</p>
                            </div>
                        </div>

                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <div class="form-group enhanced">
                                    <label for="type" class="form-label required">
                                        <i class="fas fa-tag me-1"></i>
                                        Job Type
                                    </label>
                                    <div class="select-wrapper">
                                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                            <option value="">Select job type...</option>
                                            <option value="normal" {{ old('type') == 'normal' ? 'selected' : '' }}>
                                                <i class="fas fa-clock"></i> Normal Job
                                            </option>
                                            <option value="emergency" {{ old('type') == 'emergency' ? 'selected' : '' }}>
                                                <i class="fas fa-exclamation-triangle"></i> Emergency Job
                                            </option>
                                        </select>
                                        <div class="select-icon">
                                            <i class="fas fa-chevron-down"></i>
                                        </div>
                                    </div>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group enhanced">
                                    <label for="date_booked" class="form-label required">
                                        <i class="fas fa-calendar me-1"></i>
                                        Date Booked
                                    </label>
                                    <div class="input-wrapper">
                                        <input type="date" 
                                               class="form-control @error('date_booked') is-invalid @enderror"
                                               id="date_booked" 
                                               name="date_booked" 
                                               value="{{ old('date_booked', now()->format('Y-m-d')) }}" 
                                               required>
                                        <div class="input-icon">
                                            <i class="fas fa-calendar"></i>
                                        </div>
                                    </div>
                                    @error('date_booked')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Fault Information Section --}}
                    <div class="form-section" data-section="fault">
                        <div class="section-header">
                            <div class="section-indicator">
                                <div class="indicator-number">3</div>
                                <div class="indicator-line"></div>
                            </div>
                            <div class="section-content">
                                <h6 class="section-title">
                                    <i class="fas fa-bug me-2"></i>
                                    Fault Information
                                </h6>
                                <p class="section-description">Describe the issue and provide reference details</p>
                            </div>
                        </div>

                        <div class="row g-3 mt-2">
                            <div class="col-12">
                                <div class="form-group enhanced">
                                    <label for="fault_description" class="form-label required">
                                        <i class="fas fa-file-text me-1"></i>
                                        Fault Description
                                    </label>
                                    <div class="textarea-wrapper">
                                        <textarea class="form-control @error('fault_description') is-invalid @enderror"
                                                  id="fault_description" 
                                                  name="fault_description" 
                                                  rows="4" 
                                                  required
                                                  placeholder="Describe the fault or issue in detail...">{{ old('fault_description') }}</textarea>
                                        <div class="textarea-footer">
                                            <span class="char-count">0/1000</span>
                                        </div>
                                    </div>
                                    @error('fault_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group enhanced">
                                    <label for="zimra_ref" class="form-label">
                                        <i class="fas fa-hashtag me-1"></i>
                                        ZIMRA Reference
                                    </label>
                                    <div class="input-wrapper">
                                        <input type="text" 
                                               class="form-control @error('zimra_ref') is-invalid @enderror"
                                               id="zimra_ref" 
                                               name="zimra_ref" 
                                               value="{{ old('zimra_ref') }}"
                                               placeholder="Enter ZIMRA reference">
                                        <div class="input-icon">
                                            <i class="fas fa-hashtag"></i>
                                        </div>
                                    </div>
                                    @error('zimra_ref')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group enhanced">
                                    <label for="amount_charged" class="form-label required">
                                        <i class="fas fa-dollar-sign me-1"></i>
                                        Amount Charged (USD)
                                    </label>
                                    <div class="input-group amount-input">
                                        <span class="input-group-text">
                                            <i class="fas fa-dollar-sign"></i>
                                        </span>
                                        <input type="number" 
                                               class="form-control @error('amount_charged') is-invalid @enderror"
                                               id="amount_charged" 
                                               name="amount_charged" 
                                               value="{{ old('amount_charged', '0.00') }}"
                                               step="0.01" 
                                               min="0" 
                                               max="999999.99"
                                               required
                                               placeholder="0.00">
                                    </div>
                                    <small class="form-text text-muted">Enter the amount to be charged for this job</small>
                                    @error('amount_charged')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Admin/Accounts Only Section --}}
                    @if(auth()->user()->isAdmin() || auth()->user()->isAccounts())
                    <div class="form-section" data-section="admin">
                        <div class="section-header">
                            <div class="section-indicator">
                                <div class="indicator-number">4</div>
                                <div class="indicator-line"></div>
                            </div>
                            <div class="section-content">
                                <h6 class="section-title">
                                    <i class="fas fa-user-shield me-2"></i>
                                    Administrative Information
                                </h6>
                                <p class="section-description">Administrative details and job assignment</p>
                            </div>
                        </div>

                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <div class="form-group enhanced">
                                    <label class="form-label">
                                        <i class="fas fa-user-check me-1"></i>
                                        Approved By
                                    </label>
                                    <div class="input-wrapper">
                                        <input type="text" 
                                               class="form-control" 
                                               value="{{ auth()->user()->name }}" 
                                               readonly>
                                        <div class="input-icon">
                                            <i class="fas fa-check-circle text-success"></i>
                                        </div>
                                    </div>
                                    <input type="hidden" name="approved_by" value="{{ auth()->id() }}">
                                    <input type="hidden" name="approved_by_name" value="{{ auth()->user()->name }}">
                                    <input type="hidden" name="booked_by" value="{{ auth()->user()->name }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group enhanced">
                                    <label for="assigned_to" class="form-label">
                                        <i class="fas fa-user-cog me-1"></i>
                                        Assign to Technician
                                    </label>
                                    <div class="select-wrapper">
                                        <select class="form-select @error('assigned_to') is-invalid @enderror" 
                                                id="assigned_to" 
                                                name="assigned_to">
                                            <option value="">Assign later...</option>
                                            @foreach($technicians ?? [] as $technician)
                                                @if(is_object($technician))
                                                    <option value="{{ $technician->id }}" {{ old('assigned_to') == $technician->id ? 'selected' : '' }}>
                                                        {{ $technician->name }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <div class="select-icon">
                                            <i class="fas fa-chevron-down"></i>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">Optional: Assign immediately or leave for later</small>
                                    @error('assigned_to')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Hidden Status Field --}}
                    <input type="hidden" name="status" value="pending">
                </div>

                {{-- Form Actions --}}
                <div class="form-actions">
                    <div class="d-flex gap-3 justify-content-end">
                        <button type="reset" class="btn btn-outline-secondary" id="resetBtn">
                            <i class="fas fa-undo me-2"></i>
                            Reset Form
                        </button>
                        <button type="submit" class="btn btn-success" id="submitBtn">
                            <i class="fas fa-save me-2"></i>
                            Create Job Card
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Enhanced Job Card Create Form Styles */
:root {
    --primary: #059669;
    --primary-dark: #047857;
    --success: #059669;
    --warning: #F59E0B;
    --danger: #DC2626;
    --secondary: #6B7280;
    --secondary-dark: #4B5563;
    --info: #0EA5E9;
    --white: #FFFFFF;
    --gray-50: #F9FAFB;
    --gray-100: #F3F4F6;
    --gray-200: #E5E7EB;
    --gray-300: #D1D5DB;
    --gray-400: #9CA3AF;
    --gray-500: #6B7280;
    --gray-600: #4B5563;
    --gray-700: #374151;
    --gray-800: #1F2937;
    --border-radius: 8px;
    --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    --transition: all 0.2s ease;
}

.dashboard-header {
    background: var(--white);
    border-radius: var(--border-radius);
    padding: 0.5rem 0.75rem;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--gray-200);
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.dashboard-title {
    font-size: 0.95rem;
    font-weight: 600;
    color: var(--gray-800);
    margin: 0;
    display: flex;
    align-items: center;
}

.header-meta {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-top: 0.125rem;
}

.header-meta .badge {
    font-size: 0.65rem;
    padding: 0.2rem 0.4rem;
    border-radius: 4px;
}

.bg-info {
    background: var(--info) !important;
    color: white;
}

.header-meta small {
    font-size: 0.75rem;
    color: var(--gray-500);
}

/* Progress Indicator */
.progress-indicator {
    background: var(--white);
    border-radius: var(--border-radius);
    padding: 0.75rem;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--gray-200);
}

.progress-bar {
    width: 100%;
    height: 6px;
    background: var(--gray-200);
    border-radius: 3px;
    overflow: hidden;
    margin-bottom: 0.5rem;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--success), var(--primary));
    transition: width 0.3s ease;
}

.progress-text {
    font-size: 0.75rem;
    margin: 0;
}

/* Content Card */
.content-card {
    background: var(--white);
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--gray-200);
}

.content-card-header {
    padding: 0.75rem 1rem;
    border-bottom: 1px solid var(--gray-200);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-title {
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--gray-800);
    margin: 0;
    display: flex;
    align-items: center;
}

.card-subtitle {
    color: var(--gray-500);
    font-size: 0.75rem;
    margin-top: 0.25rem;
}

.completion-badge {
    background: var(--gray-100);
    color: var(--gray-600);
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.7rem;
    font-weight: 600;
    border: 1px solid var(--gray-300);
}

/* Form Wizard */
.form-wizard {
    position: relative;
}

.form-section {
    margin-bottom: 2rem;
    position: relative;
}

.form-section:last-child {
    margin-bottom: 0;
}

/* Section Header */
.section-header {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.section-indicator {
    display: flex;
    flex-direction: column;
    align-items: center;
    flex-shrink: 0;
}

.indicator-number {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: var(--gray-200);
    color: var(--gray-600);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.85rem;
    transition: var(--transition);
}

.form-section.completed .indicator-number {
    background: var(--success);
    color: white;
}

.indicator-line {
    width: 2px;
    height: 40px;
    background: var(--gray-200);
    margin-top: 0.5rem;
    transition: var(--transition);
}

.form-section:last-child .indicator-line {
    display: none;
}

.form-section.completed .indicator-line {
    background: var(--success);
}

.section-content {
    flex: 1;
    min-width: 0;
}

.section-title {
    color: var(--gray-700);
    font-weight: 600;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    margin: 0 0 0.25rem 0;
}

.section-title i {
    color: var(--secondary);
}

.section-description {
    color: var(--gray-500);
    font-size: 0.75rem;
    margin: 0;
    line-height: 1.4;
}

/* Enhanced Form Groups */
.form-group.enhanced {
    margin-bottom: 1.25rem;
}

.form-label {
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    font-size: 0.875rem;
}

.form-label.required::after {
    content: ' *';
    color: var(--danger);
}

.form-label i {
    color: var(--secondary);
    width: 16px;
}

/* Enhanced Input Wrapper */
.input-wrapper {
    position: relative;
}

.input-wrapper .form-control {
    padding-left: 2.75rem;
    border: 1px solid var(--gray-300);
    border-radius: var(--border-radius);
    font-size: 0.875rem;
    transition: var(--transition);
    background: var(--white);
}

.input-wrapper .form-control:focus {
    border-color: var(--secondary);
    box-shadow: 0 0 0 2px rgba(107, 114, 128, 0.1);
    outline: none;
}

.input-wrapper .form-control:focus + .input-icon i {
    color: var(--secondary);
}

.input-icon {
    position: absolute;
    left: 0.75rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--gray-400);
    pointer-events: none;
    transition: var(--transition);
}

/* Select Wrapper */
.select-wrapper {
    position: relative;
}

.select-wrapper .form-select {
    appearance: none;
    padding-right: 2.5rem;
    border: 1px solid var(--gray-300);
    border-radius: var(--border-radius);
    font-size: 0.875rem;
    transition: var(--transition);
    background: var(--white);
}

.select-wrapper .form-select:focus {
    border-color: var(--secondary);
    box-shadow: 0 0 0 2px rgba(107, 114, 128, 0.1);
    outline: none;
}

.select-icon {
    position: absolute;
    right: 0.75rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--gray-400);
    pointer-events: none;
    transition: var(--transition);
}

.select-wrapper .form-select:focus + .select-icon i {
    color: var(--secondary);
}

/* Textarea Wrapper */
.textarea-wrapper {
    position: relative;
}

.textarea-wrapper .form-control {
    border: 1px solid var(--gray-300);
    border-radius: var(--border-radius);
    font-size: 0.875rem;
    transition: var(--transition);
    background: var(--white);
    resize: vertical;
    min-height: 100px;
}

.textarea-wrapper .form-control:focus {
    border-color: var(--secondary);
    box-shadow: 0 0 0 2px rgba(107, 114, 128, 0.1);
    outline: none;
}

.textarea-footer {
    display: flex;
    justify-content: flex-end;
    margin-top: 0.5rem;
}

.char-count {
    font-size: 0.75rem;
    color: var(--gray-500);
    background: var(--gray-100);
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
}

/* Amount Input */
.amount-input .input-group-text {
    background: var(--gray-50);
    border: 1px solid var(--gray-300);
    color: var(--gray-600);
    font-weight: 500;
}

.amount-input .form-control {
    border-left: none;
}

.amount-input .form-control:focus {
    border-color: var(--secondary);
    box-shadow: none;
}

.amount-input .form-control:focus ~ .input-group-text {
    border-color: var(--secondary);
}

/* Job Type Styling */
#type option[value="emergency"] {
    color: var(--danger);
    font-weight: 600;
}

#type option[value="normal"] {
    color: var(--success);
    font-weight: 600;
}

#type.emergency-selected {
    color: var(--danger) !important;
    font-weight: 600;
}

#type.normal-selected {
    color: var(--success) !important;
    font-weight: 600;
}

/* Form Validation */
.form-control.is-invalid,
.form-select.is-invalid {
    border-color: var(--danger);
}

.form-control.is-invalid:focus,
.form-select.is-invalid:focus {
    border-color: var(--danger);
    box-shadow: 0 0 0 2px rgba(220, 38, 38, 0.1);
}

.invalid-feedback {
    color: var(--danger);
    font-size: 0.75rem;
    margin-top: 0.25rem;
}

/* Readonly Fields */
.form-control[readonly] {
    background-color: var(--gray-50);
    color: var(--gray-600);
}

/* Form Actions */
.form-actions {
    background: linear-gradient(135deg, var(--gray-50) 0%, var(--white) 100%);
    margin: 1.5rem -1.5rem -1.5rem -1.5rem;
    padding: 1.5rem;
    border-radius: 0 0 var(--border-radius) var(--border-radius);
    border-top: 1px solid var(--gray-200);
}

/* Button Styles */
.btn-secondary {
    background: var(--secondary);
    border-color: var(--secondary);
    color: white;
}

.btn-secondary:hover {
    background: var(--secondary-dark);
    border-color: var(--secondary-dark);
    color: white;
}

.btn-success {
    background: var(--success);
    border-color: var(--success);
    color: white;
}

.btn-success:hover {
    background: var(--primary-dark);
    border-color: var(--primary-dark);
    color: white;
}

.btn-outline-secondary {
    color: var(--secondary);
    border-color: var(--secondary);
}

.btn-outline-secondary:hover {
    background: var(--secondary);
    border-color: var(--secondary);
    color: white;
}

/* Alert Styles */
.alert {
    border-radius: var(--border-radius);
    padding: 0.75rem 1rem;
    font-size: 0.85rem;
}

.alert-danger {
    background: #FEF2F2;
    color: #DC2626;
    border: 1px solid #FECACA;
}

/* Responsive */
@media (max-width: 768px) {
    .dashboard-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
        padding: 0.5rem;
    }
    
    .header-actions {
        width: 100%;
        display: flex;
        justify-content: flex-end;
    }
    
    .section-header {
        gap: 0.75rem;
    }
    
    .input-wrapper .form-control {
        padding-left: 0.75rem;
    }
    
    .input-icon {
        display: none;
    }
    
    .form-actions .d-flex {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .form-actions .btn {
        width: 100%;
    }
}

/* Animation */
@keyframes slideInFromLeft {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.form-section {
    animation: slideInFromLeft 0.3s ease-out;
}

.form-section:nth-child(2) {
    animation-delay: 0.1s;
}

.form-section:nth-child(3) {
    animation-delay: 0.2s;
}

.form-section:nth-child(4) {
    animation-delay: 0.3s;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('createJobCardForm');
    const submitBtn = document.getElementById('submitBtn');
    const resetBtn = document.getElementById('resetBtn');
    const progressFill = document.querySelector('.progress-fill');
    const progressText = document.querySelector('.progress-text');
    const completionCount = document.querySelector('.completion-count');
    
    let totalSections = 4;
    let completedSections = 0;
    
    // Check for success message and show toastr
    @if(session('success'))
        toastr.success("{{ session('success') }}", "Success!", {
            closeButton: true,
            progressBar: true,
            timeOut: 5000,
            extendedTimeOut: 2000,
            positionClass: 'toast-top-right'
        });
    @endif
    
    // Update progress function
    function updateProgress() {
        const percentage = (completedSections / totalSections) * 100;
        progressFill.style.width = percentage + '%';
        progressText.textContent = `${Math.round(percentage)}% complete - ${completedSections}/${totalSections} sections`;
        completionCount.textContent = `${completedSections}/${totalSections}`;
        
        if (percentage === 100) {
            progressText.textContent = 'Ready to create job card!';
            progressFill.style.background = 'linear-gradient(90deg, var(--success), var(--primary))';
        }
    }
    
    // Check section completion
    function checkSectionCompletion(section) {
        const sectionElement = document.querySelector(`[data-section="${section}"]`);
        const requiredFields = sectionElement.querySelectorAll('input[required], select[required], textarea[required]');
        const completedFields = Array.from(requiredFields).filter(field => field.value.trim() !== '');
        
        const isCompleted = completedFields.length === requiredFields.length;
        
        if (isCompleted) {
            sectionElement.classList.add('completed');
        } else {
            sectionElement.classList.remove('completed');
        }
        
        return isCompleted;
    }
    
    // Update all sections
    function updateAllSections() {
        completedSections = 0;
        
        // Check customer section
        if (checkSectionCompletion('customer')) completedSections++;
        
        // Check job section
        if (checkSectionCompletion('job')) completedSections++;
        
        // Check fault section
        if (checkSectionCompletion('fault')) completedSections++;
        
        // Check admin section (if visible)
        const adminSection = document.querySelector('[data-section="admin"]');
        if (adminSection) {
            totalSections = 4;
            if (checkSectionCompletion('admin')) completedSections++;
        } else {
            totalSections = 3;
        }
        
        updateProgress();
    }
    
    // Handle form submission with loading state
    if (form && submitBtn) {
        form.addEventListener('submit', function(e) {
            const originalContent = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Creating...';
            submitBtn.disabled = true;
            
            // Re-enable if form validation fails (client-side)
            setTimeout(() => {
                if (submitBtn.disabled) {
                    submitBtn.innerHTML = originalContent;
                    submitBtn.disabled = false;
                }
            }, 3000);
        });
    }
    
    // Handle reset button
    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            setTimeout(() => {
                // Clear validation errors
                document.querySelectorAll('.is-invalid').forEach(element => {
                    element.classList.remove('is-invalid');
                });
                
                // Reset date to current date
                const dateField = document.getElementById('date_booked');
                if (dateField) {
                    dateField.value = new Date().toISOString().split('T')[0];
                }
                
                // Reset amount to 0.00
                const amountField = document.getElementById('amount_charged');
                if (amountField) {
                    amountField.value = '0.00';
                }
                
                // Reset job type styling
                const typeSelect = document.getElementById('type');
                if (typeSelect) {
                    typeSelect.className = 'form-select';
                }
                
                updateAllSections();
                
                toastr.info('Form has been reset.', 'Reset', {
                    closeButton: true,
                    progressBar: true,
                    timeOut: 2000,
                    positionClass: 'toast-top-right'
                });
            }, 100);
        });
    }
    
    // Auto-focus first input
    const firstInput = document.getElementById('customer_name');
    if (firstInput) {
        firstInput.focus();
    }
    
    // Format phone number input
    const phoneInput = document.getElementById('customer_phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/[^\d\+\s\-\(\)]/g, '');
            e.target.value = value;
        });
    }
    
    // Format amount input with proper decimal places
    const amountInput = document.getElementById('amount_charged');
    if (amountInput) {
        amountInput.addEventListener('blur', function(e) {
            if (e.target.value && !isNaN(e.target.value)) {
                e.target.value = parseFloat(e.target.value).toFixed(2);
            }
        });
        
        // Prevent negative values
        amountInput.addEventListener('input', function(e) {
            if (parseFloat(e.target.value) < 0) {
                e.target.value = '0.00';
            }
        });
    }
    
    // Job type change handler
    const typeSelect = document.getElementById('type');
    if (typeSelect) {
        typeSelect.addEventListener('change', function(e) {
            // Remove existing classes
            e.target.classList.remove('emergency-selected', 'normal-selected');
            
            if (e.target.value === 'emergency') {
                e.target.classList.add('emergency-selected');
                
                // Auto-adjust amount for emergency jobs
                const amountField = document.getElementById('amount_charged');
                if (amountField && parseFloat(amountField.value) === 0) {
                    amountField.value = '50.00';
                    amountField.focus();
                    amountField.select();
                }
            } else if (e.target.value === 'normal') {
                e.target.classList.add('normal-selected');
            }
            
            updateAllSections();
        });
    }
    
    // Character counter for fault description
    const faultTextarea = document.getElementById('fault_description');
    const charCount = document.querySelector('.char-count');
    
    if (faultTextarea && charCount) {
        const maxLength = 1000;
        
        function updateCharCount() {
            const length = faultTextarea.value.length;
            charCount.textContent = `${length}/${maxLength}`;
            
            if (length > maxLength * 0.9) {
                charCount.style.color = '#DC2626';
                charCount.style.background = '#FEF2F2';
            } else if (length > maxLength * 0.7) {
                charCount.style.color = '#D97706';
                charCount.style.background = '#FFFBEB';
            } else {
                charCount.style.color = '#6B7280';
                charCount.style.background = '#F3F4F6';
            }
        }
        
        faultTextarea.addEventListener('input', function() {
            updateCharCount();
            updateAllSections();
        });
        
        faultTextarea.setAttribute('maxlength', maxLength);
        updateCharCount();
    }
    
    // Clear validation errors on input
    document.querySelectorAll('.form-control, .form-select').forEach(input => {
        input.addEventListener('input', function() {
            this.classList.remove('is-invalid');
            updateAllSections();
        });
        
        input.addEventListener('change', function() {
            updateAllSections();
        });
    });
    
    // Initial progress update
    updateAllSections();
    
    // Smooth scrolling for long forms
    function scrollToFirstError() {
        const firstError = document.querySelector('.is-invalid');
        if (firstError) {
            firstError.scrollIntoView({ 
                behavior: 'smooth', 
                block: 'center' 
            });
            firstError.focus();
        }
    }
    
    // Enhanced form validation feedback
    form.addEventListener('invalid', scrollToFirstError, true);
});
</script>
@endpush

