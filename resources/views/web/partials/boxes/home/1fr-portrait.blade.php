<div class="box-1fr">
  <div>
    <div class="box__e">
      <div>
        @if (isset($elements[0]))
          @if ($elements[0]->news)
            @include('web.partials.boxes.article', array('news' => $elements[0]->news))
          @endif
          @if ($elements[0]->projectimage)
            <a href="/projekt/{!! AppHelper::slug($elements[0]->projectimage->project) !!}" rel="canonical" title="{{$elements[0]->projectimage->project->name}}">
              <img class="lazyload" data-src="{!! ImageHelper::get($elements[0]->projectimage->name, 'lg') !!}" height="1120" width="860" alt="{{$elements[0]->projectimage->caption}}">
            </a>
          @endif
        @endif
      </div>
    </div>
  </div>
</div>