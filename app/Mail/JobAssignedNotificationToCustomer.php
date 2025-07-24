<?php

namespace App\Mail;

use App\Models\CallLog;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class JobAssignedNotificationToCustomer extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $callLog;
    public $technician;

    public function __construct(CallLog $callLog, User $technician)
    {
        $this->callLog = $callLog;
        $this->technician = $technician;
    }

    public function build()
    {
        return $this->subject('Job Assignment Update - Job #' . ($this->callLog->job_card ?? $this->callLog->id))
                    ->view('emails.job-assigned-customer')
                    ->with([
                        'job' => $this->callLog,
                        'technician' => $this->technician,
                        'companyName' => config('app.name', 'Fiscal Support')
                    ]);
    }
}
