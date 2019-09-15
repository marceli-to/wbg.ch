@extends('web.layout.app')
@section('content')
<div style="padding-bottom: 40px">
  
    <style>
      .grid {
        display: grid;
        grid-template-columns: 1fr 2fr;
        grid-gap: 30px;
        margin-top: 30px;
      }
      .grid-team {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        grid-gap: 30px;
      }
      p {
        line-height: 1.25;
      }
      article {
      }
      img {
        display: block;
        height: auto;
        width: 100%;
      }
    </style>
    <div class="grid">
      <div>
        <h2 style="border-bottom: 1px solid #000">Über uns</h2>
        <p style="padding-top: 10px; font-size: 24px">Die Strut Architekten AG wächst 2015 aus dem bestehenden eingespielten Architekturbüro von Peter Kunz heraus. Eine vertrauensbildende Kontinuität wird erhalten und gleichzeitig ein chancenreicher Wandel ermöglicht. Die neue Geschäftsleitung bilden Roger Studerus und Felix Rutishauser. Peter Kunz arbeitet als Partner weiterhin bei Strut Architekten.</p>
      </div>
    </div>
    <div style="margin-top: 60px;">
      <h2>Team</h2>
      <div class="grid-team">
        @foreach($team as $t)
          <article style="border-top: 1px solid #000;padding-top: 10px">
            <p style="font-size: 24px; margin-bottom: 5px">
              {{$t->firstname}} {{$t->name}}<br>
              {{$t->role}}<br>
              {{$t->position}}<br>
            </p>
            <img src="/media/{{$t->media}}/sm" style="max-width: 50%;margin-bottom:5px">
            <p style="margin-bottom: 5px">
              @if ($t->phone)
                {{$t->phone}}<br>
              @endif
              {{$t->email}}<br>
            </p>
            <p style="margin-bottom: 5px">Lebenslauf</p>
            <div>
              {!! $t->cv !!}
            </div>
          </article>
        @endforeach
      </div>
    </div>
  </div>
@endsection