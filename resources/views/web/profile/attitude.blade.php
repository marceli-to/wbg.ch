@extends('web.layout.app')
@section('seo_title', $pageTitle)
@section('seo_description', 'Wer wirklich etwas zu sagen hat, braucht nicht zu schreien – auch grafisch nicht.')
@section('content')
<section class="site-content">
  <article class="content-lg">
    {!! $content->text !!}
  </article>
</section>
@endsection