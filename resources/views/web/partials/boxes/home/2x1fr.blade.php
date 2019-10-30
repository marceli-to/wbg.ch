<div class="box-2x1fr">
    <div>
      <div class="box__b">
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
                <img class="lazyload" data-src="{!! ImageHelper::get($elements[0]->projectimage->name, 'lg') !!}" height="560" width="430" alt="{{$elements[0]->projectimage->caption}}">
              </a>
            @endif
          @endif
        </div>
      </div>
    </div>
    <div>
      <div class="box__b">
        <div>
          @if (isset($elements[1]))
            @if ($elements[1]->news)
              @include('web.partials.boxes.article', array('news' => $elements[1]->news))
            @endif
            @if ($elements[1]->projectimage)
              @if ($elements[1]->projectimage->project->category_id == 3)
                <a href="/projekte/{{ $elements[1]->projectimage->project->category_id }}/panoptikum/{{ $elements[1]->projectimage->project->subcategory_id }}/-/!#{{str_slug($elements[1]->projectimage->project->name)}}" 
                  rel="canonical" 
                  title="{{$elements[1]->projectimage->project->name}}">
              @else
                <a href="/projekt/{!! AppHelper::slug($elements[1]->projectimage->project) !!}" 
                  rel="canonical" 
                  title="{{$elements[1]->projectimage->project->name}}">
              @endif
                <img class="lazyload" data-src="{!! ImageHelper::get($elements[1]->projectimage->name, 'lg') !!}" height="560" width="430" alt="{{$elements[1]->projectimage->caption}}">
              </a>
            @endif
          @endif
         </div>
      </div>
    </div>
  </div>