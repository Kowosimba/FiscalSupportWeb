@extends('layouts.app')

@section('title', 'Edit Job Card - ' . ($callLog->job_card ?? 'TBD-' . $callLog->id))

@section('content')
<div class="dashboard-container">
    {{-- Header --}}
    <div class="dashboard-header mb-2">
        <div class="header-content">
            <h1 class="dashboard-title">
                <i class="fas fa-edit me-2"></i>
                Edit Job Card
            </h1>
            <div class="header-meta">
                <span class="badge bg-secondary me-2">
                    <i class="fas fa-file-text me-1"></i>
                    {{ $callLog->job_card ?? 'TBD-' . $callLog->id }}
                </span>
                <small class="text-muted">Modify job card details and status</small>
            </div>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.call-logs.show', $callLog) }}" class="btn btn-sm btn-outline-info me-2">
                <i class="fas fa-eye me-1"></i>
                View Job
            </a>
            <a href="{{ route('admin.call-logs.all') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>
                Back to Jobs
            </a>
        </div>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-2">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-2">
            <i class="fas fa-exclamation-triangle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

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

    {{-- Form Card --}}
    <div class="content-card">
        <div class="content-card-header">
            <div class="header-content">
                <h4 class="card-title">
                    <i class="fas fa-clipboard-list me-2"></i>
                    Edit Job Card Details
                </h4>
                <p class="card-subtitle mb-0">Update job information and status</p>
            </div>
            <div class="header-actions">
                <span class="status-badge {{ strtolower(str_replace('_', '-', $callLog->status)) }}">
                    <i class="fas fa-flag me-1"></i>
                    {{ ucfirst(str_replace('_', ' ', $callLog->status)) }}
                </span>
            </div>
        </div>
        
        <div class="content-card-body" style="padding: 1.5rem;">
            <form action="{{ route('admin.call-logs.update', $callLog) }}" method="POST" id="editJobCardForm">
                @csrf
                @method('PUT')
                
                @php
                    $isEngineer = $callLog->assigned_to === auth()->id() && in_array(auth()->user()->role, ['technician', 'manager']);
                    $isAdmin = in_array(auth()->user()->role, ['admin', 'accounts']);
                @endphp

                @if($isEngineer && !$isAdmin)
                    {{-- Engineer View - Limited Fields --}}
                    <div class="form-wizard">
                        {{-- Read-only Customer Info --}}
                        <div class="form-section mb-4">
                            <h6 class="section-title">
                                <i class="fas fa-info-circle me-2"></i>
                                Job Information (Read Only)
                            </h6>
                            
                            <div class="row g-3 mt-2">
                                <div class="col-md-6">
                                    <label class="form-label">Customer Name</label>
                                    <input type="text" class="form-control" value="{{ $callLog->customer_name }}" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Customer Email</label>
                                    <input type="text" class="form-control" value="{{ $callLog->customer_email ?? 'N/A' }}" readonly>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Fault Description</label>
                                    <textarea class="form-control" rows="3" readonly>{{ $callLog->fault_description }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Amount Charged</label>
                                    <input type="text" class="form-control" value="@if(($callLog->currency ?? 'USD') === 'ZWG')ZWG {{ number_format($callLog->amount_charged ?? 0) }}@else${{ number_format($callLog->amount_charged ?? 0, 2) }}@endif" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Date Booked</label>
                                    <input type="text" class="form-control" value="{{ $callLog->date_booked ? $callLog->date_booked->format('Y-m-d') : 'N/A' }}" readonly>
                                </div>
                            </div>
                        </div>

                        {{-- Engineer Updates --}}
                        <div class="form-section">
                            <h6 class="section-title">
                                <i class="fas fa-user-cog me-2"></i>
                                Engineer Updates
                            </h6>
                            
                            <div class="row g-3 mt-2">
                                <div class="col-md-6">
                                    <label for="job_card" class="form-label">
                                        Job Card Number
                                        <span class="text-danger" id="job_card_required" style="display: none;">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('job_card') is-invalid @enderror"
                                           id="job_card" 
                                           name="job_card" 
                                           value="{{ old('job_card', $callLog->job_card) }}"
                                           placeholder="Enter job card number">
                                    @error('job_card')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="status" class="form-label">Job Status *</label>
                                    <select class="form-select @error('status') is-invalid @enderror" 
                                            id="status" name="status" required>
                                        <option value="pending" @selected(old('status', $callLog->status) == 'pending')>Pending</option>
                                        <option value="assigned" @selected(old('status', $callLog->status) == 'assigned')>Assigned</option>
                                        <option value="in_progress" @selected(old('status', $callLog->status) == 'in_progress')>In Progress</option>
                                        <option value="complete" @selected(old('status', $callLog->status) == 'complete')>Complete</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="time_start" class="form-label">
                                        Start Time
                                        <span class="text-danger" id="time_start_required" style="display: none;">*</span>
                                    </label>
                                    <input type="time" 
                                           class="form-control @error('time_start') is-invalid @enderror" 
                                           id="time_start" 
                                           name="time_start" 
                                           value="{{ old('time_start', $callLog->time_start) }}">
                                    @error('time_start')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="time_finish" class="form-label">
                                        Finish Time
                                        <span class="text-danger" id="time_finish_required" style="display: none;">*</span>
                                    </label>
                                    <input type="time" 
                                           class="form-control @error('time_finish') is-invalid @enderror" 
                                           id="time_finish" 
                                           name="time_finish" 
                                           value="{{ old('time_finish', $callLog->time_finish) }}">
                                    @error('time_finish')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="billed_hours" class="form-label">
                                        Billed Hours
                                        <span class="text-danger" id="billed_hours_required" style="display: none;">*</span>
                                    </label>
                                    
                                    {{-- Quick Selection Buttons --}}
                                    <div class="btn-group mb-2" role="group">
                                        <button type="button" class="btn btn-outline-secondary btn-sm quick-hours" data-value="10%">10%</button>
                                        <button type="button" class="btn btn-outline-secondary btn-sm quick-hours" data-value="1">1 Hour</button>
                                        <button type="button" class="btn btn-outline-secondary btn-sm quick-hours" data-value="2">2 Hours</button>
                                        <button type="button" class="btn btn-outline-info btn-sm" id="calculateHours">
                                            <i class="fas fa-calculator me-1"></i> Calculate
                                        </button>
                                    </div>
                                    
                                    <input type="text" 
                                           class="form-control @error('billed_hours') is-invalid @enderror" 
                                           id="billed_hours" 
                                           name="billed_hours" 
                                           value="{{ old('billed_hours', $callLog->billed_hours) }}"
                                           placeholder="e.g., 10%, 1, 2">
                                    
                                    <small class="form-text text-muted">
                                        Common values: <strong>10%</strong> (percentage), <strong>1</strong> or <strong>2</strong> (hours)
                                    </small>
                                    @error('billed_hours')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="date_resolved" class="form-label">
                                        Date Resolved
                                        <span class="text-danger" id="date_resolved_required" style="display: none;">*</span>
                                    </label>
                                    <input type="date" 
                                           class="form-control @error('date_resolved') is-invalid @enderror" 
                                           id="date_resolved" 
                                           name="date_resolved" 
                                           value="{{ old('date_resolved', $callLog->date_resolved ? $callLog->date_resolved->format('Y-m-d') : '') }}">
                                    @error('date_resolved')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="engineer_comments" class="form-label">
                                        Engineer Comments
                                        <span class="text-danger" id="engineer_comments_required" style="display: none;">*</span>
                                    </label>
                                    <textarea class="form-control @error('engineer_comments') is-invalid @enderror" 
                                              id="engineer_comments" 
                                              name="engineer_comments" 
                                              rows="4" 
                                              placeholder="Required when completing: Describe work done, parts used, resolution steps, etc.">{{ old('engineer_comments', $callLog->engineer_comments) }}</textarea>
                                    <div class="form-text text-muted">
                                        Required when marking job as complete. Describe the technical work performed.
                                    </div>
                                    @error('engineer_comments')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                @else
                    {{-- Admin/Accounts View - Full Access --}}
                    <div class="form-wizard">
                        {{-- Customer Information --}}
                        <div class="form-section mb-4">
                            <h6 class="section-title">
                                <i class="fas fa-user me-2"></i>
                                Customer Information
                            </h6>
                            
                            <div class="row g-3 mt-2">
                                <div class="col-md-6">
                                    <label for="job_card_admin" class="form-label">Job Card Number</label>
                                    <input type="text" 
                                           class="form-control @error('job_card') is-invalid @enderror" 
                                           id="job_card_admin" 
                                           name="job_card" 
                                           value="{{ old('job_card', $callLog->job_card) }}">
                                    @error('job_card')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="customer_name" class="form-label">Customer Name *</label>
                                    <input type="text" 
                                           class="form-control @error('customer_name') is-invalid @enderror" 
                                           id="customer_name" 
                                           name="customer_name" 
                                           value="{{ old('customer_name', $callLog->customer_name) }}" 
                                           required>
                                    @error('customer_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="customer_email" class="form-label">Customer Email *</label>
                                    <input type="email" 
                                           class="form-control @error('customer_email') is-invalid @enderror" 
                                           id="customer_email" 
                                           name="customer_email" 
                                           value="{{ old('customer_email', $callLog->customer_email) }}" 
                                           required>
                                    @error('customer_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="customer_phone" class="form-label">Customer Phone</label>
                                    <input type="text" 
                                           class="form-control @error('customer_phone') is-invalid @enderror" 
                                           id="customer_phone" 
                                           name="customer_phone" 
                                           value="{{ old('customer_phone', $callLog->customer_phone) }}">
                                    @error('customer_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="customer_address" class="form-label">Customer Address</label>
                                    <input type="text" 
                                           class="form-control @error('customer_address') is-invalid @enderror" 
                                           id="customer_address" 
                                           name="customer_address" 
                                           value="{{ old('customer_address', $callLog->customer_address) }}">
                                    @error('customer_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="zimra_ref" class="form-label">ZIMRA Reference</label>
                                    <input type="text" 
                                           class="form-control @error('zimra_ref') is-invalid @enderror" 
                                           id="zimra_ref" 
                                           name="zimra_ref" 
                                           value="{{ old('zimra_ref', $callLog->zimra_ref) }}">
                                    @error('zimra_ref')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="fault_description" class="form-label">Fault Description *</label>
                                    <textarea class="form-control @error('fault_description') is-invalid @enderror" 
                                              id="fault_description" 
                                              name="fault_description" 
                                              rows="4" 
                                              required>{{ old('fault_description', $callLog->fault_description) }}</textarea>
                                    @error('fault_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Job Information --}}
                        <div class="form-section mb-4">
                            <h6 class="section-title">
                                <i class="fas fa-cogs me-2"></i>
                                Job Information
                            </h6>
                            
                            <div class="row g-3 mt-2">
                                <div class="col-md-6">
                                    <label for="type" class="form-label">Job Type *</label>
                                    <select class="form-select @error('type') is-invalid @enderror" 
                                            id="type" name="type" required>
                                        @foreach($types as $value => $label)
                                            <option value="{{ $value }}" @selected(old('type', $callLog->type) == $value)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="status_admin" class="form-label">Status *</label>
                                    <select class="form-select @error('status') is-invalid @enderror" 
                                            id="status_admin" name="status" required>
                                        @foreach($statuses as $value => $label)
                                            <option value="{{ $value }}" @selected(old('status', $callLog->status) == $value)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="assigned_to" class="form-label">Assigned Engineer</label>
                                    <select class="form-select @error('assigned_to') is-invalid @enderror" 
                                            id="assigned_to" name="assigned_to">
                                        <option value="">Select engineer...</option>
                                        @foreach($technicians as $user)
                                            <option value="{{ $user->id }}" @selected(old('assigned_to', $callLog->assigned_to) == $user->id)>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('assigned_to')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="date_booked" class="form-label">Date Booked *</label>
                                    <input type="date" 
                                           class="form-control @error('date_booked') is-invalid @enderror" 
                                           id="date_booked" 
                                           name="date_booked" 
                                           value="{{ old('date_booked', $callLog->date_booked ? $callLog->date_booked->format('Y-m-d') : now()->format('Y-m-d')) }}" 
                                           required>
                                    @error('date_booked')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="amount_charged" class="form-label">Amount Charged *</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" 
                                               class="form-control @error('amount_charged') is-invalid @enderror" 
                                               id="amount_charged" 
                                               name="amount_charged" 
                                               value="{{ old('amount_charged', $callLog->amount_charged) }}" 
                                               step="0.01" 
                                               min="0" 
                                               required>
                                    </div>
                                    @error('amount_charged')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="currency" class="form-label">Currency *</label>
                                    <select class="form-select @error('currency') is-invalid @enderror" 
                                            id="currency" name="currency" required>
                                        <option value="USD" @selected(old('currency', $callLog->currency ?? 'USD') == 'USD')>USD</option>
                                        <option value="ZWG" @selected(old('currency', $callLog->currency) == 'ZWG')>ZWG</option>
                                    </select>
                                    @error('currency')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Engineering Details --}}
                        <div class="form-section">
                            <h6 class="section-title">
                                <i class="fas fa-tools me-2"></i>
                                Engineering Details
                            </h6>
                            
                            <div class="row g-3 mt-2">
                                <div class="col-md-6">
                                    <label for="time_start_admin" class="form-label">Start Time</label>
                                    <input type="time" 
                                           class="form-control @error('time_start') is-invalid @enderror" 
                                           id="time_start_admin" 
                                           name="time_start" 
                                           value="{{ old('time_start', $callLog->time_start) }}">
                                    @error('time_start')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="time_finish_admin" class="form-label">Finish Time</label>
                                    <input type="time" 
                                           class="form-control @error('time_finish') is-invalid @enderror" 
                                           id="time_finish_admin" 
                                           name="time_finish" 
                                           value="{{ old('time_finish', $callLog->time_finish) }}">
                                    @error('time_finish')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="billed_hours_admin" class="form-label">Billed Hours</label>
                                    <input type="text" 
                                           class="form-control @error('billed_hours') is-invalid @enderror" 
                                           id="billed_hours_admin" 
                                           name="billed_hours" 
                                           value="{{ old('billed_hours', $callLog->billed_hours) }}"
                                           placeholder="e.g., 10%, 1, 2">
                                    @error('billed_hours')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="date_resolved_admin" class="form-label">Date Resolved</label>
                                    <input type="date" 
                                           class="form-control @error('date_resolved') is-invalid @enderror" 
                                           id="date_resolved_admin" 
                                           name="date_resolved" 
                                           value="{{ old('date_resolved', $callLog->date_resolved ? $callLog->date_resolved->format('Y-m-d') : '') }}">
                                    @error('date_resolved')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="engineer_comments_admin" class="form-label">Engineer Comments</label>
                                    <textarea class="form-control @error('engineer_comments') is-invalid @enderror" 
                                              id="engineer_comments_admin" 
                                              name="engineer_comments" 
                                              rows="4" 
                                              placeholder="Technical work performed, parts used, resolution steps, etc.">{{ old('engineer_comments', $callLog->engineer_comments) }}</textarea>
                                    @error('engineer_comments')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Form Actions --}}
                <div class="form-actions mt-4 pt-3 border-top">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="action-buttons">
                            <button type="submit" class="btn btn-primary me-2" id="saveJobBtn">
                                <i class="fas fa-save me-2"></i>
                                Update Job Card
                            </button>
                            <a href="{{ route('admin.call-logs.show', $callLog) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>
                                Cancel
                            </a>
                        </div>
                        <div class="form-info">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Last updated: {{ $callLog->updated_at->format('M j, Y \a\t g:i A') }}
                            </small>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
:root {
    --primary: #059669;
    --primary-dark: #047857;
    --success: #059669;
    --danger: #DC2626;
    --warning: #F59E0B;
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
    --transition: all 0.2s ease;
}

/* DASHBOARD HEADER */
.dashboard-header {
    background: var(--white);
    border-radius: var(--border-radius);
    padding: 0.75rem 1rem;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--gray-200);
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.dashboard-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--gray-800);
    margin: 0;
    display: flex;
    align-items: center;
}

