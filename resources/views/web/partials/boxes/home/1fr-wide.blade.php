<div class="box-1fr">
  <div>
    <div class="box__d">
      <div>
        @if (isset($elements[0]))
          @if ($elements[0]->news)
            @include('web.partials.boxes.article', array('news' => $elements[0]->news))
          @endif
          @if ($elements[0]->projectimage)
            @if ($elements[0]->projectimage->project->category_id == 3)
              <a href="/projekte/{{ $elements[0]->projectimage->project->category_id }}/panoptikum/{{ $elements[0]->projectimage->project->subcategory_id }}/-/!#{{str_slug($elements[0]->projectimage->project->name)}}" 
                rel="canonical" 
                title="{{$elements[0]->projectimage->project->name}}">
            @else
              <a href="/projekt/{!! AppHelper::slug($elements[0]->projectimage->project) !!}" 
                rel="canonical" 
                title="{{$elements[0]->projectimage->project->name}}">
            @endif
              <img class="lazyload" data-src="{!! ImageHelper::get($elements[0]->projectimage->name, 'lg') !!}" height="280" width="860" alt="{{$elements[0]->projectimage->caption}}">
            </a>
          @endif
        @endif
      </div>
    </div>
  </div>
</div>