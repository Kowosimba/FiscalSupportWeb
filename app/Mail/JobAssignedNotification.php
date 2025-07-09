<?php
// app/Mail/JobAssignedNotification.php

namespace App\Mail;

use App\Models\Job;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class JobAssignedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $job;
    public $recipient;

    public function __construct(Job $job, string $recipient)
    {
        $this->job = $job;
        $this->recipient = $recipient;
    }

    public function build()
    {
        if ($this->recipient === 'technician') {
            return $this->subject('New Job Assignment - ' . $this->job->job_card)
                        ->view('emails.job-assigned-technician');
        } else {
            return $this->subject('Technician Assigned to Your Service Request')
                        ->view('emails.job-assigned-customer');
        }
    }
}
