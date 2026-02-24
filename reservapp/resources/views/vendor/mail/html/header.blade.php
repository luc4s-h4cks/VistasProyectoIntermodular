@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="https://i.imgur.com/WZ9OL47.png" class="logo" alt="ReservApp Logo" style="max-width: 200px;">
@else
{!! $slot !!}
@endif
</a>
</td>
</tr>
