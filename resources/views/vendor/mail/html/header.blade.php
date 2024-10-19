@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="https://newway-manga.s3.ap-southeast-1.amazonaws.com/logos/01JAACCQ51Z94SAZC3WJGB4H7Y.png" class="logo" alt="New Way Logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
