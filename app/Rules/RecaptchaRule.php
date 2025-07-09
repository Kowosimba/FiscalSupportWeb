<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;  // Add this import

class RecaptchaRule implements Rule
{
    /**
     * Determine if the validation rule passes.
     */
    public function passes($attribute, $value)
    {
        if (!config('services.recaptcha.enabled')) {
            return true;
        }

        if (empty($value)) {
            return false;
        }

        try {
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => config('services.recaptcha.secret_key'),
                'response' => $value,
                'remoteip' => request()->ip(),
            ]);

            $result = $response->json();
            
            return isset($result['success']) && $result['success'] === true;
        } catch (\Exception $e) {
            // Now Log will work correctly
            Log::error('reCAPTCHA verification failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get the validation error message.
     */
    public function message()
    {
        return 'Please verify that you are not a robot.';
    }
}
