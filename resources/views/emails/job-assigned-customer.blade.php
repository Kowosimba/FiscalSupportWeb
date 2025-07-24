<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Assignment Update</title>
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
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
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
        .job-summary {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .technician-info {
            background: #dbeafe;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #3b82f6;
        }
        .technician-info h3 {
            color: #1d4ed8;
            margin-top: 0;
        }
        .contact-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 10px 0;
        }
        .contact-btn {
            display: inline-block;
            padding: 8px 16px;
            background: #22c55e;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
            font-weight: bold;
        }
        .contact-btn:hover {
            background: #16a34a;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #64748b;
            font-size: 14px;
        }
        .priority-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .priority-emergency {
            background: #fee2e2;
            color: #dc2626;
        }
        .priority-normal {
            background: #d1fae5;
            color: #059669;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>👨‍🔧 Technician Assigned</h1>
            <p>Your job has been assigned to a qualified technician</p>
        </div>

        <div class="content">
            <p>Dear <strong>{{ $job->customer_name ?? $job->company_name }}</strong>,</p>
            
            <p>We are pleased to inform you that your service request has been assigned to one of our qualified technicians who will be assisting you with your technical needs.</p>

            <div class="job-summary">
                <h3>📋 Your Job Summary</h3>
                <p><strong>Job Reference:</strong> {{ $job->job_card ?? 'TBD-' . $job->id }}</p>
                <p><strong>Service Type:</strong> 
                    <span class="priority-badge priority-{{ $job->type === 'emergency' ? 'emergency' : 'normal' }}">
                        {{ ucfirst($job->type ?? 'normal') }}
                    </span>
                </p>
                @if($job->fault_description)
                    <p><strong>Issue Description:</strong> {{ $job->fault_description }}</p>
                @endif
                <p><strong>Date Scheduled:</strong> {{ $job->date_booked ? $job->date_booked->format('l, M j, Y') : 'To be confirmed' }}</p>
            </div>

            <div class="technician-info">
                <h3>👨‍🔧 Your Assigned Technician</h3>
                <p><strong>Name:</strong> {{ $technician->name }}</p>
                
                @if($technician->email)
                <div class="contact-info">
                    <span><strong>Email:</strong> {{ $technician->email }}</span>
                    <a href="mailto:{{ $technician->email }}?subject=Job {{ $job->job_card ?? $job->id }} - {{ $job->customer_name }}" class="contact-btn">
                        📧 Email Technician
                    </a>
                </div>
                @endif

                @if($technician->phone)
                <div class="contact-info">
                    <span><strong>Phone:</strong> {{ $technician->phone }}</span>
                    <a href="tel:{{ $technician->phone }}" class="contact-btn">
                        📞 Call Technician
                    </a>
                </div>
                @endif
            </div>

            @if($job->type === 'emergency')
                <div style="background: #fee2e2; padding: 15px; border-radius: 6px; border-left: 4px solid #dc2626; margin: 20px 0;">
                    <h4 style="color: #dc2626; margin-top: 0;">🚨 Priority Service</h4>
                    <p style="margin-bottom: 0; color: #7f1d1d;">This is marked as an emergency service. Our technician will contact you as soon as possible to schedule immediate assistance.</p>
                </div>
            @endif

            <div style="background: #d1fae5; padding: 15px; border-radius: 6px; margin: 20px 0;">
                <h4 style="color: #047857; margin-top: 0;">💡 What happens next?</h4>
                <ul style="color: #065f46; margin-bottom: 0;">
                    <li>The assigned technician will contact you shortly to confirm the appointment</li>
                    <li>For urgent matters, feel free to contact the technician directly using the information above</li>
                    <li>Our technician will arrive at the scheduled time and resolve your technical issue</li>
                    <li>You will receive a completion notification once the work is finished</li>
                </ul>
            </div>

            <div style="background: #fff3cd; padding: 15px; border-radius: 6px; border-left: 4px solid #f59e0b; margin: 20px 0;">
                <h4 style="color: #92400e; margin-top: 0;">📞 Need immediate assistance?</h4>
                <p style="margin-bottom: 0; color: #78350f;">
                    For the fastest response, please contact your assigned technician directly using the contact information provided above. 
                    They are best equipped to help you with your specific technical needs.
                </p>
            </div>

            <p>We appreciate your business and look forward to resolving your technical issue promptly.</p>

            <p>Best regards,<br>
            <strong>{{ $companyName }} Customer Service Team</strong></p>
        </div>

        <div class="footer">
            <p><strong>{{ $companyName }}</strong></p>
            <p>Professional Technical Services & Support</p>
            <p style="margin-top: 15px; font-size: 12px;">
                This is an automated notification. For support, please contact your assigned technician or our main office.
            </p>
        </div>
    </div>
</body>
</html>
