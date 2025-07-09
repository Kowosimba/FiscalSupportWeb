@component('mail::message')
# Technician Assigned to Your Service Request

Hello {{ $job->customer_name }},

Great news! We have assigned a technician to your service request.

**Job Card:** {{ $job->job_card }}  
**Service Type:** {{ ucfirst($job->type) }}  
**Priority:** {{ ucfirst($job->priority) }}

## Assigned Technician
**Name:** {{ $job->assignedTo->name }}  
**Email:** {{ $job->assignedTo->email }}  

Your assigned technician will contact you directly to schedule the service. You can also reach out to them using the contact information above.

## Service Details
{{ $job->fault_description }}

We appreciate your business and will ensure your service request is completed promptly.

@component('mail::button', ['url' => 'mailto:' . $job->assignedTo->email])
Contact Technician
@endcomponent

If you have any questions, please don't hesitate to contact us.

Best regards,<br>
{{ config('app.name') }}
@endcomponent
