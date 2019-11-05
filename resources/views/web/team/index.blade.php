@extends('web.layout.app')
@section('seo_title', $pageTitle)
@section('seo_description', 'WBG AG – Gestalterische und technische Wertbeständigkeit in sämtlichen Bereichen der visuellen Kommunikation.')
@section('content')
<section class="site-content site-content--team">
  <div class="team">
    @foreach($team as $t)
      <article>
        <h2>{{$t->firstname }} {{ $t->name }}@if ($t->role), {{ $t->role }}@endif</h2>
        <a href="tel:{{ $t->phone }}">{{ $t->phone }}</a>@if ($t->email) / <a href="mailto:{{ $t->email }}" title="E-Mail {{$t->firstname }} {{ $t->name }}">{{ $t->email }}</a>@endif
      </article>
    @endforeach
  </div>
</section>
@endsection