<div class="box-2x1fr">
  <div>
    <div class="box__a">
      <div>
        @if (isset($elements[0]))
          <img src="/media/{{$elements[0]->image->name}}/lg" height="280" width="430" alt="{{$elements[0]->image->caption}}">
        @endif
      </div>
    </div>
  </div>
  <div>
    <div class="box__a">
      <div>
        @if (isset($elements[1]))
          <img src="/media/{{$elements[1]->image->name}}/lg" height="280" width="430" alt="{{$elements[1]->image->caption}}">
        @endif
      </div>
    </div>
  </div>
</div>