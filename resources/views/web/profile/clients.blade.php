@extends('web.layout.app')
@section('seo_title', $pageTitle)
@section('seo_description', 'WBG AG – Gestalterische und technische Wertbeständigkeit in sämtlichen Bereichen der visuellen Kommunikation.')
@section('content')
<section class="site-content site-content--narrow">
  <div class="clients">
    @foreach($clients as $key => $client_group)
      <div class="client-group">
        <div class="client-index">{{$key}}</div>
        @foreach($client_group as $client)
          <div class="client">
            @if ($client->project)
              @if ($client->project->category_id == 3)
                <a 
                  href="/projekte/{{ $client->project->category_id }}/panoptikum/{{ $client->project->subcategory_id }}/-/!#{{\Str::slug($client->project->name)}}"
                  title="{{$client->project->name}}"
                  rel="canonical"
                  class="hide-below-sm">
                  {{$client->name}}@if ($client->location), {{$client->location}} @endif
                </a>
                <a 
                  href="/projekte/{{ $client->project->category_id }}/panoptikum!#{{\Str::slug($client->project->name)}}"
                  title="{{$client->project->name}}"
                  rel="canonical"
                  class="hide-above-sm">
                  {{$client->name}}@if ($client->location), {{$client->location}} @endif
                </a>
              @elseif ($client->project->is_brands)
                <a href="/projekt/{!! AppHelper::slug($client->project) !!}/!#{{\Str::slug($client->name)}}" title="{{$client->project->name}}" rel="canonical">
                  {{$client->name}}@if ($client->location), {{$client->location}} @endif
                </a>
              @else
                <a href="/projekt/{!! AppHelper::slug($client->project) !!}" title="{{$client->project->name}}" rel="canonical">
                  {{$client->name}}@if ($client->location), {{$client->location}} @endif
                </a>
              @endif
            @else
              {{$client->name}}@if ($client->location), {{$client->location}} @endif
            @endif
          </div>
        @endforeach
      </div>
    @endforeach
  </div>
</section>
@endsection