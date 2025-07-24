<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Mail\DailyUnassignedJobsReport;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendDailyUnassignedJobsReport extends Command
{
    protected $signature = 'jobs:send-daily-report {--test : Send test email to current user}';
    protected $description = 'Send daily unassigned jobs report to all managers';

    public function handle()
    {
        $this->info('Starting daily unassigned jobs report...');

        try {
            if ($this->option('test')) {
                // Send test email to first admin/manager for testing
                $testUser = User::whereIn('role', ['admin', 'manager'])->first();
                
                if (!$testUser) {
                    $this->error('No admin or manager users found for testing.');
                    return 1;
                }

                Mail::to($testUser->email)->send(new DailyUnassignedJobsReport($testUser));
                $this->info("Test email sent to: {$testUser->email}");
                
                return 0;
            }

            // Get all managers
            $managers = User::where('role', 'manager')->get();
            
            if ($managers->isEmpty()) {
                // If no managers, send to admins as fallback
                $managers = User::where('role', 'admin')->get();
                
                if ($managers->isEmpty()) {
                    $this->warn('No managers or admins found to send reports to.');
                    Log::warning('Daily report not sent - no managers or admins found');
                    return 1;
                }
            }

            $emailsSent = 0;
            $emailsFailed = 0;

            foreach ($managers as $manager) {
                try {
                    if ($manager->email) {
                        Mail::to($manager->email)->send(new DailyUnassignedJobsReport($manager));
                        $emailsSent++;
                        $this->info("Report sent to: {$manager->name} ({$manager->email})");
                        
                        Log::info('Daily unassigned jobs report sent', [
                            'manager_id' => $manager->id,
                            'manager_email' => $manager->email,
                            'sent_at' => now()
                        ]);
                    } else {
                        $this->warn("Manager {$manager->name} has no email address.");
                        $emailsFailed++;
                    }
                } catch (\Exception $e) {
                    $emailsFailed++;
                    $this->error("Failed to send report to {$manager->name}: {$e->getMessage()}");
                    
                    Log::error('Failed to send daily report', [
                        'manager_id' => $manager->id,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            }

            $this->info("Daily report completed. Sent: {$emailsSent}, Failed: {$emailsFailed}");
            
            Log::info('Daily unassigned jobs report completed', [
                'emails_sent' => $emailsSent,
                'emails_failed' => $emailsFailed,
                'total_managers' => $managers->count()
            ]);

            return 0;

        } catch (\Exception $e) {
            $this->error("Command failed: {$e->getMessage()}");
            Log::error('Daily report command failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return 1;
        }
    }
}
