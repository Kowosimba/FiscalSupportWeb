<div class="content-card mb-4">
    <div class="content-card-header">
        <h5 class="card-title">
            <i class="fa fa-filter me-2"></i>
            Filters
        </h5>
    </div>
    <div class="content-card-body">
        <form method="GET" action="{{ route('admin.call-logs.index') }}" id="filtersForm">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" class="form-control form-control-enhanced" id="search" name="search" 
                               value="{{ request('search') }}" placeholder="Job card, company, fault description...">
                    </div>
                </div>
                
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-control form-control-enhanced" id="status" name="status">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="assigned" {{ request('status') == 'assigned' ? 'selected' : '' }}>Assigned</option>
                            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="complete" {{ request('status') == 'complete' ? 'selected' : '' }}>Complete</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="type" class="form-label">Job Type</label>
                        <select class="form-control form-control-enhanced" id="type" name="type">
                            <option value="">All Types</option>
                            <option value="normal" {{ request('type') == 'normal' ? 'selected' : '' }}>Normal</option>
                            <option value="maintenance" {{ request('type') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            <option value="repair" {{ request('type') == 'repair' ? 'selected' : '' }}>Repair</option>
                            <option value="installation" {{ request('type') == 'installation' ? 'selected' : '' }}>Installation</option>
                            <option value="consultation" {{ request('type') == 'consultation' ? 'selected' : '' }}>Consultation</option>
                            <option value="emergency" {{ request('type') == 'emergency' ? 'selected' : '' }}>Emergency</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="engineer" class="form-label">Engineer</label>
                        <select class="form-control form-control-enhanced" id="engineer" name="engineer">
                            <option value="">All Engineers</option>
                            <option value="Benson" {{ request('engineer') == 'Benson' ? 'selected' : '' }}>Benson</option>
                            <option value="Malvine" {{ request('engineer') == 'Malvine' ? 'selected' : '' }}>Malvine</option>
                            <option value="Mukai" {{ request('engineer') == 'Mukai' ? 'selected' : '' }}>Mukai</option>
                            <option value="Tapera" {{ request('engineer') == 'Tapera' ? 'selected' : '' }}>Tapera</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="date_range" class="form-label">Date Range</label>
                        <select class="form-control form-control-enhanced" id="date_range" name="date_range">
                            <option value="">All Dates</option>
                            <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Today</option>
                            <option value="this_week" {{ request('date_range') == 'this_week' ? 'selected' : '' }}>This Week</option>
                            <option value="this_month" {{ request('date_range') == 'this_month' ? 'selected' : '' }}>This Month</option>
                            <option value="last_month" {{ request('date_range') == 'last_month' ? 'selected' : '' }}>Last Month</option>
                            <option value="this_year" {{ request('date_range') == 'this_year' ? 'selected' : '' }}>This Year</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-1">
                    <div class="form-group">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex flex-column gap-2">
                            <button type="submit" class="btn btn-primary btn-enhanced">
                                <i class="fa fa-search me-1"></i> Filter
                            </button>
                            <a href="{{ route('admin.call-logs.index') }}" class="btn btn-outline-secondary btn-enhanced">
                                <i class="fa fa-times me-1"></i> Clear
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Advanced Filters Row -->
            <div class="row mt-3">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="date_from" class="form-label">Date From</label>
                        <input type="date" class="form-control form-control-enhanced" id="date_from" name="date_from" 
                               value="{{ request('date_from') }}">
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="date_to" class="form-label">Date To</label>
                        <input type="date" class="form-control form-control-enhanced" id="date_to" name="date_to" 
                               value="{{ request('date_to') }}">
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="min_amount" class="form-label">Min Amount (USD)</label>
                        <input type="number" class="form-control form-control-enhanced" id="min_amount" name="min_amount" 
                               value="{{ request('min_amount') }}" placeholder="0.00" step="0.01">
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="max_amount" class="form-label">Max Amount (USD)</label>
                        <input type="number" class="form-control form-control-enhanced" id="max_amount" name="max_amount" 
                               value="{{ request('max_amount') }}" placeholder="999999.99" step="0.01">
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
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
        font-size: 1.15rem;
        font-weight: 600;
        color: var(--primary-green);
        margin: 0;
        display: flex;
        align-items: center;
    }

    .content-card-body {
        padding: 2rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        font-weight: 600;
        color: var(--dark-text);
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
        width: 100%;
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
        color: var(--medium-text);
        background: transparent;
    }

    .btn-outline-secondary.btn-enhanced:hover {
        background: var(--medium-text);
        color: var(--white);
        border-color: var(--medium-text);
    }

    @media (max-width: 768px) {
        .content-card-header,
        .content-card-body {
            padding: 1rem;
        }

        .row > div {
            margin-bottom: 1rem;
        }

        .d-flex.flex-column {
            flex-direction: row !important;
            gap: 0.5rem !important;
        }

        .btn-enhanced {
            width: auto;
            flex: 1;
        }
    }
</style>
