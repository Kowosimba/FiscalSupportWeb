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

class JobAssignedToTechnician extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $callLog;
    public $technician;
    public $jobReference;
    public $jobDetails;
    public $jobUrl;

    public function __construct(CallLog $callLog, User $technician)
    {
        $this->callLog = $callLog;
        $this->technician = $technician;
        $this->jobReference = $callLog->job_card ?? "#{$callLog->id}";
        $this->jobUrl = route('admin.call-logs.show', $callLog->id);

        $this->jobDetails = [
            'customer'     => $callLog->customer_name,
            'type'         => ucfirst($callLog->type),
            'description'  => $callLog->fault_description,
            'booked_date'  => $callLog->date_booked->format('M j, Y'),
            'amount'       => number_format($callLog->amount_charged, 2),
            'currency'     => $callLog->currency,
        ];
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "🔔 New Job Assignment – Job {$this->jobReference}"
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.job-assigned-technician',
            with: [
                'jobReference' => $this->jobReference,
                'technicianName' => $this->technician->name,
                'jobUrl' => $this->jobUrl,
                'jobDetails' => $this->jobDetails,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
