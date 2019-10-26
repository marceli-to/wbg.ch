<div class="box-1fr">
  <div>
    <div class="box__c">
      <div>
        @if (isset($elements[0]))
          @if ($elements[0]->news)
            @include('web.partials.boxes.article', array('news' => $elements[0]->news))
          @endif
          @if ($elements[0]->image)
            @if ($elements[0]->image->url)
              <a href="{{$elements[0]->image->url}}" target="_blank" title="{{$elements[0]->image->caption}}">
                <img src="{!! ImageHelper::get($elements[0]->image->name, 'lg') !!}" height="560" width="860" alt="{{$elements[0]->image->caption}}">
              </a>
            @else
              <img src="{!! ImageHelper::get($elements[0]->image->name, 'lg') !!}" height="560" width="860" alt="{{$elements[0]->image->caption}}">
            @endif
          @endif
        @endif
      </div>
    </div>
  </div>
</div>