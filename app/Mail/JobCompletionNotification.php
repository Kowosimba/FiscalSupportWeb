<?php

namespace App\Mail;

use App\Models\CallLog;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class JobCompletionNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $callLog;
    public $technician;

    public function __construct(CallLog $callLog)
    {
        $this->callLog = $callLog;
        $this->technician = $callLog->assignedTo;
    }

    public function build()
    {
        return $this->subject('✅ Job Completed - ' . ($this->callLog->job_card ?? 'Job #' . $this->callLog->id))
                    ->view('emails.job-completion-notification')
                    ->with([
                        'job' => $this->callLog,
                        'technician' => $this->technician,
                        'companyName' => config('app.name', 'FiscalTech Solutions'),
                        'companyPhone' => config('company.phone', '+263 XX XXX XXXX'),
                        'companyEmail' => config('company.email', config('mail.from.address'))
                    ]);
    }
}
