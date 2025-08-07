@extends('layouts.app')

@section('title', 'Create New Job Card')

@section('content')
<div class="dashboard-container">
    {{-- Compact Header --}}
    <div class="dashboard-header mb-2">
        <div class="header-content">
            <h1 class="dashboard-title">
                <i class="fas fa-plus-circle me-2"></i>
                Create New Job Card
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
            <button onclick="window.location.href='{{ route('admin.call-logs.all') }}'" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>
                Back to Jobs
            </button>
        </div>
    </div>

    {{-- Minimized Progress Indicator --}}
    <div class="progress-indicator mb-2">
        <div class="progress-header">
            <span class="completion-badge">
                <i class="fas fa-check-circle me-1"></i>
                <span class="completion-count">0/4</span> Complete
            </span>
        </div>
        <div class="progress-bar">
            <div class="progress-fill" style="width: 0%"></div>
        </div>
    </div>

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-2">
            <div class="alert-header">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Please fix the following errors:</strong>
            </div>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Compact Job Card Form --}}
    <div class="content-card">
        <div class="content-card-header">
            <div class="header-content">
                <h4 class="card-title">
                    <i class="fas fa-clipboard-list me-2"></i>
                    Job Card Information
                </h4>
            </div>
        </div>
        
        <div class="content-card-body" style="padding: 1rem;">
            <form action="{{ route('admin.call-logs.store') }}" method="POST" id="createJobCardForm" novalidate>
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
                            </div>
                        </div>
                        
                        <div class="row g-2 mt-1">
                            <div class="col-md-6">
                                <div class="form-group compact">
                                    <label for="customer_name" class="form-label required">
                                        Customer Name
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('customer_name') is-invalid @enderror"
                                           id="customer_name" 
                                           name="customer_name" 
                                           value="{{ old('customer_name') }}" 
                                           required
                                           placeholder="Enter customer full name">
                                    @error('customer_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group compact">
                                    <label for="customer_email" class="form-label required">
                                        Customer Email
                                    </label>
                                    <input type="email" 
                                           class="form-control @error('customer_email') is-invalid @enderror"
                                           id="customer_email" 
                                           name="customer_email" 
                                           value="{{ old('customer_email') }}"
                                           required
                                           placeholder="customer@example.com">
                                    @error('customer_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group compact">
                                    <label for="customer_phone" class="form-label">
                                        Customer Phone
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('customer_phone') is-invalid @enderror"
                                           id="customer_phone" 
                                           name="customer_phone" 
                                           value="{{ old('customer_phone') }}"
                                           placeholder="+263 77 123 4567">
                                    @error('customer_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group compact">
                                    <label for="customer_address" class="form-label">
                                        Customer Address
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('customer_address') is-invalid @enderror"
                                           id="customer_address" 
                                           name="customer_address" 
                                           value="{{ old('customer_address') }}"
                                           placeholder="Enter customer address">
                                    @error('customer_address')
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
                            </div>
                        </div>

                        <div class="row g-2 mt-1">
                            <div class="col-md-6">
                                <div class="form-group compact">
                                    <label for="type" class="form-label required">
                                        Job Type
                                    </label>
                                    <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                        <option value="">Select job type...</option>
                                        <option value="normal" {{ old('type') == 'normal' ? 'selected' : '' }}>
                                            Normal Job
                                        </option>
                                        <option value="emergency" {{ old('type') == 'emergency' ? 'selected' : '' }}>
                                            Emergency Job
                                        </option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group compact">
                                    <label for="date_booked" class="form-label required">
                                        Date Booked
                                    </label>
                                    <input type="date" 
                                           class="form-control @error('date_booked') is-invalid @enderror"
                                           id="date_booked" 
                                           name="date_booked" 
                                           value="{{ old('date_booked', now()->format('Y-m-d')) }}" 
                                           required>
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
                            </div>
                        </div>

                        <div class="row g-2 mt-1">
                            <div class="col-12">
                                <div class="form-group compact">
                                    <label for="fault_description" class="form-label required">
                                        Fault Description
                                    </label>
                                    <textarea class="form-control @error('fault_description') is-invalid @enderror"
                                              id="fault_description" 
                                              name="fault_description" 
                                              rows="3" 
                                              required
                                              maxlength="1000"
                                              placeholder="Describe the fault or issue in detail...">{{ old('fault_description') }}</textarea>
                                    <div class="char-count-inline">
                                        <span class="char-count">0/1000</span>
                                    </div>
                                    @error('fault_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group compact">
                                    <label for="zimra_ref" class="form-label">
                                        ZIMRA Reference
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('zimra_ref') is-invalid @enderror"
                                           id="zimra_ref" 
                                           name="zimra_ref" 
                                           value="{{ old('zimra_ref') }}"
                                           placeholder="Enter ZIMRA reference number">
                                    @error('zimra_ref')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Billing Information Section --}}
                    <div class="form-section" data-section="billing">
                        <div class="section-header">
                            <div class="section-indicator">
                                <div class="indicator-number">4</div>
                            </div>
                            <div class="section-content">
                                <h6 class="section-title">
                                    <i class="fas fa-dollar-sign me-2"></i>
                                    Billing Information
                                </h6>
                            </div>
                        </div>

                        <div class="row g-2 mt-1">
                            <div class="col-md-6">
                                <div class="form-group compact">
                                    <label for="currency" class="form-label required">
                                        Currency
                                    </label>
                                    <select class="form-select @error('currency') is-invalid @enderror" id="currency" name="currency" required>
                                        <option value="">Select currency...</option>
                                        <option value="USD" {{ old('currency', 'USD') == 'USD' ? 'selected' : '' }}>
                                            USD
                                        </option>
                                        <option value="ZWG" {{ old('currency') == 'ZWG' ? 'selected' : '' }}>
                                            ZWG
                                        </option>
                                    </select>
                                    @error('currency')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group compact">
                                    <label for="amount_charged" class="form-label required">
                                        Amount Charged
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text currency-symbol">
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
                                    @error('amount_charged')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Hidden Fields --}}
                    <input type="hidden" name="status" value="pending">
                </div>

                {{-- Compact Form Actions --}}
                <div class="form-actions">
                    <div class="action-buttons">
                        <button type="reset" class="btn btn-outline-secondary btn-sm" id="resetBtn">
                            <i class="fas fa-undo me-1"></i>
                            Reset
                        </button>
                        <button type="submit" class="btn btn-success btn-sm" id="submitBtn">
                            <i class="fas fa-save me-1"></i>
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
/* Exact original CSS you provided */
:root {
    --primary: #059669;
    --success: #059669;
    --danger: #DC2626;
    --secondary: #6B7280;
    --info: #0EA5E9;
    --white: #FFFFFF;
    --gray-50: #F9FAFB;
    --gray-100: #F3F4F6;
    --gray-200: #E5E7EB;
    --gray-300: #D1D5DB;
    --gray-400: #9CA3AF;
    --gray-500: #6B7280;
    --gray-700: #374151;
    --gray-800: #1F2937;
    --border-radius: 6px;
    --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.05);
    --transition: all 0.2s ease;
}

