@extends('layouts.contents')

@section('content')


    <div class="container-fluid">
        <div class="page-header-card mb-4">
            <div class="page-header-content">
                <div>
                    <h3 class="page-title">
                        <i class="fa fa-envelope me-2"></i>
                        Newsletter Subscribers
                    </h3>
                    <p class="page-subtitle">
                        Manage your newsletter audience and keep them engaged.
                    </p>
                </div>
            </div>
        </div>

        <div class="content-card">
            <div class="content-card-header">
                <h5 class="card-title">
                    <i class="fa fa-users me-2"></i>
                    Subscribers List
                </h5>
            </div>
            <div class="content-card-body">
                <div class="table-responsive">
                    <table class="enhanced-table">
                        <thead>
                            <tr>
                                <th><i class="fa fa-envelope me-1"></i>Email</th>
                                <th><i class="fa fa-toggle-on me-1"></i>Status</th>
                                <th><i class="fa fa-calendar me-1"></i>Subscribed On</th>
                                <th><i class="fa fa-cog me-1"></i>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($subscribers as $subscriber)
                            <tr>
                                <td>{{ $subscriber->email }}</td>
                                <td>
                                    <span class="status-badge status-{{ $subscriber->is_active ? 'active' : 'inactive' }}">
                                        <i class="fa fa-{{ $subscriber->is_active ? 'check-circle' : 'times-circle' }} me-1"></i>
                                        {{ $subscriber->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>{{ $subscriber->created_at->format('M d, Y H:i') }}</td>
                                <td>
                                    <form action="{{ route('admin.subscribers.destroy', $subscriber) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn delete-btn" title="Delete" onclick="return confirm('Are you sure you want to delete this subscriber?')">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="empty-state">
                                    <div class="empty-content">
                                        <div class="empty-icon">
                                            <i class="fa fa-inbox"></i>
                                        </div>
                                        <h5 class="empty-title">No subscribers found</h5>
                                        <p class="empty-description">
                                            When someone subscribes to your newsletter, they’ll appear here.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-4">
                    {{ $subscribers->links() }}
                </div>
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
        .page-header-content { padding: 2rem; background: linear-gradient(135deg, var(--ultra-light-green) 0%, var(--light-green) 100%);}
        .page-title { font-size: 1.5rem; font-weight: 600; color: var(--primary-green-dark);}
        .page-subtitle { color: var(--light-text); margin: 0.5rem 0 0 0; font-size: 0.95rem;}
        .content-card { background: var(--white); border-radius: 16px; box-shadow: var(--shadow); overflow: hidden; border: 1px solid var(--border-color);}
        .content-card-header { padding: 1.5rem 2rem; background: linear-gradient(135deg, var(--ultra-light-green) 0%, var(--light-green) 100%); border-bottom: 1px solid var(--border-color);}
        .content-card-header .card-title { font-size: 1.15rem; font-weight: 600; color: var(--primary-green);}
        .content-card-body { padding: 2rem;}
        .enhanced-table { width: 100%; border-collapse: separate; border-spacing: 0; margin: 0;}
        .enhanced-table thead th { background: var(--ultra-light-green); color: var(--primary-green); font-weight: 600; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.05em; padding: 1rem 1.5rem; border-bottom: 2px solid var(--light-green);}
        .enhanced-table tbody tr { transition: all 0.2s ease; border-bottom: 1px solid var(--border-color);}
        .enhanced-table tbody tr:last-child { border-bottom: none;}
        .enhanced-table tbody tr:hover { background: var(--ultra-light-green);}
        .enhanced-table tbody td { padding: 1rem 1.5rem; vertical-align: middle;}
        .status-badge { display: inline-flex; align-items: center; padding: 0.375rem 0.75rem; border-radius: 6px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;}
        .status-active { background: var(--ultra-light-green); color: var(--primary-green); border: 1px solid var(--secondary-green);}
        .status-inactive { background: #FEF2F2; color: #DC2626; border: 1px solid #FECACA;}
        .action-btn { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 8px; transition: all 0.2s ease; text-decoration: none; border: none; cursor: pointer;}
        .delete-btn { background: #FEF2F2; color: #DC2626;}
        .delete-btn:hover { background: #DC2626; color: var(--white);}
        .empty-state { text-align: center; padding: 3rem 1.5rem;}
        .empty-content { max-width: 400px; margin: 0 auto;}
        .empty-icon { width: 64px; height: 64px; border-radius: 50%; background: var(--hover-bg); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;}
        .empty-icon i { font-size: 2rem; color: var(--light-text);}
        .empty-title { color: var(--dark-text); font-weight: 600; margin-bottom: 0.5rem;}
        .empty-description { color: var(--light-text); margin-bottom: 1.5rem;}
        @media (max-width: 768px) {
            .page-header-content, .content-card-header, .content-card-body { padding: 1rem;}
        }
    </style>
@endsection
