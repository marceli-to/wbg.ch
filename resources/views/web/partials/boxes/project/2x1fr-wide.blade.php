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
                <img src="{!! ImageHelper::get($elements[0]->image->name, 'lg') !!}" height="280" width="430" alt="{{$elements[0]->image->caption}}">
              </a>
            @else
              <img src="{!! ImageHelper::get($elements[0]->image->name, 'lg') !!}" height="280" width="430" alt="{{$elements[0]->image->caption}}">
            @endif
          @endif
        @endif
      </div>
    </div>
  </div>
  <div>
    <div class="box__a">
      <div>
        @if (isset($elements[1]))
          @if ($elements[1]->news)
            @include('web.partials.boxes.article', array('news' => $elements[1]->news))
          @endif
          @if ($elements[1]->image)
            @if ($elements[1]->image->url)
              <a href="{{$elements[1]->image->url}}" target="_blank" title="{{$elements[1]->image->caption}}">
                <img src="{!! ImageHelper::get($elements[1]->image->name, 'lg') !!}" height="280" width="430" alt="{{$elements[1]->image->caption}}">
              </a>
            @else
              <img src="{!! ImageHelper::get($elements[1]->image->name, 'lg') !!}" height="280" width="430" alt="{{$elements[1]->image->caption}}">
            @endif
          @endif
        @endif
      </div>
    </div>
  </div>
</div>