/* Compact Dashboard Header */
.dashboard-header {
    background: var(--white);
    border-radius: var(--border-radius);
    padding: 0.5rem 0.75rem;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--gray-200);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.dashboard-title {
    font-size: 1rem;
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

/* Minimized Progress Indicator */
.progress-indicator {
    background: var(--white);
    border-radius: var(--border-radius);
    padding: 0.5rem 0.75rem;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--gray-200);
}

.progress-header {
    display: flex;
    justify-content: flex-end;
    margin-bottom: 0.5rem;
}

.completion-badge {
    background: var(--gray-100);
    color: var(--gray-600);
    padding: 0.2rem 0.5rem;
    border-radius: 4px;
    font-size: 0.7rem;
    font-weight: 600;
    border: 1px solid var(--gray-300);
}

.progress-bar {
    width: 100%;
    height: 4px;
    background: var(--gray-200);
    border-radius: 2px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--success), var(--primary));
    transition: width 0.3s ease;
}

/* Compact Content Card */
.content-card {
    background: var(--white);
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--gray-200);
}

.content-card-header {
    padding: 0.75rem 1rem;
    border-bottom: 1px solid var(--gray-200);
}

.card-title {
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--gray-800);
    margin: 0;
    display: flex;
    align-items: center;
}

/* Compact Form Sections */
.form-section {
    margin-bottom: 1.5rem;
    position: relative;
}

.form-section:last-child {
    margin-bottom: 0;
}

