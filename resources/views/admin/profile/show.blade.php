@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="dashboard-container">
    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Compact Header --}}
    <div class="dashboard-header mb-2">
        <div class="header-content">
            <h1 class="dashboard-title">
                <i class="fas fa-user-circle me-2"></i>
                My Profile
            </h1>
            <div class="header-meta">
                <span class="badge bg-secondary me-2">
                    <i class="fas fa-id-badge me-1"></i>
                    {{ ucfirst($user->role ?? 'User') }}
                </span>
                <span class="badge bg-info me-2">
                    <i class="fas fa-calendar me-1"></i>
                    Member since {{ $user->created_at->format('M Y') }}
                </span>
                <small class="text-muted">Last updated: {{ $user->updated_at->diffForHumans() }}</small>
            </div>
        </div>
        <div class="header-actions">
            <button onclick="window.location.href='{{ route('admin.index') }}'" class="btn btn-sm btn-outline-secondary me-2">
                <i class="fas fa-arrow-left me-1"></i>
                Back to Dashboard
            </button>
        </div>
    </div>

    {{-- Status Overview --}}
    <div class="status-overview mb-2">
        <div class="status-item">
            @php
                $roleConfig = [
                    'admin' => ['class' => 'status-complete', 'icon' => 'crown', 'label' => 'Administrator'],
                    'manager' => ['class' => 'status-progress', 'icon' => 'user-tie', 'label' => 'Manager'],
                    'accounts' => ['class' => 'status-assigned', 'icon' => 'calculator', 'label' => 'Accounts'],
                    'technician' => ['class' => 'status-progress', 'icon' => 'tools', 'label' => 'Technician'],
                    'user' => ['class' => 'status-pending', 'icon' => 'user', 'label' => 'User']
                ];
                $config = $roleConfig[$user->role ?? 'user'] ?? ['class' => 'status-default', 'icon' => 'user', 'label' => 'User'];
            @endphp
            <span class="status-badge {{ $config['class'] }}">
                <i class="fas fa-{{ $config['icon'] }} me-1"></i>
                {{ $config['label'] }}
            </span>
        </div>
        
        <div class="status-item">
            <span class="type-badge {{ $user->created_at->diffInDays() < 30 ? 'emergency' : 'normal' }}">
                <i class="fas fa-{{ $user->created_at->diffInDays() < 30 ? 'star' : 'user-check' }} me-1"></i>
                {{ $user->created_at->diffInDays() < 30 ? 'New Member' : 'Established Member' }}
            </span>
        </div>
        
        <div class="status-item">
            <span class="amount-badge">
                <i class="fas fa-clock me-1"></i>
                Active for {{ $user->created_at->diffInDays() }} days
            </span>
        </div>
    </div>

   

    <div class="row g-3">
        {{-- Main Content --}}
        <div class="col-lg-8">
            {{-- Personal Information Card --}}
            <div class="content-card mb-3">
                <div class="content-card-header">
                    <div class="header-content">
                        <h4 class="card-title">
                            <i class="fas fa-user me-2"></i>
                            Personal Information
                        </h4>
                        <p class="card-subtitle mb-0">
                            Update your basic profile information
                        </p>
                    </div>
                </div>
                
                <div class="content-card-body" style="padding: 1rem;">
                    <form action="{{ route('profile.update') }}" method="POST" id="profileForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="info-section">
                                    <div class="info-grid">
                                        <div class="info-item">
                                            <div class="info-label">Full Name</div>
                                            <input type="text" 
                                                   class="form-control @error('name') is-invalid @enderror" 
                                                   id="name" 
                                                   name="name" 
                                                   value="{{ old('name', $user->name) }}" 
                                                   required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="info-item">
                                            <div class="info-label">Email Address</div>
                                            <input type="email" 
                                                   class="form-control @error('email') is-invalid @enderror" 
                                                   id="email" 
                                                   name="email" 
                                                   value="{{ old('email', $user->email) }}" 
                                                   required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="info-section">
                                    <div class="info-grid">
                                        <div class="info-item">
                                            <div class="info-label">Current Password <span class="text-danger">*</span></div>
                                            <div class="input-group">
                                                <input type="password" 
                                                       class="form-control @error('current_password') is-invalid @enderror" 
                                                       id="current_password_profile" 
                                                       name="current_password" 
                                                       placeholder="Enter current password to confirm changes"
                                                       required>
                                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('current_password_profile')">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                            @error('current_password')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="info-item">
                                            <div class="d-flex justify-content-end">
                                                <button type="submit" class="action-btn primary">
                                                    <i class="fas fa-save me-2"></i>
                                                    Update Profile
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Change Password Card --}}
            <div class="content-card mb-3">
                <div class="content-card-header">
                    <div class="header-content">
                        <h4 class="card-title">
                            <i class="fas fa-lock me-2"></i>
                            Security Settings
                        </h4>
                        <p class="card-subtitle mb-0">
                            Update your password for enhanced security
                        </p>
                    </div>
                </div>
                
                <div class="content-card-body" style="padding: 1rem;">
                    <form action="{{ route('profile.password') }}" method="POST" id="passwordForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="info-section">
                                    <div class="info-grid">
                                        <div class="info-item">
                                            <div class="info-label">Current Password</div>
                                            <div class="input-group">
                                                <input type="password" 
                                                       class="form-control @error('current_password') is-invalid @enderror" 
                                                       id="current_password_change" 
                                                       name="current_password" 
                                                       placeholder="Enter current password"
                                                       required>
                                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('current_password_change')">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                            @error('current_password')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="info-section">
                                    <div class="info-grid">
                                        <div class="info-item">
                                            <div class="info-label">New Password</div>
                                            <div class="input-group">
                                                <input type="password" 
                                                       class="form-control @error('password') is-invalid @enderror" 
                                                       id="password" 
                                                       name="password" 
                                                       placeholder="Enter new password"
                                                       minlength="8"
                                                       required>
                                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                            <div class="password-strength mt-1" id="passwordStrength"></div>
                                            <small class="form-text text-muted">
                                                Must be at least 8 characters with uppercase, lowercase, and numbers
                                            </small>
                                            @error('password')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="info-section">
                                    <div class="info-grid">
                                        <div class="info-item">
                                            <div class="info-label">Confirm Password</div>
                                            <div class="input-group">
                                                <input type="password" 
                                                       class="form-control @error('password_confirmation') is-invalid @enderror" 
                                                       id="password_confirmation" 
                                                       name="password_confirmation" 
                                                       placeholder="Confirm new password"
                                                       minlength="8"
                                                       required>
                                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation')">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                            <div class="password-match mt-1" id="passwordMatch"></div>
                                            @error('password_confirmation')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end mt-3">
                            <button type="submit" class="action-btn warning">
                                <i class="fas fa-key me-2"></i>
                                Change Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Recent Sessions Card --}}
            <div class="content-card">
                <div class="content-card-header">
                    <div class="header-content">
                        <h4 class="card-title">
                            <i class="fas fa-history me-2"></i>
                            Recent Sessions
                        </h4>
                        <p class="card-subtitle mb-0">
                            Monitor your account access and security
                        </p>
                    </div>
                </div>
                
                <div class="content-card-body" style="padding: 1rem;">
                    @if(count($recentSessions) > 0)
                        <div class="sessions-list">
                            @foreach($recentSessions as $session)
                                <div class="session-item {{ !$loop->last ? 'border-bottom pb-3 mb-3' : '' }}">
                                    <div class="session-header">
                                        <div class="session-indicator">
                                            <div class="session-avatar {{ $session['is_current'] ? 'current' : 'past' }}">
                                                <i class="fas fa-{{ $session['is_current'] ? 'circle' : 'history' }}"></i>
                                            </div>
                                        </div>
                                        <div class="session-details">
                                            <div class="session-location">{{ $session['location'] }}</div>
                                            <div class="session-meta">
                                                <span class="session-ip">{{ $session['ip_address'] }}</span>
                                                <span class="session-separator">•</span>
                                                <span class="session-time">{{ $session['last_activity']->diffForHumans() }}</span>
                                            </div>
                                            <div class="session-agent">{{ Str::limit($session['user_agent'], 60) }}</div>
                                        </div>
                                        @if($session['is_current'])
                                            <span class="status-badge status-complete">
                                                <i class="fas fa-check-circle me-1"></i>
                                                Current
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-clock fa-2x mb-3"></i>
                            <p class="mb-0">No recent sessions found</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4">
            {{-- Profile Overview Card --}}
            <div class="content-card mb-3">
                <div class="content-card-header">
                    <div class="header-content">
                        <h4 class="card-title">
                            <i class="fas fa-id-card me-2"></i>
                            Profile Overview
                        </h4>
                        <p class="card-subtitle mb-0">
                            Your account information and stats
                        </p>
                    </div>
                </div>
                
                <div class="content-card-body" style="padding: 1rem;">
                    <div class="profile-overview">
                        {{-- Avatar Section --}}
                        <div class="profile-avatar-section mb-3">
                            <div class="position-relative d-inline-block">
                                <img src="{{ $user->avatar_url }}" 
                                     alt="{{ $user->name }}" 
                                     class="profile-avatar" 
                                     width="80" height="80"
                                     onerror="this.src='https://www.gravatar.com/avatar/{{ md5(strtolower(trim($user->email))) }}?d=mp&s=80'">
                                <button type="button" 
                                        class="btn btn-primary btn-sm position-absolute bottom-0 end-0 rounded-circle avatar-update-btn" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#avatarModal"
                                        title="Update Avatar">
                                    <i class="fas fa-camera"></i>
                                </button>
                            </div>
                            <div class="mt-2">
                                @if($user->avatar)
                                    <form action="{{ route('profile.avatar.delete') }}" method="POST" class="d-inline" 
                                          onsubmit="return confirm('Are you sure you want to remove your avatar?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash me-1"></i>
                                            Remove Avatar
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>

                        {{-- User Details --}}
                        <div class="user-card">
                            <div class="user-details">
                                <div class="user-name">{{ $user->name }}</div>
                                <div class="user-role">{{ ucfirst($user->role ?? 'User') }}</div>
                                <div class="user-contact">
                                    <a href="mailto:{{ $user->email }}" class="contact-link">
                                        <i class="fas fa-envelope me-1"></i>
                                        {{ $user->email }}
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- Quick Stats --}}
                        <div class="profile-stats mt-3">
                            <div class="stat-item">
                                <div class="stat-value">{{ $user->created_at->diffInDays() }}</div>
                                <div class="stat-label">Days Active</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-value">
                                    @if(method_exists($user, 'isAdmin') && $user->isAdmin())
                                        <i class="fas fa-crown text-warning"></i>
                                    @elseif(method_exists($user, 'isAccounts') && $user->isAccounts())
                                        <i class="fas fa-calculator text-info"></i>
                                    @elseif(method_exists($user, 'isTechnician') && $user->isTechnician())
                                        <i class="fas fa-tools text-primary"></i>
                                    @elseif(method_exists($user, 'isManager') && $user->isManager())
                                        <i class="fas fa-user-tie text-success"></i>
                                    @else
                                        <i class="fas fa-user text-secondary"></i>
                                    @endif
                                </div>
                                <div class="stat-label">Role</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-value">{{ $user->updated_at->format('M Y') }}</div>
                                <div class="stat-label">Last Update</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Account Timeline Card --}}
            <div class="content-card">
                <div class="content-card-header">
                    <div class="header-content">
                        <h4 class="card-title">
                            <i class="fas fa-history me-2"></i>
                            Account Timeline
                        </h4>
                        <p class="card-subtitle mb-0">
                            Your account milestones and activity
                        </p>
                    </div>
                </div>
                
                <div class="content-card-body" style="padding: 1rem;">
                    <div class="timeline">
                        {{-- Account Created --}}
                        <div class="timeline-item completed">
                            <div class="timeline-marker">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-title">Account Created</div>
                                <div class="timeline-date">{{ $user->created_at->format('M j, Y g:i A') }}</div>
                                <div class="timeline-description">Joined the platform</div>
                            </div>
                        </div>
                        
                        {{-- Email Verified --}}
                        @if($user->email_verified_at)
                            <div class="timeline-item completed">
                                <div class="timeline-marker">
                                    <i class="fas fa-envelope-check"></i>
                                </div>
                                <div class="timeline-content">
                                    <div class="timeline-title">Email Verified</div>
                                    <div class="timeline-date">{{ $user->email_verified_at->format('M j, Y g:i A') }}</div>
                                    <div class="timeline-description">Email address confirmed</div>
                                </div>
                            </div>
                        @endif
                        
                        {{-- Role Assigned --}}
                        @if($user->role && $user->role !== 'user')
                            <div class="timeline-item completed">
                                <div class="timeline-marker">
                                    <i class="fas fa-{{ $user->role === 'admin' ? 'crown' : ($user->role === 'manager' ? 'user-tie' : ($user->role === 'accounts' ? 'calculator' : 'tools')) }}"></i>
                                </div>
                                <div class="timeline-content">
                                    <div class="timeline-title">Role Assigned</div>
                                    <div class="timeline-date">{{ $user->updated_at->format('M j, Y g:i A') }}</div>
                                    <div class="timeline-description">Assigned {{ ucfirst($user->role) }} role</div>
                                </div>
                            </div>
                        @endif
                        
                        {{-- Last Update --}}
                        <div class="timeline-item completed">
                            <div class="timeline-marker">
                                <i class="fas fa-sync"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-title">Profile Updated</div>
                                <div class="timeline-date">{{ $user->updated_at->format('M j, Y g:i A') }}</div>
                                <div class="timeline-description">Last profile modification</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Avatar Upload Modal -->
