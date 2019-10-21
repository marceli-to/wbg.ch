<div class="box-2x1fr">
  <div>
    <div class="box__a">
      <div>
        @if (isset($elements[0]))
          <a href="projekt/{{$elements[0]->projectimage->project->id}}">
            <img src="{!! ImageHelper::get($elements[0]->projectimage->name, 'lg') !!}" height="280" width="430" alt="{{$elements[0]->projectimage->caption}}">
          </a>
        @endif
      </div>
    </div>
  </div>
  <div>
    <div class="box__a">
      <div>
        @if (isset($elements[1]))
          <a href="projekt/{{$elements[1]->projectimage->project->id}}">
            <img src="{!! ImageHelper::get($elements[1]->projectimage->name, 'lg') !!}" height="280" width="430" alt="{{$elements[1]->projectimage->caption}}">
          </a>
        @endif
      </div>
    </div>
  </div>
</div>