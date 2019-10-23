<div class="box-2x1fr">
    <div>
      <div class="box__b">
        <div>
          @if (isset($elements[0]))
            <a href="/projekt/{!! AppHelper::slug($elements[0]->projectimage->project) !!}" rel="canonical" title="{{$elements[0]->projectimage->project->name}}">
              <img class="lazyload" data-src="{!! ImageHelper::get($elements[0]->projectimage->name, 'lg') !!}" height="560" width="430" alt="{{$elements[0]->projectimage->caption}}">
            </a>
          @endif
        </div>
      </div>
    </div>
    <div>
      <div class="box__b">
        <div>
          @if (isset($elements[1]))
            <a href="/projekt/{!! AppHelper::slug($elements[1]->projectimage->project) !!}" rel="canonical" title="{{$elements[1]->projectimage->project->name}}">
              <img class="lazyload" data-src="{!! ImageHelper::get($elements[1]->projectimage->name, 'lg') !!}" height="560" width="430" alt="{{$elements[1]->projectimage->caption}}">
            </a>
          @endif
         </div>
      </div>
    </div>
  </div>