<?php

namespace App\Services;

class RecaptchaService
{
    public function isHuman(string $recaptchaResponse, string $ip): bool
    {
        $secretKey = config('recaptcha.secret_key');
        if (empty($secretKey)) {
            return false;
        }

        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = [
            'secret' => $secretKey,
            'response' => $recaptchaResponse,
            'remoteip' => $ip,
        ];

        $options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data),
            ],
        ];

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $response = json_decode($result, true);

        return $response['success'] ?? false;
    }
}