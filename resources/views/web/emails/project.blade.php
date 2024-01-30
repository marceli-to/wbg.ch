@component('mail::message')
<div style="padding: 20px 0; line-height: 1.75">
@foreach($data as $d)
{{ $d['from'] }}: {{ $d['to'] }}<br>
@endforeach
</div>
<div class="footer">WBG AG<br>Visuelle&nbsp;Kommunikation<br>Binzstrasse&nbsp;39<br>CH-8045&nbsp;Zürich<br>+41&nbsp;44&nbsp;269&nbsp;43&nbsp;43<br><a href="mailto:mail@wbg.ch" style="color: #646464;text-decoration:none">mail@wbg.ch</a></div>
@endcomponent