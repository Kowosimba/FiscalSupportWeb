@component('mail::message')
# Job Status Update

Hello {{ $job->customer_name }},

Your service request status has been updated.

**Job Card:** {{ $job->job_card }}  
**Status:** {{ ucfirst(str_replace('_', ' ', $job->status)) }}  
**Last Updated:** {{ $job->updated_at->format('M j, Y - H:i') }}

@if($job->status === 'completed')
## Job Completed!
Your service request has been completed successfully.

**Total Amount:** ${{ number_format($job->amount_charged, 2) }}  
@if($job->billed_hours)
**Hours Worked:** {{ $job->billed_hours }}  
@endif
@if($job->engineer_comments)
**Technician Notes:** {{ $job->engineer_comments }}  
@endif

@elseif($job->status === 'in_progress')
## Work in Progress
Our technician is currently working on your service request.

@elseif($job->status === 'assigned')
## Technician Assigned
Your job has been assigned to: {{ $job->assignedTo->name }}

@endif

Thank you for choosing our services.

Best regards,<br>
{{ config('app.name') }}
@endcomponent
