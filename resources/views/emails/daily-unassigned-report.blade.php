@component('mail::message')
{{-- Header --}}
# 📊 Daily Unassigned Jobs Report

Good {{ Carbon\Carbon::now()->format('H') < 12 ? 'Morning' : (Carbon\Carbon::now()->format('H') < 18 ? 'Afternoon' : 'Evening') }} **{{ $manager->name }}**,

{{ $reportDate }} Summary Report

{{-- Status Overview --}}
@if($unassignedCount > 0)

@component('mail::table')
| 📈 **OVERVIEW** | |
|:---|:---|
| Unassigned Jobs | **{{ $unassignedCount }}** |
| Emergency Priority | **{{ $emergencyCount }}** 🚨 |
| Overdue Items | **{{ $overdueCount }}** ⏰ |
| Revenue at Risk | **${{ number_format($totalPendingValue, 2) }}** 💰 |
@endcomponent

{{-- Alert Section --}}
@if($emergencyCount > 0 || $overdueCount > 0)
@component('mail::panel')
🚨 **URGENT ACTION REQUIRED**

@if($emergencyCount > 0)
• **{{ $emergencyCount }} Emergency Job{{ $emergencyCount > 1 ? 's' : '' }}** need immediate assignment
@endif
@if($overdueCount > 0)
• **{{ $overdueCount }} Overdue Job{{ $overdueCount > 1 ? 's' : '' }}** are past their scheduled date
@endif
@endcomponent
@endif

{{-- Recent Jobs --}}
@if($recentJobs->count() > 0)
## 📋 Recent Unassigned Jobs

@component('mail::table')
| Job | Customer | Type | Value | Days Pending |
|:----|:---------|:-----|:------|:-------------|
@foreach($recentJobs->take(5) as $job)
| **#{{ $job['id'] }}** | {{ Str::limit($job['customer_name'], 15) }} | {{ $job['type'] }}{{ $job['is_emergency'] ? ' 🚨' : '' }}{{ $job['is_overdue'] ? ' ⏰' : '' }} | {{ $job['currency'] }}${{ $job['amount'] }} | {{ $job['days_pending'] }} day{{ $job['days_pending'] != 1 ? 's' : '' }} |
@endforeach
@if($recentJobs->count() > 5)
| | | | | *+{{ $recentJobs->count() - 5 }} more jobs* |
@endif
@endcomponent
@endif

{{-- Action Buttons --}}
## 🎯 Quick Actions

@component('mail::button', ['url' => $dashboardUrl, 'color' => 'red'])
🔍 Review Unassigned Jobs ({{ $unassignedCount }})
@endcomponent

@else

{{-- Success State --}}
@component('mail::panel')
🎉 **EXCELLENT NEWS!**

All jobs are currently assigned. Your team is performing exceptionally well!

✅ **Zero Unassigned Jobs**  
✅ **Team Operating at Full Efficiency**  
✅ **No Immediate Action Required**
@endcomponent

@component('mail::button', ['url' => $allJobsUrl, 'color' => 'green'])
📊 View Dashboard
@endcomponent

@endif

---

**🔔 Report Settings:**
- Frequency: Daily at {{ Carbon\Carbon::now()->format('H:i') }}
- Scope: All unassigned jobs
- Generated: {{ Carbon\Carbon::now()->format('Y-m-d H:i:s T') }}

@endcomponent
