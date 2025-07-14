@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="{{ asset('assets/img/logo/logo-v2.png') }}" class="logo" alt="Fiscal Support Services Logo">
@else
{!! $slot !!}
@endif
</a>
</td>
</tr>
