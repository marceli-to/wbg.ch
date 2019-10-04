<div class="box-2x1fr">
  <div class="box__b">
    <div>
      @if (isset($elements[0]))
        <img src="/media/{{$elements[0]->image->name}}/lg" height="560" width="430" alt="{{$elements[0]->image->caption}}">
      @endif
    </div>
  </div>
  <div>
    <div class="box__a">
      <div>
        @if (isset($elements[1]))
          <img src="/media/{{$elements[1]->image->name}}/lg" height="430" width="280" alt="{{$elements[1]->image->caption}}">
        @endif
      </div>
    </div>
    <div class="box__a">
      <div>
        @if (isset($elements[2]))
          <img src="/media/{{$elements[2]->image->name}}/lg" height="430" width="280" alt="{{$elements[2]->image->caption}}">
          @endif
      </div>
    </div>
  </div>
</div>