<div class="modal fade" id="avatarModal" tabindex="-1" aria-labelledby="avatarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="avatarModalLabel">
                    <i class="fas fa-camera me-2"></i>
                    Update Avatar
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('profile.avatar') }}" method="POST" enctype="multipart/form-data" id="avatarForm">
                @csrf
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <img src="{{ $user->avatar_url }}" 
                             alt="Current Avatar" 
                             class="rounded-circle border border-3 border-white shadow" 
                             width="100" height="100"
                             style="object-fit: cover;"
                             id="avatarPreview"
                             onerror="this.src='https://www.gravatar.com/avatar/{{ md5(strtolower(trim($user->email))) }}?d=mp&s=100'">
                    </div>
                    
                    <div class="mb-3">
                        <label for="avatar" class="form-label">Choose new avatar</label>
                        <input type="file" 
                               class="form-control @error('avatar') is-invalid @enderror" 
                               id="avatar" 
                               name="avatar" 
                               accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                               required>
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>
                            Maximum file size: 2MB. Supported formats: JPEG, PNG, JPG, GIF, WebP
                        </div>
                        @error('avatar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary" id="uploadAvatarBtn">
                        <i class="fas fa-upload me-1"></i> Upload Avatar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Profile Specific Styles - Extending Job Card Theme */
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
    --transition: all 0.2s ease;
}

