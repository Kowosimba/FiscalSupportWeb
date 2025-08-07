<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NewUserRegistered extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
        Log::info('NewUserRegistered mailable constructor called', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'activation_token' => $user->activation_token
        ]);
    }

    public function build()
    {
        Log::info('NewUserRegistered build method started', [
            'user_id' => $this->user->id,
            'user_email' => $this->user->email
        ]);

        if (empty($this->user->activation_token)) {
            Log::error("Activation token is missing for user", [
                'user_id' => $this->user->id,
                'user_email' => $this->user->email
            ]);
            throw new \Exception("Activation token is missing for user {$this->user->email}");
        }

        $adminEmail = env('ADMIN_EMAIL', 'supporthre2@fiscalsupportservices.com');
        Log::info('Admin email retrieved', ['admin_email' => $adminEmail]);

        try {
            $activationUrl = route('admin.users.activate', ['token' => $this->user->activation_token]);
            Log::info('Activation URL generated', ['activation_url' => $activationUrl]);
        } catch (\Exception $e) {
            Log::error('Failed to generate activation URL', [
                'error' => $e->getMessage(),
                'token' => $this->user->activation_token
            ]);
            throw $e;
        }

        $mailData = [
            'name' => $this->user->name,
            'role' => $this->user->role ?? 'user',
            'email' => $this->user->email,
            'activationUrl' => $activationUrl,
            'admin_email' => $adminEmail,
        ];

        Log::info('Mail data prepared', $mailData);

        $mail = $this->markdown('emails.new_user_registered')
                    ->subject("New User Registration: {$this->user->name}")
                    ->with($mailData);

        Log::info('NewUserRegistered build method completed successfully');
        
        return $mail;
    }
}