.header-meta {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-top: 0.25rem;
}

.badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    background: var(--secondary);
    color: white;
}

.header-actions {
    display: flex;
    gap: 0.5rem;
}

/* CONTENT CARD */
.content-card {
    background: var(--white);
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--gray-200);
}

.content-card-header {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid var(--gray-200);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--gray-800);
    margin: 0;
    display: flex;
    align-items: center;
}

.card-subtitle {
    color: var(--gray-500);
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

/* STATUS BADGE */
.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.375rem 0.75rem;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 600;
    background: var(--gray-100);
    color: var(--gray-700);
    text-transform: capitalize;
}

.status-badge.pending {
    background: #FEF3C7;
    color: #92400E;
}

.status-badge.assigned {
    background: #DBEAFE;
    color: #1E40AF;
}

.status-badge.in-progress {
    background: #FEF2F2;
    color: #DC2626;
}

.status-badge.complete {
    background: #D1FAE5;
    color: #065F46;
}

/* FORM SECTIONS */
.form-section {
    margin-bottom: 2rem;
}

.section-title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--gray-800);
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid var(--gray-200);
}

/* FORM LABEL */
.form-label {
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 0.5rem;
}

.form-label.required::after {
    content: ' *';
    color: var(--danger);
}

/* FORM CONTROLS */
.form-control,
.form-select {
    border: 1px solid var(--gray-300);
    border-radius: var(--border-radius);
    transition: var(--transition);
    font-size: 0.95rem;
    padding: 0.375rem 0.75rem;
}

