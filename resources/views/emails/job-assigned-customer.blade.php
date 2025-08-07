@component('mail::message')
# 👋 Technician Assigned to Your Job

Hello **{{ $jobDetails['customer_name'] }}**,

Your service request **{{ $jobDetails['reference'] }}** has been assigned to:

**{{ $technician->name }}**
- 📧 [Email Technician](mailto:{{ $technician->email }})

## Job Summary

@component('mail::table')
| Detail           | Information                           |
|:-----------------|:--------------------------------------|
| **Reference**    | {{ $jobDetails['reference'] }}        |
| **Service Type** | {{ $jobDetails['type'] }}             |
| **Description**  | {{ Str::limit($jobDetails['description'], 50) }} |
| **Amount**       | {{ $jobDetails['currency'] }} ${{ $jobDetails['amount'] }} |
@endcomponent

When you are ready, you can contact your assigned technician by email to schedule a time for assistance, or call our support line and ask for them.

@component('mail::button', ['url' => 'tel:' . str_replace(' ', '', $supportContact['phone']), 'color' => 'green'])
📞 Call Support +2638677187169
@endcomponent

We’re here to help—please reach out if you have any questions.

Thanks,<br>
**{{ $companyName }} Support Team**
@endcomponent
