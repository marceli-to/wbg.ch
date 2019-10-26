<div class="box-2x1fr">
  <div class="box__b">
    <div>
      @if (isset($elements[0]))
        @if ($elements[0]->news)
          @include('web.partials.boxes.article', array('news' => $elements[0]->news))
        @endif
        @if ($elements[0]->projectimage)
          <a href="/projekt/{!! AppHelper::slug($elements[0]->projectimage->project) !!}" rel="canonical" title="{{$elements[0]->projectimage->project->name}}">
            <img class="lazyload" data-src="{!! ImageHelper::get($elements[0]->projectimage->name, 'lg') !!}" height="560" width="430" alt="{{$elements[0]->projectimage->caption}}">
          </a>
        @endif
      @endif
    </div>
  </div>
  <div>
    <div class="box__a">
      <div>
        @if (isset($elements[1]))
          @if ($elements[1]->news)
            @include('web.partials.boxes.article', array('news' => $elements[1]->news))
          @endif
          @if ($elements[1]->projectimage)
            <a href="/projekt/{!! AppHelper::slug($elements[1]->projectimage->project) !!}" rel="canonical" title="{{$elements[1]->projectimage->project->name}}">
              <img class="lazyload" data-src="{!! ImageHelper::get($elements[1]->projectimage->name, 'lg') !!}" height="430" width="280" alt="{{$elements[1]->projectimage->caption}}">
            </a>
          @endif
        @endif
      </div>
    </div>
    <div class="box__a">
      <div>
        @if (isset($elements[2]))
          @if ($elements[2]->news)
            @include('web.partials.boxes.article', array('news' => $elements[2]->news))
          @endif
          @if ($elements[2]->projectimage)
            <a href="/projekt/{!! AppHelper::slug($elements[2]->projectimage->project) !!}" rel="canonical" title="{{$elements[2]->projectimage->project->name}}">
              <img class="lazyload" data-src="{!! ImageHelper::get($elements[2]->projectimage->name, 'lg') !!}" height="430" width="280" alt="{{$elements[2]->projectimage->caption}}">
            </a>
          @endif
        @endif
      </div>
    </div>
  </div>
</div>