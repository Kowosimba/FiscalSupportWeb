<?php

namespace App\Mail;

use App\Models\CallLog;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Carbon\Carbon;

class JobAssignedNotificationToCustomer extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $callLog;
    public $technician;
    public $jobDetails;
    public $estimatedCompletion;
    public $supportContact;

    public function __construct(CallLog $callLog, User $technician)
    {
        $this->callLog = $callLog;
        $this->technician = $technician;
        
        // Prepare job details for the email
        $this->prepareJobDetails();
        
        // Calculate estimated completion date
        $this->calculateEstimatedCompletion();
        
        // Set support contact information
        $this->supportContact = [
            'phone' => config('company.support_phone', '+263 XXX XXXX'),
            'email' => config('company.support_email', 'support@fiscaltech.co.zw'),
            'hours' => config('company.support_hours', 'Monday - Friday, 8:00 AM - 5:00 PM')
        ];
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $jobReference = $this->callLog->job_card ?? "#{$this->callLog->id}";
        
        return new Envelope(
            subject: "✅ Technician Assigned - Job {$jobReference}",
            from: config('company.support_email', config('mail.from.address')),
            replyTo: $this->technician->email ?? config('company.support_email')
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.job-assigned-customer',
            with: [
                'companyName' => config('app.name', 'FiscalTech Solutions'),
                'companyWebsite' => config('app.url', 'https://fiscaltech.co.zw'),
                'urgencyLevel' => $this->getUrgencyLevel(),
                'isEmergency' => $this->callLog->type === 'emergency'
            ]
        );
    }

    /**
     * Prepare comprehensive job details
     */
    private function prepareJobDetails(): void
    {
        $this->jobDetails = [
            'reference' => $this->callLog->job_card ?? "#{$this->callLog->id}",
            'description' => $this->callLog->fault_description,
            'type' => ucfirst($this->callLog->type),
            'amount' => number_format($this->callLog->amount_charged, 2),
            'currency' => $this->callLog->currency,
            'booked_date' => Carbon::parse($this->callLog->date_booked)->format('l, F j, Y'),
            'created_date' => $this->callLog->created_at->format('F j, Y \a\t g:i A'),
            'status' => ucfirst($this->callLog->status),
            'customer_name' => $this->callLog->customer_name,
            'customer_email' => $this->callLog->customer_email,
            'customer_phone' => $this->callLog->customer_phone,
            'company_name' => $this->callLog->company_name,
        ];
    }

    /**
     * Calculate estimated completion date
     */
    private function calculateEstimatedCompletion(): void
    {
        $baseDate = Carbon::parse($this->callLog->date_booked);
        
        // Add business days based on job type
        $businessDays = match($this->callLog->type) {
            'emergency' => 1,
            'repair' => 2,
            'maintenance' => 3,
            'installation' => 5,
            'consultation' => 2,
            default => 3
        };
        
        $this->estimatedCompletion = $baseDate->addWeekdays($businessDays)->format('l, F j, Y');
    }

    /**
     * Get urgency level description
     */
    private function getUrgencyLevel(): string
    {
        return match($this->callLog->type) {
            'emergency' => 'High Priority - Same Day Service',
            'repair' => 'Standard Priority - 2-3 Business Days',
            'maintenance' => 'Normal Priority - 3-5 Business Days',
            'installation' => 'Scheduled Service - 5-7 Business Days',
            'consultation' => 'Standard Priority - 2-3 Business Days',
            default => 'Standard Priority - 3-5 Business Days'
        };
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
