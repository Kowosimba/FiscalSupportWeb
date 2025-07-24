<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Unassigned Jobs Report</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f5f7fa;
        }
        .container {
            max-width: 650px;
            margin: 20px auto;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 25px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0 0 10px 0;
            font-size: 26px;
        }
        .date-stamp {
            background: rgba(255,255,255,0.2);
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            display: inline-block;
        }
        .content {
            padding: 30px;
        }
        .greeting {
            color: #1f2937;
            font-size: 16px;
            margin-bottom: 25px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 15px;
            margin: 25px 0;
        }
        .stat-card {
            background: #f8fafc;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            border-left: 4px solid #e5e7eb;
        }
        .stat-card.primary {
            border-left-color: #3b82f6;
        }
        .stat-card.danger {
            border-left-color: #dc2626;
            background: #fef2f2;
        }
        .stat-card.warning {
            border-left-color: #f59e0b;
            background: #fffbeb;
        }
        .stat-card.success {
            border-left-color: #10b981;
            background: #f0fdf4;
        }
        .stat-number {
            font-size: 28px;
            font-weight: bold;
            color: #1f2937;
            display: block;
        }
        .stat-label {
            font-size: 12px;
            color: #6b7280;
            text-transform: uppercase;
            font-weight: 600;
            margin-top: 5px;
        }
        .recent-jobs {
            margin: 25px 0;
        }
        .job-item {
            background: #f9fafb;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 10px;
            border-left: 3px solid #e5e7eb;
        }
        .job-item.emergency {
            border-left-color: #dc2626;
            background: #fef2f2;
        }
        .job-header {
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 5px;
        }
        .job-details {
            font-size: 13px;
            color: #6b7280;
        }
        .job-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 8px;
        }
        .job-type {
            background: #e5e7eb;
            color: #4b5563;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .job-type.emergency {
            background: #fecaca;
            color: #dc2626;
        }
        .job-amount {
            font-weight: 600;
            color: #059669;
        }
        .action-section {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            padding: 25px;
            margin: 25px 0;
            border-radius: 8px;
            text-align: center;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 5px;
            transition: all 0.3s ease;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
        }
        .btn-secondary {
            background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
        }
        .btn-secondary:hover {
            box-shadow: 0 4px 12px rgba(107, 114, 128, 0.4);
        }
        .alert {
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
        }
        .alert-warning {
            background: #fef3c7;
            color: #92400e;
            border-left: 4px solid #f59e0b;
        }
        .alert-success {
            background: #d1fae5;
            color: #047857;
            border-left: 4px solid #10b981;
        }
        .footer {
            background: #f8fafc;
            padding: 20px;
            text-align: center;
            color: #6b7280;
            font-size: 13px;
        }
        .no-jobs {
            text-align: center;
            color: #6b7280;
            font-style: italic;
            padding: 20px;
        }
        @media (max-width: 600px) {
            .container {
                margin: 10px;
                border-radius: 0;
            }
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .job-meta {
                flex-direction: column;
                align-items: flex-start;
            }
            .content {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📊 Daily Jobs Report</h1>
            <div class="date-stamp">
                {{ now()->format('l, F j, Y') }} - 8:00 AM Report
            </div>
        </div>

        <div class="content">
            <div class="greeting">
                Good morning <strong>{{ $manager->name }}</strong>,
            </div>

            @if($unassignedCount > 0)
                <div class="alert alert-warning">
                    <strong>⚠️ Action Required:</strong> 
                    You have <strong>{{ $unassignedCount }}</strong> job{{ $unassignedCount !== 1 ? 's' : '' }} 
                    that need{{ $unassignedCount === 1 ? 's' : '' }} to be assigned to technicians.
                    @if($emergencyCount > 0)
                        <strong>{{ $emergencyCount }}</strong> of these are marked as emergency jobs.
                    @endif
                </div>
            @else
                <div class="alert alert-success">
                    <strong>✅ Great job!</strong> All jobs are currently assigned to technicians. No immediate action required.
                </div>
            @endif

            <h3 style="color: #1f2937; margin: 25px 0 15px 0;">📈 Today's Overview</h3>
            
            <div class="stats-grid">
                <div class="stat-card {{ $unassignedCount > 0 ? 'danger' : 'success' }}">
                    <span class="stat-number">{{ $unassignedCount }}</span>
                    <div class="stat-label">Unassigned Jobs</div>
                </div>
                
                <div class="stat-card {{ $emergencyCount > 0 ? 'danger' : 'primary' }}">
                    <span class="stat-number">{{ $emergencyCount }}</span>
                    <div class="stat-label">Emergency Jobs</div>
                </div>
                
                <div class="stat-card {{ $overdueCount > 0 ? 'warning' : 'primary' }}">
                    <span class="stat-number">{{ $overdueCount }}</span>
                    <div class="stat-label">Overdue Jobs</div>
                </div>
                
                <div class="stat-card success">
                    <span class="stat-number">${{ number_format($totalPendingValue, 0) }}</span>
                    <div class="stat-label">Pending Value</div>
                </div>
            </div>

            @if($recentJobs->count() > 0)
                <h3 style="color: #1f2937; margin: 25px 0 15px 0;">🆕 Recent Unassigned Jobs</h3>
                
                @foreach($recentJobs->take(5) as $job)
                    <div class="job-item {{ $job->type === 'emergency' ? 'emergency' : '' }}">
                        <div class="job-header">
                            Job #{{ $job->job_card ?? $job->id }} - {{ $job->customer_name ?? $job->company_name }}
                        </div>
                        <div class="job-details">
                            {{ Str::limit($job->fault_description, 80) }}
                        </div>
                        <div class="job-meta">
                            <div>
                                <span class="job-type {{ $job->type === 'emergency' ? 'emergency' : '' }}">
                                    {{ $job->type === 'emergency' ? '🚨 ' : '' }}{{ ucfirst($job->type ?? 'normal') }}
                                </span>
                            </div>
                            <div class="job-amount">
                                ${{ number_format($job->amount_charged ?? 0, 2) }}
                            </div>
                        </div>
                    </div>
                @endforeach
                
                @if($recentJobs->count() > 5)
                    <div class="no-jobs">
                        ... and {{ $recentJobs->count() - 5 }} more jobs waiting for assignment
                    </div>
                @endif
            @endif

            <div class="action-section">
                <h3 style="color: #1e40af; margin-top: 0;">🎯 Quick Actions</h3>
                <p style="color: #1f2937; margin-bottom: 20px;">
                    Click the buttons below to manage job assignments:
                </p>
                
                @if($unassignedCount > 0)
                    <a href="{{ $dashboardUrl }}" class="btn">
                        🔧 Assign Jobs Now ({{ $unassignedCount }})
                    </a>
                @endif
                
                <a href="{{ $allJobsUrl }}" class="btn btn-secondary">
                    📋 View All Jobs
                </a>
            </div>

            @if($unassignedCount > 0)
                <div style="background: #fef3c7; padding: 15px; border-radius: 6px; margin: 20px 0;">
                    <h4 style="color: #92400e; margin-top: 0;">⏰ Reminder</h4>
                    <p style="color: #78350f; margin-bottom: 0;">
                        Timely job assignments help maintain customer satisfaction and ensure efficient service delivery. 
                        @if($emergencyCount > 0)
                            <strong>Emergency jobs should be assigned immediately.</strong>
                        @endif
                    </p>
                </div>
            @else
                <div style="background: #d1fae5; padding: 15px; border-radius: 6px; margin: 20px 0; text-align: center;">
                    <h4 style="color: #047857; margin-top: 0;">🌟 Excellent Work!</h4>
                    <p style="color: #065f46; margin-bottom: 0;">
                        Your team is staying on top of job assignments. Keep up the great work!
                    </p>
                </div>
            @endif

            <p style="color: #6b7280; margin-top: 30px;">
                This is your automated daily report. You will receive this every morning at 8:00 AM to help you stay on top of job assignments.
            </p>

            <p>Have a productive day!</p>
            
            <p style="margin-bottom: 0;">
                Best regards,<br>
                <strong>{{ $companyName }} System</strong>
            </p>
        </div>

        <div class="footer">
            <p><strong>{{ $companyName }}</strong> - Automated Daily Report</p>
            <p>This email was sent automatically. Please do not reply to this message.</p>
            <p style="margin-top: 15px;">&copy; {{ date('Y') }} {{ $companyName }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
