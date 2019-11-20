@extends('web.layout.app')
@section('seo_title', $pageTitle)
@section('content')
<section class="site-content">
  <article class="content-lg">
    {!! $content->text !!}
  </article>
</section>
@endsection