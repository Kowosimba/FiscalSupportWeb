<?php

use Illuminate\Console\Command;
use App\Models\Ticket;
use Carbon\Carbon;
use App\Models\User;
use App\Notifications\TechnicianOutstandingTicketNotification;


class RemindOutstandingTickets extends Command
{
    protected $signature = 'tickets:remind-technicians';
    protected $description = 'Remind technicians of outstanding tickets after 24 hours and escalate priority';

    public function handle()
    {
        $tickets = Ticket::where('status', 'in_progress')
            ->where('priority', '!=', 'high')
            ->whereNotNull('assigned_to')
            ->where('updated_at', '<=', Carbon::now()->subDay())
            ->get();

        foreach ($tickets as $ticket) {
            $ticket->priority = 'high';
            $ticket->save();

            $technician = User::find($ticket->assigned_to);
            if ($technician) {
                $technician->notify(new TechnicianOutstandingTicketNotification($ticket));
            }
        }
    }
}
