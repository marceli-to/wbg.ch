@extends('web.layout.app')
@section('seo_title', $project->name . ' - '. $project->category->name)
@section('seo_description', substr(strip_tags($project->meta_description),0,255))
@section('og_image', url('/') . ImageHelper::get($ogImage, 'lg'))
@section('content')
<section class="site-content site-content--project">
  <div class="project-detail">
    <nav class="project-browse">
      <a href="/projekt/{!! AppHelper::slug($browse['prev']) !!}" rel="canonical" class="icon-browse is-prev">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 25 20"><path d="m12.2 0-9.3 9.2h22.1v1.6h-22.1l9.3 9.2h-2.2l-10-10 10-10z"/></svg>
      </a>
      <a href="/projekt/{!! AppHelper::slug($browse['next']) !!}" rel="canonical" class="icon-browse is-next">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 25 20"><polygon points="12.8 0 22.1 9.2 0 9.2 0 10.8 22.1 10.8 12.8 20 15 20 25 10 15 0 12.8 0"/></svg>
      </a>
    </nav>
    <header class="project-header">
      <div class="span">
        <h2>{{ $project->name }}</h2>
      </div>
      <div class="span">
        <a href="javascript:;" onclick="window.history.back();" class="icon-back" title="Zurück">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 10 10"><g style="isolation:isolate"><polygon points="9.4 0 5 4.4 0.6 0 0 0.6 4.4 5 0 9.4 0.6 10 5 5.6 9.4 10 10 9.4 5.6 5 10 0.6 9.4 0"/></g></svg>
        </a>
      </div>
    </header>
    <div class="ratio-boxes">
      @if ($project->video)
        <div class="box-1fr">
          <div>
            <div class="video-container">
              <video class="video" src="/assets/video/{{ $project->video }}" autoplay muted></video>
            </div>
          </div>
        </div>
      @endif
      @foreach($grids as $g)
        @if ($g['key'] == '1fr')
          @if (isset($g['elements']))
            @include('web.partials.boxes.project.1fr', array('elements' => $g['elements']))
          @endif
        @endif
        @if ($g['key'] == '1fr-1fr_stacked')
          @if (isset($g['elements']))
            @include('web.partials.boxes.project.1fr-1fr_stacked', array('elements' => $g['elements']))
          @endif
        @endif
        @if ($g['key'] == '1fr_stacked-1fr')
          @if (isset($g['elements']))
            @include('web.partials.boxes.project.1fr_stacked-1fr', array('elements' => $g['elements']))
          @endif
        @endif
        @if ($g['key'] == '1fr-wide')
          @if (isset($g['elements']))
            @include('web.partials.boxes.project.1fr-wide', array('elements' => $g['elements']))
          @endif
        @endif
        @if ($g['key'] == '2x1fr-wide')
          @if (isset($g['elements']))
            @include('web.partials.boxes.project.2x1fr-wide', array('elements' => $g['elements']))
          @endif
        @endif
        @if ($g['key'] == '2x1fr')
          @if (isset($g['elements']))
            @include('web.partials.boxes.project.2x1fr', array('elements' => $g['elements']))
          @endif
        @endif
        @if ($g['key'] == '1fr-portrait')
          @if (isset($g['elements']))
            @include('web.partials.boxes.project.1fr-portrait', array('elements' => $g['elements']))
          @endif
        @endif
        @if ($g['key'] == '2x1fr-logo')
          @if (isset($g['elements']))
            <div class="logo-boxes">
              @include('web.partials.boxes.project.2x1fr-logo', array('elements' => $g['elements']))
            </div>
          @endif
        @endif
      @endforeach
    </div>
    @if ($project->description)
      <div class="project-info">
        <article class="project-description">
          {!! $project->description !!}
        </article>
        <div class="project-relations js-project-relations">
          @if ($project->relations)
            @foreach($project->relations as $relation)
              <article class="project-relation">
                <h3>{{ $relation->related->name }}</h3>
                @if ($relation->related->category_id == 3)
                  <a href="/projekte/{{ $relation->related->category_id }}/panoptikum/{{ $relation->related->subcategory_id }}/-/!#{{str_slug($relation->related->name)}}" 
                    rel="canonical"
                    class="icon-arrow-light hide-below-sm"
                    title="{{$relation->related->category->name}}">
                    {{$relation->related->category->name}}
                  </a>
                  <a href="/projekte/{{ $relation->related->category_id }}/panoptikum!#{{str_slug($relation->related->name)}}" 
                    rel="canonical"
                    class="icon-arrow-light hide-above-sm"
                    title="{{$relation->related->category->name}}">
                    {{$relation->related->category->name}}
                  </a>
                @else
                  <a href="/projekt/{!! AppHelper::slug($relation->related) !!}" 
                    rel="canonical"
                    class="icon-arrow-light" 
                    title="{{$relation->related->category->name}}">
                    {{$relation->related->category->name}}
                  </a>
                @endif                
                @foreach($relation->related->images as $img)
                  @if ($loop->first)
                    <figure>
                      @if ($relation->related->category_id == 3)
                        <a href="/projekte/{{ $relation->related->category_id }}/panoptikum/{{ $relation->related->subcategory_id }}/-/!#{{str_slug($relation->related->name)}}" 
                          rel="canonical"
                          class="hide-below-sm"
                          title="{{$relation->related->category->name}}">
                          <img src="{!! ImageHelper::related($img->name) !!}" height="430" width="280" alt="{{$img->caption}}">
                        </a>
                        <a href="/projekte/{{ $relation->related->category_id }}/panoptikum!#{{str_slug($relation->related->name)}}" 
                          rel="canonical"
                          class="hide-above-sm"
                          title="{{$relation->related->category->name}}">
                          <img src="{!! ImageHelper::related($img->name) !!}" height="430" width="280" alt="{{$img->caption}}">
                        </a>
                      @else
                        <a href="/projekt/{!! AppHelper::slug($relation->related) !!}" 
                          rel="canonical"
                          title="{{$relation->related->category->name}}">
                          <img src="{!! ImageHelper::related($img->name) !!}" height="430" width="280" alt="{{$img->caption}}">
                        </a>
                      @endif  
                    </figure>
                  @endif
                @endforeach
              </article>
            @endforeach
          @endif
        </div>
      </div>
    @endif
  </div>
</section>
@endsection