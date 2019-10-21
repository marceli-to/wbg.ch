<div class="box-1fr">
  <div>
    <div class="box__d">
      <div>
        @if (isset($elements[0]))
          <img src="{!! ImageHelper::get($elements[0]->image->name, 'lg') !!}" height="280" width="860" alt="{{$elements[0]->image->caption}}">
        @endif
      </div>
    </div>
  </div>
</div>