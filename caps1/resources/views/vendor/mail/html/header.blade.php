@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'PUP-UNISAN-LIBRARY')

<img src='https://i.postimg.cc/dkKskc2k/final-logo.png' alt="pupunisan" border="0" style="width: 100px;">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