/* Dashboard Layout (inherited from job card) */
.dashboard-container {
    padding: 0.5rem;
    max-width: 100%;
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
    flex-wrap: wrap;
}

.header-meta .badge {
    font-size: 0.65rem;
    padding: 0.2rem 0.4rem;
    border-radius: 4px;
}

.bg-secondary {
    background: var(--secondary) !important;
    color: white;
}

.bg-info {
    background: var(--info) !important;
    color: white;
}

.header-meta small {
    font-size: 0.75rem;
    color: var(--gray-500);
}

.header-actions .btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    height: 28px;
}

/* Status Overview (inherited from job card) */
.status-overview {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    background: var(--white);
    padding: 0.75rem;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--gray-200);
}

.status-item {
    flex: 1;
    min-width: 200px;
}

/* Status Badges (inherited from job card) */
.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.375rem 0.75rem;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 600;
    white-space: nowrap;
}

.status-pending {
    background: #FFFBEB;
    color: #D97706;
    border: 1px solid #FDE68A;
}

.status-assigned {
    background: #F0F9FF;
    color: #0284C7;
    border: 1px solid #BAE6FD;
}

.status-progress {
    background: #EFF6FF;
    color: #1D4ED8;
    border: 1px solid #BFDBFE;
}

.status-complete {
    background: #F0FDF4;
    color: #047857;
    border: 1px solid #BBF7D0;
}

