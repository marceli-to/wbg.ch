@extends('web.layout.app')
@section('seo_title', $pageTitle)
@section('seo_description', $metaDescription)
@section('content')
<section class="site-content site-content--list">
  <div class="project-detail project-detail--panoptikum">
    @foreach($projects as $project)
      <header class="project-header hide-below-sm">
        <h2>{{ $project['title'] }}</h2>
      </header>
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
</section>
@endsection