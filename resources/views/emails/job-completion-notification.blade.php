@php
use Illuminate\Support\Str;
@endphp
@component('mail::message')
# ✅ Job Completed Successfully

Hello **{{ $callLog->customer_name }}**,

We’re pleased to inform you that your job **{{ $jobReference }}** has been completed on {{ $completionDate }}.

## Service Details

@component('mail::table')
| Detail              | Information                         |
|:--------------------|:------------------------------------|
| **Job Reference**   | {{ $jobReference }}                 |
| **Service Type**    | {{ $jobDetails['type'] }}           |
| **Service Amount**  | {{ $jobDetails['currency'] }} ${{ $jobDetails['amount'] }} |
@endcomponent

## Work Performed

{{ Str::limit($jobDetails['description'], 100) }}

@component('mail::panel')
**Assigned Technician:**  
**{{ $technicianName }}**  
📧 [Email Technician](mailto:{{ $technicianEmail }})
@endcomponent

## Next Steps

If you have any questions or need follow-up service, please:

- **Call Support:** +2638677187169

Thank you for trusting **{{ $companyName }}**. We appreciate your business and look forward to serving you again.

Best regards,<br>
**{{ $companyName }}** Service Team

@endcomponent
