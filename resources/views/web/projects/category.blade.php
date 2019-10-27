@extends('web.layout.app')
@section('seo_title', $pageTitle)
@section('seo_description', '')
@section('content')
@if ($isPanoptikum)
<section class="site-content site-content--list">
  <div class="project-list project-list--panoptikum">
    @foreach($projects as $key => $projectSub)
      <h2>{{$subCategories[$key]}}</h2>
      @foreach($projectSub as $project)
        @foreach($project->previewImages as $image)
          <figure class="project-teaser">
            <a href="/projekte/{{ $project->category_id }}/panoptikum/{{ $project->subcategory_id }}/{{ mb_strtolower($subCategories[$key], 'UTF-8') }}/!#{{str_slug($project->name)}}" title="{{$project->name}}">
              <img src="/assets/img/preview.png" data-src="{!! ImageHelper::preview($image->name) !!}" class="lazyload" height="512" width="380" alt="{{$project->name}}">
            </a>
          </figure>
        @endforeach
      @endforeach
    @endforeach
  </div>
</section>
@else
  @include('web.partials.projects.list', array('projects' => $projects))
@endif
@endsection