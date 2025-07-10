<?php
// app/Mail/JobAssignedNotification.php

namespace App\Mail;

use App\Models\CallLog;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class JobAssignedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $jobCard;
    public $recipient;

    public function __construct(CallLog $jobCard, string $recipient)
    {
        $this->jobCard = $jobCard;
        $this->recipient = $recipient;
    }

    public function build()
    {
        if ($this->recipient === 'engineer') {
            return $this->subject('New Job Assignment - ' . $this->jobCard->job_card)
                        ->view('emails.job-assigned-engineer')
                        ->with([
                            'jobCard' => $this->jobCard,
                            'engineerName' => $this->jobCard->engineer,
                            'companyName' => $this->jobCard->company_name,
                            'faultDescription' => $this->jobCard->fault_description,
                            'jobType' => ucfirst($this->jobCard->type),
                            'dateBooked' => $this->jobCard->date_booked,
                            'amountCharged' => $this->jobCard->amount_charged,
                            'zimraRef' => $this->jobCard->zimra_ref
                        ]);
        } else {
            return $this->subject('Engineer Assigned to Your IT Support Request - ' . $this->jobCard->job_card)
                        ->view('emails.job-assigned-customer')
                        ->with([
                            'jobCard' => $this->jobCard,
                            'companyName' => $this->jobCard->company_name,
                            'engineerName' => $this->jobCard->engineer,
                            'faultDescription' => $this->jobCard->fault_description,
                            'jobType' => ucfirst($this->jobCard->type),
                            'dateBooked' => $this->jobCard->date_booked,
                            'estimatedAmount' => $this->jobCard->amount_charged,
                            'zimraRef' => $this->jobCard->zimra_ref
                        ]);
        }
    }
}
