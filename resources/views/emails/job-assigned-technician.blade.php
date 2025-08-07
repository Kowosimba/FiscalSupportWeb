@component('mail::message')
# 🔔 New Job Assigned

Hello **{{ $technicianName }}**,

You have been assigned a new job: **{{ $jobReference }}**.

## Job Overview

@component('mail::table')
| Detail           | Information                  |
|:-----------------|:-----------------------------|
| **Customer**     | {{ $jobDetails['customer'] }} |
| **Type**         | {{ $jobDetails['type'] }}     |
| **Booked Date**  | {{ $jobDetails['booked_date'] }} |
| **Amount**       | {{ $jobDetails['currency'] }} ${{ $jobDetails['amount'] }} |
@endcomponent

## Description

{{ Str::limit($jobDetails['description'], 100) }}

@component('mail::button', ['url' => $jobUrl])
View Job Details
@endcomponent

Please review the job and proceed with the next steps. Log into the admin panel for more information.
@endcomponent
