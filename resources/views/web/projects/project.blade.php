@extends('web.layout.app')
@section('content')
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
      <a href="javascript:;" onclick="window.history.back();" class="icon-back">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 10 10"><g style="isolation:isolate"><polygon points="9.4 0 5 4.4 0.6 0 0 0.6 4.4 5 0 9.4 0.6 10 5 5.6 9.4 10 10 9.4 5.6 5 10 0.6 9.4 0"/></g></svg>
      </a>
    </div>
  </header>
  <div class="ratio-boxes">
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
    @endforeach
  </div>
  <div class="project-info">
    <h3>{{ $project->name }}</h3>
    {!! $project->description !!}
  </div>
</div>
@endsection