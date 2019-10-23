<div class="project-list">
  @foreach($projects as $project)
    @foreach($project->previewImages as $image)
      <figure class="project-teaser">
        <h2>{{ $project->name }}</h2>
        <a href="/projekt/{!! AppHelper::slug($project) !!}" title="{{$project->name}}">
          <img src="/assets/img/preview.png" data-src="{!! ImageHelper::preview($image->name) !!}" class="lazyload" height="512" width="380" alt="{{$project->name}}">
        </a>
      </figure>
    @endforeach
  @endforeach
</div>