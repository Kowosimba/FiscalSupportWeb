<?php
// app/Mail/JobStatusUpdateNotification.php

namespace App\Mail;

use App\Models\Job;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class JobStatusUpdateNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $job;
    public $oldStatus;

    public function __construct(Job $job, string $oldStatus)
    {
        $this->job = $job;
        $this->oldStatus = $oldStatus;
    }

    public function build()
    {
        return $this->subject('Service Request Status Update - ' . $this->job->job_card)
                    ->view('emails.job-status-update');
    }
}
