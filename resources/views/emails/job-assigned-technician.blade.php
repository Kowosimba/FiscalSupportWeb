@component('mail::message')
# New Job Assignment

Hello {{ $job->assignedTo->name }},

You have been assigned a new job. Here are the details:

**Job Card:** {{ $job->job_card }}  
**Customer:** {{ $job->customer_name }}  
**Priority:** {{ ucfirst($job->priority) }}  
**Amount:** ${{ number_format($job->amount_charged, 2) }}

## Job Description
{{ $job->fault_description }}

## Customer Contact
**Email:** {{ $job->customer_email }}  
@if($job->customer_phone)
**Phone:** {{ $job->customer_phone }}  
@endif
@if($job->customer_address)
**Address:** {{ $job->customer_address }}  
@endif

@component('mail::button', ['url' => route('jobs.show', $job)])
View Job Details
@endcomponent

Please contact the customer directly and begin work as soon as possible.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