.section-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1rem;
}

.section-indicator {
    display: flex;
    flex-direction: column;
    align-items: center;
    flex-shrink: 0;
}

.indicator-number {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: var(--gray-200);
    color: var(--gray-600);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.8rem;
    border: 2px solid var(--gray-300);
    transition: var(--transition);
}

.form-section.completed .indicator-number {
    background: var(--success);
    color: white;
    border-color: var(--success);
}

.indicator-line {
    width: 2px;
    height: 30px;
    background: var(--gray-200);
    margin-top: 0.25rem;
    transition: var(--transition);
}

.form-section:last-child .indicator-line {
    display: none;
}

.form-section.completed .indicator-line {
    background: var(--success);
}

.section-title {
    color: var(--gray-700);
    font-weight: 600;
    font-size: 0.85rem;
    display: flex;
    align-items: center;
    margin: 0;
}

/* Compact Form Groups */
.form-group.compact {
    margin-bottom: 0.75rem;
}

.form-label {
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 0.25rem;
    display: block;
    font-size: 0.8rem;
}

.form-label.required::after {
    content: ' *';
    color: var(--danger);
}

/* Compact Form Controls */
.form-control,
.form-select {
    border: 1px solid var(--gray-300);
    border-radius: var(--border-radius);
    font-size: 0.8rem;
    transition: var(--transition);
    background: var(--white);
    height: 34px;
    padding: 0.375rem 0.5rem;
}

.form-control:focus,
.form-select:focus {
    border-color: var(--secondary);
    box-shadow: 0 0 0 2px rgba(107, 114, 128, 0.1);
    outline: none;
}

textarea.form-control {
    height: auto;
    resize: vertical;
    min-height: 80px;
}

/* Compact Input Group */
.input-group-text {
    background: var(--gray-50);
    border: 1px solid var(--gray-300);
    color: var(--gray-600);
    font-weight: 500;
    font-size: 0.8rem;
    padding: 0.375rem 0.5rem;
}

.currency-symbol {
    min-width: 40px;
    justify-content: center;
}

/* Inline Character Count */
.char-count-inline {
    text-align: right;
    margin-top: 0.25rem;
}

.char-count {
    font-size: 0.7rem;
    color: var(--gray-500);
    background: var(--gray-100);
    padding: 0.125rem 0.375rem;
    border-radius: 3px;
}

/* Compact Form Actions */
.form-actions {
    background: var(--gray-50);
    margin: 1rem -1rem -1rem -1rem;
    padding: 0.75rem;
    border-radius: 0 0 var(--border-radius) var(--border-radius);
    border-top: 1px solid var(--gray-200);
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
    justify-content: flex-end;
}

/* Compact Button Styles */
.btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.8rem;
    font-weight: 500;
    border-radius: var(--border-radius);
}

.btn-success {
    background: var(--success);
    border-color: var(--success);
    color: white;
}

.btn-outline-secondary {
    color: var(--secondary);
    border-color: var(--secondary);
}

.btn-outline-secondary:hover {
    background: var(--secondary);
    color: white;
}

/* Alert Styles */
.alert {
    border-radius: var(--border-radius);
    padding: 0.5rem 0.75rem;
    font-size: 0.8rem;
    border: none;
}

.alert-danger {
    background: #FEF2F2;
    color: #DC2626;
    border-left: 3px solid #DC2626;
}

.alert-header {
    display: flex;
    align-items: center;
    margin-bottom: 0.25rem;
}

/* Validation */
.form-control.is-invalid,
.form-select.is-invalid {
    border-color: var(--danger);
}

.invalid-feedback {
    color: var(--danger);
    font-size: 0.7rem;
    margin-top: 0.25rem;
}

/* Job Type Styling */
#type.emergency-selected {
    color: var(--danger) !important;
    font-weight: 600;
}

/* Currency Styling */
#currency.usd-selected {
    color: var(--success) !important;
    font-weight: 600;
}

#currency.zwg-selected {
    color: #F59E0B !important;
    font-weight: 600;
}

