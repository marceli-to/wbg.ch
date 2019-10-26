<div class="box-2x1fr">
  <div>
    <div class="box__a">
      <div>
        @if (isset($elements[0]))
          @if ($elements[0]->news)
            @include('web.partials.boxes.article', array('news' => $elements[0]->news))
          @endif
          @if ($elements[0]->image)
            @if ($elements[0]->image->url)
              <a href="{{$elements[0]->image->url}}" target="_blank" title="{{$elements[0]->image->caption}}">
                <img src="{!! ImageHelper::get($elements[0]->image->name, 'lg') !!}" height="430" width="280" alt="{{$elements[0]->image->caption}}">
              </a>
            @else
              <img src="{!! ImageHelper::get($elements[0]->image->name, 'lg') !!}" height="430" width="280" alt="{{$elements[0]->image->caption}}">
            @endif
          @endif
        @endif
      </div>
    </div>
    <div class="box__a">
      <div>
        @if (isset($elements[1]))
          @if ($elements[1]->news)
            @include('web.partials.boxes.article', array('news' => $elements[1]->news))
          @endif
          @if ($elements[1]->image)
            @if ($elements[1]->image->url)
              <a href="{{$elements[1]->image->url}}" target="_blank" title="{{$elements[1]->image->caption}}">
                <img src="{!! ImageHelper::get($elements[1]->image->name, 'lg') !!}" height="430" width="280" alt="{{$elements[1]->image->caption}}">
              </a>
            @else
              <img src="{!! ImageHelper::get($elements[1]->image->name, 'lg') !!}" height="430" width="280" alt="{{$elements[1]->image->caption}}">
            @endif
          @endif
        @endif
      </div>
    </div>
  </div>
  <div class="box__b">
    <div>
      @if (isset($elements[2]))
        @if ($elements[2]->news)
          @include('web.partials.boxes.article', array('news' => $elements[2]->news))
        @endif
        @if ($elements[2]->image)
          @if ($elements[2]->image->url)
            <a href="{{$elements[2]->image->url}}" target="_blank" title="{{$elements[2]->image->caption}}">
              <img src="{!! ImageHelper::get($elements[2]->image->name, 'lg') !!}" height="560" width="430" alt="{{$elements[2]->image->caption}}">
            </a>
          @else
            <img src="{!! ImageHelper::get($elements[2]->image->name, 'lg') !!}" height="560" width="430" alt="{{$elements[2]->image->caption}}">
          @endif
        @endif
      @endif
    </div>
  </div>
</div>