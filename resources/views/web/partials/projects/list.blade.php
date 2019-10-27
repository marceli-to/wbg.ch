<section class="site-content site-content--list">
  <div class="project-list">
    @foreach($projects as $project)
      @foreach($project->previewImages as $image)
        <figure class="project-teaser">
          <h2>{{ $project->name }}</h2>
          @if ($project->category_id == 3)
            <a href="/projekte/{{ $project->category_id }}/panoptikum/{{ $project->subcategory_id }}/{{ mb_strtolower($subCategories[$project->subcategory_id], 'UTF-8') }}/!#{{str_slug($project->name)}}" title="{{$project->name}}">
              <img src="/assets/img/preview.png" data-src="{!! ImageHelper::preview($image->name) !!}" class="lazyload" height="512" width="380" alt="{{$project->name}}">
            </a>
          @else
            <a href="/projekt/{!! AppHelper::slug($project) !!}" title="{{$project->name}}">
              <img src="/assets/img/preview.png" data-src="{!! ImageHelper::preview($image->name) !!}" class="lazyload" height="512" width="380" alt="{{$project->name}}">
            </a>
          @endif
        </figure>
      @endforeach
    @endforeach
  </div>
</section>