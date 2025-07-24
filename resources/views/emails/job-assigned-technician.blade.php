<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Assignment Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px;
        }
        .job-details {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .job-details h3 {
            color: #16a34a;
            margin-top: 0;
        }
        .detail-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .detail-item:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: bold;
            color: #64748b;
        }
        .detail-value {
            color: #1e293b;
        }
        .priority-high {
            color: #dc2626;
            font-weight: bold;
        }
        .priority-medium {
            color: #d97706;
            font-weight: bold;
        }
        .priority-normal {
            color: #059669;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
        }
        .btn:hover {
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #64748b;
            font-size: 14px;
        }
        .urgent-notice {
            background: #fee2e2;
            border-left: 4px solid #dc2626;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .urgent-notice h4 {
            color: #dc2626;
            margin-top: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔧 New Job Assignment</h1>
            <p>You have been assigned a new job</p>
        </div>

        <div class="content">
            <p>Hello <strong>{{ $technician->name }}</strong>,</p>
            
            <p>You have been assigned a new job. Please review the details below and start working on it as soon as possible.</p>

            @if($job->type === 'emergency')
                <div class="urgent-notice">
                    <h4>⚠️ URGENT - Emergency Job</h4>
                    <p>This is an emergency job that requires immediate attention. Please contact the customer as soon as possible.</p>
                </div>
            @endif

            <div class="job-details">
                <h3>📋 Job Details</h3>
                
                <div class="detail-item">
                    <span class="detail-label">Job Card:</span>
                    <span class="detail-value"><strong>{{ $job->job_card ?? 'TBD-' . $job->id }}</strong></span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Customer Name:</span>
                    <span class="detail-value">{{ $job->customer_name ?? $job->company_name }}</span>
                </div>

                @if($job->customer_phone)
                <div class="detail-item">
                    <span class="detail-label">Customer Phone:</span>
                    <span class="detail-value"><a href="tel:{{ $job->customer_phone }}">{{ $job->customer_phone }}</a></span>
                </div>
                @endif

                @if($job->customer_email)
                <div class="detail-item">
                    <span class="detail-label">Customer Email:</span>
                    <span class="detail-value"><a href="mailto:{{ $job->customer_email }}">{{ $job->customer_email }}</a></span>
                </div>
                @endif

                <div class="detail-item">
                    <span class="detail-label">Job Type:</span>
                    <span class="detail-value 
                        @if($job->type === 'emergency') priority-high
                        @elseif($job->type === 'maintenance') priority-medium
                        @else priority-normal
                        @endif">
                        {{ ucfirst($job->type ?? 'normal') }}
                        @if($job->type === 'emergency') 🚨 @endif
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Date Booked:</span>
                    <span class="detail-value">{{ $job->date_booked ? $job->date_booked->format('M j, Y') : 'Not set' }}</span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Amount:</span>
                    <span class="detail-value">${{ number_format($job->amount_charged ?? 0, 2) }}</span>
                </div>

                @if($job->fault_description)
                <div class="detail-item">
                    <span class="detail-label">Issue Description:</span>
                    <span class="detail-value">{{ $job->fault_description }}</span>
                </div>
                @endif

                @if($job->zimra_ref)
                <div class="detail-item">
                    <span class="detail-label">ZIMRA Reference:</span>
                    <span class="detail-value">{{ $job->zimra_ref }}</span>
                </div>
                @endif
            </div>

            <div style="text-align: center;">
                <a href="{{ $jobUrl }}" class="btn">
                    👀 View Job Details & Start Work
                </a>
            </div>

            <div style="background: #dbeafe; padding: 15px; border-radius: 6px; margin: 20px 0;">
                <h4 style="color: #1e40af; margin-top: 0;">📝 Next Steps:</h4>
                <ol style="color: #1e293b; margin: 0;">
                    <li>Click the button above to view full job details</li>
                    <li>Contact the customer if you need clarification</li>
                    <li>Update job status to "In Progress" when you start</li>
                    <li>Mark as complete when finished with your comments</li>
                </ol>
            </div>

            <p>If you have any questions about this assignment, please contact your supervisor immediately.</p>

            <p>Thank you for your prompt attention to this matter.</p>

            <p>Best regards,<br>
            <strong>{{ config('app.name', 'FiscalTech Solutions') }} Team</strong></p>
        </div>

        <div class="footer">
            <p>This is an automated notification. Please do not reply to this email.</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name', 'Fiscal Solutions') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
