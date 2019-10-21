<div class="box-1fr">
  <div>
    <div class="box__d">
      <div>
        @if (isset($elements[0]))
          <a href="projekt/{{$elements[0]->projectimage->project->id}}">
            <img src="{!! ImageHelper::get($elements[0]->projectimage->name, 'lg') !!}" height="280" width="860" alt="{{$elements[0]->projectimage->caption}}">
          </a>
        @endif
      </div>
    </div>
  </div>
</div>