.form-control:focus,
.form-select:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 2px rgba(5, 150, 105, 0.15);
    outline: none;
}

/* INPUT GROUPS */
.input-group-text {
    background: var(--gray-50);
    border: 1px solid var(--gray-300);
    color: var(--gray-700);
    font-weight: 600;
}

/* QUICK HOURS BUTTONS */
.btn-group .btn {
    margin-right: 0.5rem;
    border-radius: var(--border-radius);
    font-weight: 600;
    transition: var(--transition);
}

.btn-group .btn:last-child {
    margin-right: 0;
}

.btn-outline-secondary {
    color: var(--gray-700);
    border-color: var(--gray-400);
}

.btn-outline-secondary:hover {
    background-color: var(--primary);
    color: var(--white);
    border-color: var(--primary);
}

.btn-outline-info {
    color: var(--info);
    border-color: var(--info);
}

.btn-outline-info:hover {
    background-color: var(--info);
    color: var(--white);
    border-color: var(--info);
}

/* FORM ACTIONS */
.form-actions {
    background: var(--gray-50);
    margin: 0 -1.5rem -1.5rem;
    padding: 1rem 1.5rem;
    border-radius: 0 0 var(--border-radius) var(--border-radius);
}

.action-buttons {
    display: flex;
    gap: 0.75rem;
}

