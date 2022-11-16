@extends('web.layout.app')
@section('seo_title', $pageTitle)
@section('seo_description', 'WBG AG – Visuelle Kommunikation, Binzstrasse 39, CH-8045 Zürich')
@section('content')
<section class="site-content site-content--jobs">
  <div class="jobs">
    @foreach($jobs as $j)
      <article>
        <h2>{{$j->title}}</h2>
        {!! $j->text !!}
      </article>
    @endforeach
  </div>
</section>
@endsection