/* Type Badges */
.type-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.375rem 0.75rem;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 600;
    white-space: nowrap;
}

.type-badge.emergency {
    background: #FEF2F2;
    color: #DC2626;
    border: 1px solid #FECACA;
}

.type-badge.normal {
    background: #F3F4F6;
    color: #4B5563;
    border: 1px solid #D1D5DB;
}

/* Amount Badge */
.amount-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.375rem 0.75rem;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 600;
    background: #F0FDF4;
    color: #047857;
    border: 1px solid #BBF7D0;
    white-space: nowrap;
}

/* Content Card (inherited from job card) */
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

.card-subtitle {
    color: var(--gray-500);
    font-size: 0.75rem;
    margin-top: 0.25rem;
}

.content-card-body {
    padding: 0;
}

/* Info Sections */
.info-section {
    height: 100%;
}

.info-grid {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.info-label {
    font-size: 0.75rem;
    color: var(--gray-500);
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 0.25rem;
}

/* Profile Specific Styles */
.profile-overview {
    text-align: center;
}

.profile-avatar-section {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.profile-avatar {
    border-radius: 50%;
    border: 3px solid var(--white);
    box-shadow: var(--shadow-sm);
    object-fit: cover;
}

.avatar-update-btn {
    width: 24px !important;
    height: 24px !important;
    padding: 0 !important;
    font-size: 0.6rem !important;
    border: 2px solid var(--white) !important;
}

.user-card {
    background: var(--gray-50);
    padding: 0.75rem;
    border-radius: var(--border-radius);
    border: 1px solid var(--gray-200);
    margin-bottom: 1rem;
}

.user-name {
    font-weight: 600;
    color: var(--gray-800);
    font-size: 1rem;
    margin-bottom: 0.25rem;
}

.user-role {
    color: var(--gray-500);
    font-size: 0.8rem;
    margin-bottom: 0.5rem;
}

.user-contact {
    margin-top: 0.5rem;
}

.contact-link {
    color: var(--info);
    text-decoration: none;
    font-size: 0.8rem;
    transition: var(--transition);
}

.contact-link:hover {
    color: var(--primary);
    text-decoration: underline;
}

.profile-stats {
    display: flex;
    justify-content: space-between;
    gap: 1rem;
}

.stat-item {
    flex: 1;
    text-align: center;
}

.stat-value {
    font-weight: 600;
    color: var(--gray-800);
    font-size: 0.9rem;
    margin-bottom: 0.25rem;
}

.stat-label {
    color: var(--gray-500);
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

/* Sessions List */
.sessions-list {
    display: flex;
    flex-direction: column;
    gap: 0;
}

.session-item {
    display: flex;
    flex-direction: column;
}

.session-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.session-indicator {
    flex-shrink: 0;
}

.session-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
}

.session-avatar.current {
    background: linear-gradient(135deg, var(--success) 0%, var(--primary-dark) 100%);
}

.session-avatar.past {
    background: var(--gray-400);
}

.session-details {
    flex: 1;
    min-width: 0;
}

.session-location {
    font-weight: 600;
    color: var(--gray-800);
    font-size: 0.875rem;
    margin-bottom: 0.25rem;
}

.session-meta {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.25rem;
}

.session-ip {
    color: var(--gray-600);
    font-size: 0.75rem;
    font-family: monospace;
}

.session-separator {
    color: var(--gray-400);
}

.session-time {
    color: var(--gray-500);
    font-size: 0.75rem;
}

.session-agent {
    color: var(--gray-500);
    font-size: 0.7rem;
    line-height: 1.3;
}

.empty-state {
    text-align: center;
    color: var(--gray-500);
    padding: 2rem 0;
}

/* Action Buttons */
.action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.5rem 1rem;
    border-radius: var(--border-radius);
    font-size: 0.875rem;
    font-weight: 500;
    transition: var(--transition);
    border: none;
    cursor: pointer;
    text-decoration: none;
}

.action-btn.primary {
    background: var(--primary);
    color: white;
}

.action-btn.primary:hover {
    background: var(--primary-dark);
    color: white;
}

.action-btn.warning {
    background: var(--warning);
    color: white;
}

.action-btn.warning:hover {
    background: #D97706;
    color: white;
}

.action-btn.info {
    background: var(--info);
    color: white;
}

.action-btn.info:hover {
    background: #0284C7;
    color: white;
}

.action-btn.outline {
    background: transparent;
    color: var(--secondary);
    border: 1px solid var(--secondary);
}

.action-btn.outline:hover {
    background: var(--secondary);
    color: white;
}

/* Timeline (inherited from job card) */
.timeline {
    position: relative;
    padding-left: 2rem;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 1rem;
    top: 0;
    bottom: 0;
    width: 2px;
    background: var(--gray-200);
}

.timeline-item {
    position: relative;
    margin-bottom: 1.5rem;
}

.timeline-item:last-child {
    margin-bottom: 0;
}

.timeline-marker {
    position: absolute;
    left: -2rem;
    width: 2rem;
    height: 2rem;
    border-radius: 50%;
    background: var(--white);
    border: 3px solid var(--gray-200);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    color: var(--gray-400);
}

.timeline-item.completed .timeline-marker {
    background: var(--success);
    border-color: var(--success);
    color: white;
}

.timeline-content {
    background: var(--gray-50);
    padding: 1rem;
    border-radius: var(--border-radius);
    border: 1px solid var(--gray-200);
}

.timeline-title {
    font-weight: 600;
    color: var(--gray-800);
    margin-bottom: 0.25rem;
    font-size: 0.875rem;
}

.timeline-date {
    color: var(--gray-600);
    margin-bottom: 0.25rem;
    font-size: 0.75rem;
}

.timeline-description {
    color: var(--gray-500);
    font-size: 0.75rem;
}

/* Password Strength Indicator */
.password-strength {
    height: 4px;
    border-radius: 2px;
    background: #e5e7eb;
    overflow: hidden;
    transition: all 0.3s ease;
}

.password-strength.weak {
    background: linear-gradient(90deg, #dc2626 0%, #dc2626 33%, #e5e7eb 33%);
}

.password-strength.medium {
    background: linear-gradient(90deg, #f59e0b 0%, #f59e0b 66%, #e5e7eb 66%);
}

.password-strength.strong {
    background: linear-gradient(90deg, #059669 0%, #059669 100%);
}

.password-match {
    font-size: 0.75rem;
    font-weight: 500;
}

.password-match.match {
    color: #059669;
}

.password-match.no-match {
    color: #dc2626;
}

/* Form Elements */
.form-label {
    font-weight: 600;
    color: var(--gray-700);
    font-size: 0.875rem;
}

.form-control,
.form-select {
    border: 1px solid var(--gray-300);
    border-radius: var(--border-radius);
    font-size: 0.875rem;
    transition: var(--transition);
}

.form-control:focus,
.form-select:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 2px rgba(5, 150, 105, 0.1);
}

/* Modal Styles */
.modal-content {
    border-radius: var(--border-radius);
    border: none;
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.modal-header {
    border-bottom: 1px solid var(--gray-200);
}

.modal-title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--gray-800);
}

/* Alert improvements */
.alert {
    border: none;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-sm);
}

.alert .fas {
    opacity: 0.8;
}

/* Loading states */
.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
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
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    
    .status-overview {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .status-item {
        min-width: auto;
    }
    
    .info-grid {
        gap: 0.5rem;
    }
    
    .profile-stats {
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .session-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .timeline {
        padding-left: 1.5rem;
    }
    
    .timeline::before {
        left: 0.75rem;
    }
    
    .timeline-marker {
        left: -1.5rem;
        width: 1.5rem;
        height: 1.5rem;
        font-size: 0.65rem;
    }
}

@media (max-width: 480px) {
    .action-btn {
        font-size: 0.8rem;
        padding: 0.4rem 0.8rem;
    }
    
    .header-meta {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.25rem;
    }
    
    .profile-overview {
        padding: 0.5rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Password strength checker
    const passwordField = document.getElementById('password');
    const passwordConfirmField = document.getElementById('password_confirmation');
    const passwordStrength = document.getElementById('passwordStrength');
    const passwordMatch = document.getElementById('passwordMatch');
    
    if (passwordField) {
        passwordField.addEventListener('input', function() {
            const password = this.value;
            if (password.length > 0) {
                const strength = calculatePasswordStrength(password);
                updatePasswordStrength(strength);
            } else {
                passwordStrength.className = 'password-strength';
            }
            checkPasswordMatch();
        });
    }
    
    if (passwordConfirmField) {
        passwordConfirmField.addEventListener('input', checkPasswordMatch);
    }
    
    // Avatar preview
    const avatarInput = document.getElementById('avatar');
    const avatarPreview = document.getElementById('avatarPreview');
    
    if (avatarInput && avatarPreview) {
        avatarInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validate file size (2MB limit)
                if (file.size > 2 * 1024 * 1024) {
                    showToast('File size must be less than 2MB', 'error');
                    this.value = '';
                    return;
                }
                
                // Validate file type
                const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
                if (!allowedTypes.includes(file.type)) {
                    showToast('Please select a valid image file (JPEG, PNG, JPG, GIF, WebP)', 'error');
                    this.value = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    avatarPreview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    }
    
    // Form submission loading states
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn && !submitBtn.disabled) {
                // Validate password form if it's the password change form
                if (this.id === 'passwordForm') {
                    const password = this.querySelector('#password').value;
                    const confirmPassword = this.querySelector('#password_confirmation').value;
                    const currentPassword = this.querySelector('#current_password_change').value;
                    
                    if (!currentPassword) {
                        e.preventDefault();
                        showToast('Please enter your current password', 'error');
                        return;
                    }
                    
                    if (password !== confirmPassword) {
                        e.preventDefault();
                        showToast('Passwords do not match', 'error');
                        return;
                    }
                    
                    if (password.length < 8) {
                        e.preventDefault();
                        showToast('Password must be at least 8 characters long', 'error');
                        return;
                    }
                    
                    // Check password strength
                    const strength = calculatePasswordStrength(password);
                    if (strength < 3) {
                        e.preventDefault();
                        showToast('Password must contain uppercase, lowercase, and numbers', 'error');
                        return;
                    }
                }
                
                // Validate profile form
                if (this.id === 'profileForm') {
                    const currentPassword = this.querySelector('#current_password_profile').value;
                    if (!currentPassword) {
                        e.preventDefault();
                        showToast('Please enter your current password to confirm changes', 'error');
                        return;
                    }
                }
                
                // Validate avatar form
                if (this.id === 'avatarForm') {
                    const fileInput = this.querySelector('#avatar');
                    if (!fileInput.files.length) {
                        e.preventDefault();
                        showToast('Please select an image file', 'error');
                        return;
                    }
                }
                
                submitBtn.disabled = true;
                const originalText = submitBtn.innerHTML;
                const loadingText = this.id === 'avatarForm' ? 
                    '<i class="fas fa-spinner fa-spin me-1"></i> Uploading...' : 
                    '<i class="fas fa-spinner fa-spin me-1"></i> Saving...';
                submitBtn.innerHTML = loadingText;
                
                // Re-enable after 15 seconds as fallback
                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }, 15000);
            }
        });
    });
    
    // Auto-dismiss alerts after 5 seconds
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert.alert-dismissible');
        alerts.forEach(alert => {
            if (alert && alert.querySelector('.btn-close')) {
                alert.querySelector('.btn-close').click();
            }
        });
    }, 5000);
    
    function calculatePasswordStrength(password) {
        let strength = 0;
        if (password.length >= 8) strength++;
        if (/[a-z]/.test(password)) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^a-zA-Z0-9]/.test(password)) strength++;
        return strength;
    }
    
    function updatePasswordStrength(strength) {
        passwordStrength.className = 'password-strength';
        if (strength <= 2) {
            passwordStrength.classList.add('weak');
        } else if (strength <= 3) {
            passwordStrength.classList.add('medium');
        } else {
            passwordStrength.classList.add('strong');
        }
    }
    
    function checkPasswordMatch() {
        if (!passwordField || !passwordConfirmField || !passwordMatch) return;
        
        const password = passwordField.value;
        const confirmPassword = passwordConfirmField.value;
        
        if (confirmPassword.length === 0) {
            passwordMatch.textContent = '';
            passwordMatch.className = 'password-match';
            return;
        }
        
        if (password === confirmPassword) {
            passwordMatch.textContent = '✓ Passwords match';
            passwordMatch.className = 'password-match match';
        } else {
            passwordMatch.textContent = '✗ Passwords do not match';
            passwordMatch.className = 'password-match no-match';
        }
    }
    
    // Initialize tooltips
    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
});

