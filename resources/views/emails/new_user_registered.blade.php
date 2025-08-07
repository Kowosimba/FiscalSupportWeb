@component('mail::message')
# New User Registration Pending Activation

A new user has registered with the following details:

@component('mail::panel')
- **Name:** {{ $name }}  
- **Email:** {{ $email }}  
- **Role:** {{ ucfirst($role) }}
@endcomponent

Please review and activate the user by clicking the button below:

@component('mail::button', ['url' => $activationUrl])
Activate User Account
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
