@extends('web.layout.app')
@section('seo_title', $pageTitle)
@section('seo_description', $metaDescription)
@section('content')
@if ($isPanoptikum)
<section class="site-content site-content--list">
  <div class="hide-above-sm">
    <div class="project-detail project-detail--panoptikum">
      @foreach($mobileProjects as $project)
        {{-- <header class="project-header">
          <h2>{{ $project['title'] }}</h2>
        </header> --}}
        <div class="ratio-boxes" data-scroll="{{ \Str::slug($project['title']) }}">
          @foreach($project['grid'] as $g)
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
          @endforeach
        </div>
      @endforeach
    </div>
  </div>
  <div class="hide-below-sm">
    <div class="project-list project-list--panoptikum">
      @foreach($projects as $key => $project)
        @foreach($project->previewImages as $image)
          @if (isset($subCategories[$project->subcategory_id]))
            <figure class="project-teaser">
              <a href="/projekte/{{ $project->category_id }}/panoptikum/{{ $project->subcategory_id }}/{{ mb_strtolower($subCategories[$project->subcategory_id], 'UTF-8') }}/!#{{\Str::slug($project->name)}}" title="{{$project->name}}">
                <img src="/assets/img/preview.png" data-src="{!! ImageHelper::preview($image->name) !!}" class="lazyload" height="512" width="380" alt="{{$project->name}}">
              </a>
            </figure>
          @endif
        @endforeach
      @endforeach
    </div>
  </div>
</section>
@else
  @include('web.partials.projects.list', array('projects' => $projects))
@endif
@endsection