function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    if (!field) return;
    
    const button = field.nextElementSibling.querySelector('i');
    if (!button) return;
    
    if (field.type === 'password') {
        field.type = 'text';
        button.classList.remove('fa-eye');
        button.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        button.classList.remove('fa-eye-slash');
        button.classList.add('fa-eye');
    }
}

// Notification function
function showToast(message, type = 'info') {
    if (typeof toastr !== 'undefined') {
        const toastrType = type === 'error' ? 'error' : type === 'success' ? 'success' : type === 'warning' ? 'warning' : 'info';
        toastr[toastrType](message, type.charAt(0).toUpperCase() + type.slice(1) + '!', {
            closeButton: true,
            progressBar: true,
            timeOut: 5000,
            extendedTimeOut: 2000,
            positionClass: 'toast-top-right'
        });
    } else {
        // Fallback notification
        const notification = document.createElement('div');
        notification.className = `alert alert-${type === 'error' ? 'danger' : type === 'success' ? 'success' : type === 'warning' ? 'warning' : 'info'} alert-dismissible fade show notification`;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            max-width: 500px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        `;
        
        notification.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-triangle' : type === 'warning' ? 'exclamation-circle' : 'info-circle'} me-2"></i>
                <span>${message}</span>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
    }
}
</script>
@endpush