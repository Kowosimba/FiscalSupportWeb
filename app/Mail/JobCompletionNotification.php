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

class JobCompletionNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $callLog;
    public $technician;
    public $jobReference;
    public $jobDetails;
    public $completionDate;
    public $supportContact;

    public function __construct(CallLog $callLog)
    {
        $this->callLog = $callLog;
        $this->technician = $callLog->assignedTo;
        $this->jobReference = $callLog->job_card ?? "#{$callLog->id}";
        $this->completionDate = Carbon::parse($callLog->date_resolved ?? now())->format('l, F j, Y');

        $this->jobDetails = [
            'description' => $callLog->fault_description,
            'type'        => ucfirst($callLog->type),
            'amount'      => number_format($callLog->amount_charged, 2),
            'currency'    => $callLog->currency,
        ];

        $this->supportContact = [
            'phone' => config('company.support_phone', '+263 XX XXX XXXX'),
            'email' => config('company.support_email', config('mail.from.address')),
        ];
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "✅ Job Completed – {$this->jobReference}"
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.job-completion-notification',
            with: [
                'jobReference'   => $this->jobReference,
                'completionDate' => $this->completionDate,
                'technicianName' => $this->technician->name,
                'technicianEmail'=> $this->technician->email,
                'jobDetails'     => $this->jobDetails,
                'supportContact' => $this->supportContact,
                'companyName'    => config('app.name', 'FiscalTech Solutions'),
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
