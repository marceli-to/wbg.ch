@extends('web.layout.app')
@section('seo_title', $pageTitle)
@section('seo_description', '')
@section('content')
<section class="site-content">
  <div class="clients">
    @foreach($clients as $key => $client_group)
      <div class="client-group">
        <div class="client-index">{{$key}}</div>
        @foreach($client_group as $client)
          <div class="client">
            @if ($client->project_id)
              @if ($client->project->category_id == 3)
                <a 
                  href="/projekte/{{ $client->project->category_id }}/panoptikum/{{ $client->project->subcategory_id }}/-/!#{{str_slug($client->project->name)}}"
                  title="{{$client->project->name}}"
                  rel="canonical">
              @else
                <a href="/projekt/{!! AppHelper::slug($client->project) !!}" title="{{$client->project->name}}" rel="canonical">
              @endif
                {{$client->name}}@if ($client->location), {{$client->location}} @endif
              </a>
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