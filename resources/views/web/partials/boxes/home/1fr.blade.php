<div class="box-1fr">
  <div>
    <div class="box__c">
      <div>
        @if (isset($elements[0]))
          <a href="/projekt/{!! AppHelper::slug($elements[0]->projectimage->project) !!}" rel="canonical" title="{{$elements[0]->projectimage->project->name}}">
            <img class="lazyload" data-src="{!! ImageHelper::get($elements[0]->projectimage->name, 'lg') !!}" height="560" width="860" alt="{{$elements[0]->projectimage->caption}}">
          </a>
        @endif
      </div>
    </div>
  </div>
</div>