<div class="box-2x1fr">
  <div>
    <div class="box__a">
      <div class="is-logo" @if ($elements[0]->image->client) data-scroll="{{ str_slug($elements[0]->image->client->name) }}"@endif>
        @if (isset($elements[0]))
          @if ($elements[0]->news)
            @include('web.partials.boxes.article', array('news' => $elements[0]->news))
          @endif
          @if ($elements[0]->image)
            @if ($elements[0]->image->url)
              <a href="{{$elements[0]->image->url}}" {!! AppHelper::linkTarget($elements[0]->image->url) !!} title="{{$elements[0]->image->caption}}">
                <img class="lazyload" data-src="{!! ImageHelper::get($elements[0]->image->name, 'md') !!}" height="280" width="430" alt="{{$elements[0]->image->caption}}">
              </a>
            @else
              <img class="lazyload" data-src="{!! ImageHelper::get($elements[0]->image->name, 'md') !!}" height="280" width="430" alt="{{$elements[0]->image->caption}}">
            @endif
          @endif
        @endif
      </div>
    </div>
  </div>
  <div>
    <div class="box__a">
      <div class="is-logo" @if ($elements[1]->image->client) data-scroll="{{ str_slug($elements[1]->image->client->name) }}"@endif>
        @if (isset($elements[1]))
          @if ($elements[1]->news)
            @include('web.partials.boxes.article', array('news' => $elements[1]->news))
          @endif
          @if ($elements[1]->image)
            @if ($elements[1]->image->url)
              <a href="{{$elements[1]->image->url}}" {!! AppHelper::linkTarget($elements[1]->image->url) !!} title="{{$elements[1]->image->caption}}">
                <img class="lazyload" data-src="{!! ImageHelper::get($elements[1]->image->name, 'md') !!}" height="280" width="430" alt="{{$elements[1]->image->caption}}">
              </a>
            @else
              <img class="lazyload" data-src="{!! ImageHelper::get($elements[1]->image->name, 'md') !!}" height="280" width="430" alt="{{$elements[1]->image->caption}}">
            @endif
          @endif
        @endif
      </div>
    </div>
  </div>
</div>