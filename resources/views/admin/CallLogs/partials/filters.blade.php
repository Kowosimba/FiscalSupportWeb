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
                               value="{{ request('search') }}" placeholder="Job card, company, fault...">
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
                        <label for="technician" class="form-label">Technician/Manager</label>
                        <select class="form-control form-control-enhanced" id="technician" name="technician">
                            <option value="">All</option>
                            @foreach($technicians as $tech)
                                <option value="{{ $tech->name }}" {{ request('technician') == $tech->name ? 'selected' : '' }}>
                                    {{ $tech->name }}
                                </option>
                            @endforeach
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
                
                <div class="col-md-3">
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