/* Responsive */
@media (max-width: 768px) {
    .dashboard-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
        padding: 0.5rem;
    }
    
    .section-header {
        gap: 0.5rem;
    }
    
    .action-buttons {
        flex-direction: column;
        gap: 0.25rem;
    }
    
    .action-buttons .btn {
        width: 100%;
    }
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
    const completionCount = document.querySelector('.completion-count');
    const currencySelect = document.getElementById('currency');
    const currencySymbol = document.querySelector('.currency-symbol');
    
    const totalSections = 4;
    let completedSections = 0;
    
    // Update currency symbol
    function updateCurrencySymbol(currency) {
        if (currencySymbol) {
            currencySymbol.innerHTML = currency === 'ZWG' ? 'ZWG' : '<i class="fas fa-dollar-sign"></i>';
        }
    }
    
    // Update progress
    function updateProgress() {
        completedSections = 0;

        ['customer', 'job', 'fault', 'billing'].forEach(section => {
            const elem = document.querySelector(`[data-section="${section}"]`);
            if (!elem) return;

            const requiredFields = elem.querySelectorAll('input[required], select[required], textarea[required]');
            const allFilled = Array.from(requiredFields).every(field => field.value.trim() !== '');
            
            if (allFilled) {
                elem.classList.add('completed');
                completedSections++;
            } else {
                elem.classList.remove('completed');
            }
        });

        const percentage = (completedSections / totalSections) * 100;
        progressFill.style.width = percentage + '%';
        completionCount.textContent = `${completedSections}/${totalSections}`;
    }
    
    // Event listeners
    document.querySelectorAll('input, select, textarea').forEach(field => {
        field.addEventListener('input', updateProgress);
        field.addEventListener('change', updateProgress);
    });

    // Currency handling
    if (currencySelect) {
        currencySelect.addEventListener('change', function() {
            this.classList.remove('usd-selected', 'zwg-selected');
            if (this.value === 'USD') this.classList.add('usd-selected');
            else if (this.value === 'ZWG') this.classList.add('zwg-selected');
            
            updateCurrencySymbol(this.value);
            updateProgress();
        });
        updateCurrencySymbol(currencySelect.value);
    }

    // Amount input formatting
    const amountInput = document.getElementById('amount_charged');
    if (amountInput) {
        amountInput.addEventListener('blur', function() {
            if (this.value && !isNaN(this.value)) {
                this.value = parseFloat(this.value).toFixed(2);
            }
        });
    }

    // Job type handling
    const typeSelect = document.getElementById('type');
    if (typeSelect) {
        typeSelect.addEventListener('change', function() {
            this.classList.remove('emergency-selected');
            if (this.value === 'emergency') {
                this.classList.add('emergency-selected');
                
                // Auto-price emergency jobs
                if (amountInput && parseFloat(amountInput.value) === 0) {
                    const currency = currencySelect ? currencySelect.value : 'USD';
                    amountInput.value = currency === 'USD' ? '100.00' : '2000.00';
                }
            }
            updateProgress();
        });
    }

    // Character counter
    const faultTextarea = document.getElementById('fault_description');
    const charCount = document.querySelector('.char-count');
    if (faultTextarea && charCount) {
        function updateCharCount() {
            const length = faultTextarea.value.length;
            charCount.textContent = `${length}/1000`;
            
            if (length > 900) {
                charCount.style.color = '#DC2626';
                charCount.style.background = '#FEF2F2';
            } else if (length > 700) {
                charCount.style.color = '#D97706';
                charCount.style.background = '#FFFBEB';
            } else {
                charCount.style.color = '#6B7280';
                charCount.style.background = '#F3F4F6';
            }
        }
        
        faultTextarea.addEventListener('input', updateCharCount);
        updateCharCount();
    }

    // Form submission
    if (form && submitBtn) {
        form.addEventListener('submit', function() {
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Creating...';
            submitBtn.disabled = true;
        });
    }

    // Reset functionality
    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            setTimeout(() => {
                form.querySelectorAll('.is-invalid').forEach(e => e.classList.remove('is-invalid'));
                
                const today = new Date().toISOString().split('T')[0];
                const dateBooked = document.getElementById('date_booked');
                if (dateBooked) dateBooked.value = today;
                
                if (currencySelect) {
                    currencySelect.value = 'USD';
                    currencySelect.className = 'form-select usd-selected';
                    updateCurrencySymbol('USD');
                }
                
                if (amountInput) amountInput.value = '0.00';
                
                updateProgress();
            }, 100);
        });
    }

    // Initialize
    updateProgress();
});
</script>
@endpush