.btn-primary {
    background: var(--primary);
    border-color: var(--primary);
    color: white;
}

.btn-primary:hover {
    background: var(--primary-dark);
    border-color: var(--primary-dark);
}

.btn-outline-secondary {
    background: transparent;
    border: 1px solid var(--gray-400);
    color: var(--gray-600);
}

.btn-outline-secondary:hover {
    background: var(--gray-200);
    border-color: var(--gray-600);
    color: var(--gray-800);
}

/* ALERTS */
.alert {
    border-radius: var(--border-radius);
    font-size: 0.9rem;
}

.alert-success {
    background: #D1FAE5;
    color: #065F46;
}

.alert-danger {
    background: #FEE2E2;
    color: #991B1B;
}

/* INVALID FEEDBACK */
.invalid-feedback {
    font-size: 0.8rem;
    margin-top: 0.25rem;
}

/* FORM TEXT */
.form-text {
    font-size: 0.8rem;
    color: var(--gray-500);
    margin-top: 0.25rem;
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .dashboard-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .header-actions {
        width: 100%;
        justify-content: flex-end;
    }
    
    .content-card-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.75rem;
    }
    
    .form-actions {
        flex-direction: column;
        align-items: stretch;
        gap: 0.75rem;
    }
    
    .action-buttons {
        width: 100%;
        flex-direction: column;
    }
}

