@extends('web.layout.app')
@section('seo_title', $pageTitle)
@section('seo_description', '')
@section('content')
<section class="site-content">
  <div class="competences">
    <article class="content-lg competence-intro">
      {!! $content->text !!}
    </article>
    @if ($competences)
      @foreach($competences as $competence)
        <article class="competence">
          <h2>{{ $competence->title }}</h2>
          <div>
            <div class="competence__text">
              <div>
                {{-- <p>{!! nl2br(e($competence->description)) !!}</p> --}}
                {!! AppHelper::nl2p($competence->description) !!}
              </div>
            </div>
            <div class="competence__media">
              @foreach($competence->media as $media)
                @if ($loop->count > 1)
                  @if($loop->first)
                    <a href="{!! ImageHelper::get($media->name, 'lg') !!}" title="{{ $media->caption }}">
                      <img src="{!! ImageHelper::get($media->name, 'lg') !!}" height="560" width="430" alt="{{ $media->caption }}">
                    </a>
                  @else
                    <a href="{!! ImageHelper::get($media->name, 'lg') !!}"></a>
                  @endif
                @else
                  <img src="{!! ImageHelper::get($media->name, 'lg') !!}" height="560" width="430" alt="{{ $media->caption }}">
                @endif
              @endforeach
            </div>
          </div>
        </article>
      @endforeach
    @endif
  </div>
</section>
@endsection