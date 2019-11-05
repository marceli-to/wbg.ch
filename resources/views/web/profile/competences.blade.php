@extends('web.layout.app')
@section('seo_title', $pageTitle)
@section('seo_description', 'Logo- und Markenentwicklung, Signaletik/Orientierungs- und Leitsysteme, Architekten- und Bauherrenberatung, Editorial-Design und Buchgestaltung, Digital und Online, Informationsgrafik, Piktogrammentwicklung, Bildkonzepte etc.')
@section('content')
<section class="site-content">
  <div class="competences">
    <article class="content-lg competence-intro">
      {!! $content->text !!}
    </article>
    @if ($competences)
      @foreach($competences as $competence)
        <article class="competence" data-competence="{{ str_slug($competence->title) }}">
          <div>
            <div class="competence__text">
              <h2>{{ $competence->title }}</h2>

              <div>
                {!! AppHelper::nl2p($competence->description) !!}
                @if ($competence->category)
                  @if ($competence->category->id == $category->panoptikumId && $competence->subcategory_id)
                  <a href="/projekte/{{ $competence->category->id }}/{{str_slug($competence->category->name)}}/{{$competence->subcategory_id}}/{{str_slug($category->subcategories[$competence->subcategory_id])}}" class="icon-arrow-lg">
                    {{ $competence->category->name }}
                  </a>
                  @else
                    <a href="/projekte/{{ $competence->category->id }}/{{str_slug($competence->category->name)}}" class="icon-arrow-lg">
                      {{ $competence->category->name }}
                    </a>
                  @endif
                @endif
              </div>
            </div>
            <div class="competence__media">
              @if ($competence->id == 4)
                <figure class="media-digital-online">
                  <div><img src="/assets/media/WBG_Digital-und-Online.svg" height="200" width="200" alt="Digital und Online"></div>
                </figure>
              @else
                @foreach($competence->media as $media)
                  @if ($loop->count > 1)
                    @if($loop->first)
                      <a href="{!! ImageHelper::get($media->name, 'lg') !!}" title="{{ $media->caption }}" data-fancybox="gallery">
                        <img src="{!! ImageHelper::get($media->name, 'lg') !!}" height="560" width="430" alt="{{ $media->caption }}">
                      </a>
                    @else
                      <a href="{!! ImageHelper::get($media->name, 'lg') !!}" data-fancybox="gallery"></a>
                    @endif
                  @else
                    <img src="{!! ImageHelper::get($media->name, 'lg') !!}" height="560" width="430" alt="{{ $media->caption }}">
                  @endif
                @endforeach
              @endif
            </div>
          </div>
        </article>
      @endforeach
    @endif
  </div>
</section>
@endsection