</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusSelect = document.getElementById('status') || document.getElementById('status_admin');
    const jobCardInput = document.getElementById('job_card');
    const timeStartInput = document.getElementById('time_start');
    const timeFinishInput = document.getElementById('time_finish');
    const billedHoursInput = document.getElementById('billed_hours') || document.getElementById('billed_hours_admin');
    const dateResolvedInput = document.getElementById('date_resolved') || document.getElementById('date_resolved_admin');
    const engineerCommentsInput = document.getElementById('engineer_comments') || document.getElementById('engineer_comments_admin');
    
    // Quick hours buttons
    const quickHoursButtons = document.querySelectorAll('.quick-hours');
    const calculateBtn = document.getElementById('calculateHours');

    function toggleRequiredFields() {
        const isComplete = statusSelect && statusSelect.value === 'complete';
        
        // Required field indicators
        const requiredFields = [
            'job_card_required',
            'time_start_required', 
            'time_finish_required',
            'billed_hours_required',
            'date_resolved_required',
            'engineer_comments_required'
        ];
        
        requiredFields.forEach(fieldId => {
            const indicator = document.getElementById(fieldId);
            if (indicator) {
                indicator.style.display = isComplete ? 'inline' : 'none';
            }
        });

        // Set required attributes
        const fieldsToToggle = [
            jobCardInput,
            timeStartInput,
            timeFinishInput,
            billedHoursInput,
            dateResolvedInput,
            engineerCommentsInput
        ];
        
        fieldsToToggle.forEach(field => {
            if (field) {
                field.required = isComplete;
                if (isComplete) {
                    field.classList.add('required-for-completion');
                } else {
                    field.classList.remove('required-for-completion');
                }
            }
        });

        // Auto-fill date resolved if completing
        if (isComplete && dateResolvedInput && !dateResolvedInput.value) {
            dateResolvedInput.value = new Date().toISOString().split('T')[0];
        }
    }

    // Quick hours selection
    quickHoursButtons.forEach(button => {
        button.addEventListener('click', function() {
            if (billedHoursInput) {
                billedHoursInput.value = this.dataset.value;
                quickHoursButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
            }
        });
    });

    // Calculate hours from time difference
    if (calculateBtn) {
        calculateBtn.addEventListener('click', function() {
            const startTime = timeStartInput?.value;
            const endTime = timeFinishInput?.value;
            
            if (startTime && endTime && billedHoursInput) {
                const start = new Date(`2000-01-01T${startTime}`);
                const end = new Date(`2000-01-01T${endTime}`);
                const diffHours = (end - start) / (1000 * 60 * 60);
                
                if (diffHours > 0) {
                    billedHoursInput.value = Math.round(diffHours * 2) / 2; // Round to nearest 0.5
                } else {
                    alert('End time must be after start time');
                }
            } else {
                alert('Please enter both start and end times');
            }
        });
    }

    // Status change handler
    if (statusSelect) {
        statusSelect.addEventListener('change', toggleRequiredFields);
        toggleRequiredFields(); // Initial check
    }

    // Form submission
    const form = document.getElementById('editJobCardForm');
    const saveBtn = document.getElementById('saveJobBtn');
    
    if (form && saveBtn) {
        form.addEventListener('submit', function(e) {
            saveBtn.disabled = true;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Updating...';
        });
    }

    // Character counter for comments
    if (engineerCommentsInput) {
        const maxLength = 1000;
        const counterElement = document.createElement('small');
        counterElement.className = 'form-text text-muted char-counter';
        engineerCommentsInput.parentNode.appendChild(counterElement);
        
        function updateCharCount() {
            const currentLength = engineerCommentsInput.value.length;
            counterElement.textContent = `${currentLength}/${maxLength} characters`;
            
            if (currentLength > maxLength * 0.9) {
                counterElement.style.color = '#DC2626';
            } else {
                counterElement.style.color = '#6B7280';
            }
        }
        
        engineerCommentsInput.addEventListener('input', updateCharCount);
        updateCharCount(); // Initial count
    }
});
</script>
@endpush
