<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Completion Notification</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f0fdf4;
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
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0 0 10px 0;
            font-size: 28px;
            font-weight: 700;
        }
        .header .completion-badge {
            background: rgba(255,255,255,0.2);
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            display: inline-block;
            font-weight: 600;
        }
        .content {
            padding: 30px;
        }
        .greeting {
            color: #1f2937;
            font-size: 16px;
            margin-bottom: 25px;
        }
        .completion-notice {
            background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
            padding: 25px;
            border-radius: 12px;
            border-left: 5px solid #22c55e;
            margin: 25px 0;
            text-align: center;
        }
        .completion-notice h2 {
            color: #15803d;
            margin: 0 0 10px 0;
            font-size: 24px;
        }
        .completion-notice p {
            color: #166534;
            margin: 0;
            font-size: 16px;
        }
        .job-summary {
            background: #f8fafc;
            padding: 25px;
            border-radius: 8px;
            margin: 25px 0;
        }
        .job-summary h3 {
            color: #1f2937;
            margin-top: 0;
            margin-bottom: 15px;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: 600;
            color: #6b7280;
        }
        .detail-value {
            color: #1f2937;
            font-weight: 500;
        }
        .technician-info {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            padding: 20px;
            border-radius: 8px;
            margin: 25px 0;
        }
        .technician-info h3 {
            color: #1e40af;
            margin-top: 0;
            margin-bottom: 15px;
        }
        .tech-contact {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 10px 0;
        }
        .contact-btn {
            display: inline-block;
            padding: 8px 16px;
            background: #3b82f6;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .contact-btn:hover {
            background: #2563eb;
            transform: translateY(-1px);
        }
        .satisfaction-section {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            padding: 25px;
            border-radius: 8px;
            margin: 25px 0;
            text-align: center;
        }
        .satisfaction-section h3 {
            color: #92400e;
            margin-top: 0;
            margin-bottom: 15px;
        }
        .rating-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin: 20px 0;
            flex-wrap: wrap;
        }
        .rating-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }
        .rating-excellent {
            background: #22c55e;
            color: white;
        }
        .rating-good {
            background: #3b82f6;
            color: white;
        }
        .rating-needs-improvement {
            background: #f59e0b;
            color: white;
        }
        .support-section {
            background: #f1f5f9;
            padding: 25px;
            border-radius: 8px;
            margin: 25px 0;
        }
        .support-section h3 {
            color: #334155;
            margin-top: 0;
            margin-bottom: 15px;
        }
        .support-options {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin: 20px 0;
        }
        .support-option {
            background: white;
            padding: 15px;
            border-radius: 6px;
            text-align: center;
            border: 1px solid #e2e8f0;
        }
        .support-icon {
            font-size: 24px;
            margin-bottom: 10px;
        }
        .footer {
            background: #f8fafc;
            padding: 25px;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
        }
        .footer-logo {
            font-size: 18px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 10px;
        }
        @media (max-width: 600px) {
            .container {
                margin: 10px;
                border-radius: 0;
            }
            .content {
                padding: 20px;
            }
            .detail-row {
                flex-direction: column;
            }
            .tech-contact {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            .rating-buttons {
                flex-direction: column;
                align-items: center;
            }
            .support-options {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Job Completed Successfully!</h1>
            <div class="completion-badge">
                {{ now()->format('l, F j, Y') }}
            </div>
        </div>

        <div class="content">
            <div class="greeting">
                Dear <strong>{{ $job->customer_name ?? $job->company_name }}</strong>,
            </div>

            <div class="completion-notice">
                <h2>✅ Work Complete</h2>
                <p>We're pleased to inform you that your technical service request has been successfully completed!</p>
            </div>

            <div class="job-summary">
                <h3>📋 Job Summary</h3>
                
                <div class="detail-row">
                    <span class="detail-label">Job Reference:</span>
                    <span class="detail-value">{{ $job->job_card ?? 'TBD-' . $job->id }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Service Type:</span>
                    <span class="detail-value">{{ ucfirst($job->type ?? 'Technical Service') }}</span>
                </div>
                
                @if($job->fault_description)
                <div class="detail-row">
                    <span class="detail-label">Issue Addressed:</span>
                    <span class="detail-value">{{ $job->fault_description }}</span>
                </div>
                @endif
                
                <div class="detail-row">
                    <span class="detail-label">Date Completed:</span>
                    <span class="detail-value">{{ $job->date_resolved ? $job->date_resolved->format('l, F j, Y') : now()->format('l, F j, Y') }}</span>
                </div>
                
                @if($job->engineer_comments)
                <div class="detail-row">
                    <span class="detail-label">Work Performed:</span>
                    <span class="detail-value">{{ $job->engineer_comments }}</span>
                </div>
                @endif
            </div>

            @if($technician)
            <div class="technician-info">
                <h3>👨‍🔧 Your Service Technician</h3>
                <p>Your service was handled by <strong>{{ $technician->name }}</strong>, one of our qualified technicians.</p>
                
                @if($technician->email)
                <div class="tech-contact">
                    <span><strong>Technician Email:</strong> {{ $technician->email }}</span>
                    <a href="mailto:{{ $technician->email }}?subject=Follow-up for Job {{ $job->job_card ?? $job->id }}" class="contact-btn">
                        📧 Contact Technician
                    </a>
                </div>
                @endif
                
            </div>
            @endif

            <div class="satisfaction-section">
                <h3>⭐ How was our service?</h3>
                <p>We'd love to hear about your experience! Your feedback helps us improve our services.</p>
                
                <div class="rating-buttons">
                    <a href="mailto:{{ $companyEmail }}?subject=Excellent Service - Job {{ $job->job_card ?? $job->id }}&body=I had an excellent experience with your service for job {{ $job->job_card ?? $job->id }}. " class="rating-btn rating-excellent">
                        😊 Excellent
                    </a>
                    <a href="mailto:{{ $companyEmail }}?subject=Good Service - Job {{ $job->job_card ?? $job->id }}&body=I had a good experience with your service for job {{ $job->job_card ?? $job->id }}. " class="rating-btn rating-good">
                        👍 Good
                    </a>
                    <a href="mailto:{{ $companyEmail }}?subject=Service Feedback - Job {{ $job->job_card ?? $job->id }}&body=I have some feedback about the service for job {{ $job->job_card ?? $job->id }}. " class="rating-btn rating-needs-improvement">
                        💭 Feedback
                    </a>
                </div>
            </div>

            <div class="support-section">
                <h3>🤝 Need Further Assistance?</h3>
                <p>If you're experiencing any issues or need additional support, we're here to help! Don't hesitate to reach out through any of these convenient options:</p>
                
                <div class="support-options">
                    <div class="support-option">
                        <div class="support-icon">📧</div>
                        <h4>Email Support</h4>
                        <a href="mailto:{{ $companyEmail }}?subject=Support Request - Job {{ $job->job_card ?? $job->id }}" class="contact-btn">
                            Send Email
                        </a>
                    </div>
                    
                    <div class="support-option">
                        <div class="support-icon">📞</div>
                        <h4>Phone Support</h4>
                        <a href="tel:+263292270666" class="contact-btn">
                            Call Us: +263292270666/70668
                        </a>
                    
                    @if($technician && $technician->email)
                    <div class="support-option">
                        <div class="support-icon">👨‍🔧</div>
                        <h4>Direct Technician</h4>
                        <a href="mailto:{{ $technician->email }}?subject=Follow-up Question - Job {{ $job->job_card ?? $job->id }}" class="contact-btn">
                            Contact {{ $technician->name }}
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            <div style="background: #ecfdf5; padding: 20px; border-radius: 8px; border-left: 4px solid #22c55e; margin: 25px 0;">
                <h4 style="color: #15803d; margin-top: 0;">🌟 Thank You!</h4>
                <p style="color: #166534; margin-bottom: 0;">
                    Thank you for choosing <strong>{{ $companyName }}</strong> for your technical needs. 
                    We appreciate your business and look forward to serving you again in the future.
                </p>
            </div>

            <div style="background: #f0f9ff; padding: 20px; border-radius: 8px; margin: 25px 0;">
                <h4 style="color: #0369a1; margin-top: 0;">🔧 Quality Guarantee</h4>
                <p style="color: #0c4a6e; margin-bottom: 0;">
                    All our work comes with a service guarantee. If you experience any issues related to the work performed, 
                    please contact us.
                </p>
            </div>

            <p style="color: #1f2937; font-size: 16px; margin-top: 30px;">
                Once again, thank you for your trust in our services. We're always here when you need us!
            </p>

            <p style="margin-bottom: 0;">
                Best regards,<br>
                <strong>{{ $companyName }} Team</strong>
            </p>
        </div>

        <div class="footer">
            <div class="footer-logo">{{ $companyName }}</div>
            <p>Professional Technical Services & Support</p>
            <p style="margin: 15px 0;">
                📧 {{ $companyEmail }} | 📞 {{ $companyPhone }}
            </p>
            <p style="font-size: 12px; margin-top: 20px;">
                This is an automated notification. For support inquiries, please use the contact methods provided above.
            </p>
            <p style="font-size: 12px;">&copy; {{ date('Y') }} {{ $companyName }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
