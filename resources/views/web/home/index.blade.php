@extends('web.layout.app')
@section('content')
<section class="site-content">
  @if ($intro)
    <div class="intro">{!! $intro->text !!}</div>
  @endif
  <div class="ratio-boxes">
    @foreach($grids as $g)
      @if ($g['key'] == '1fr')
        @if (isset($g['elements']))
          @include('web.partials.boxes.home.1fr', array('elements' => $g['elements']))
        @endif
      @endif
      @if ($g['key'] == '1fr-1fr_stacked')
        @if (isset($g['elements']))
          @include('web.partials.boxes.home.1fr-1fr_stacked', array('elements' => $g['elements']))
        @endif
      @endif
      @if ($g['key'] == '1fr_stacked-1fr')
        @if (isset($g['elements']))
          @include('web.partials.boxes.home.1fr_stacked-1fr', array('elements' => $g['elements']))
        @endif
      @endif
      @if ($g['key'] == '1fr-wide')
        @if (isset($g['elements']))
          @include('web.partials.boxes.home.1fr-wide', array('elements' => $g['elements']))
        @endif
      @endif
      @if ($g['key'] == '2x1fr-wide')
        @if (isset($g['elements']))
          @include('web.partials.boxes.home.2x1fr-wide', array('elements' => $g['elements']))
        @endif
      @endif
      @if ($g['key'] == '2x1fr')
        @if (isset($g['elements']))
          @include('web.partials.boxes.home.2x1fr', array('elements' => $g['elements']))
        @endif
      @endif
      @if ($g['key'] == '1fr-portrait')
        @if (isset($g['elements']))
          @include('web.partials.boxes.home.1fr-portrait', array('elements' => $g['elements']))
        @endif
      @endif
    @endforeach
  </div>
</section>
@endsection