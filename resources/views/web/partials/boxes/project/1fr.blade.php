<div class="box-1fr">
  <div>
    <div class="box__c">
      <div>
        @if (isset($elements[0]))
          @if ($elements[0]->news)
            @include('web.partials.boxes.article', array('news' => $elements[0]->news))
          @endif
          @if ($elements[0]->image)
            {{-- <picture>
              <source media="(min-width: 1024px)" data-srcset="{!! ImageHelper::get($elements[0]->image->name, 'lg') !!}" srcset="/assets/img/preview.png">        
              <source media="(min-width: 720px)" data-srcset="{!! ImageHelper::get($elements[0]->image->name, 'md') !!}" srcset="/assets/img/preview.png">
              <img src="/assets/img/preview.png" data-src="{!! ImageHelper::get($elements[0]->image->name, 'sm') !!}" alt="{{$elements[0]->image->caption}}" title="{{$elements[0]->image->caption}}"  height="430" width="280" class="is-responsive lazyload">
            </picture> --}}
            @include('web.partials.boxes.teaser-project', array('teaser' => $elements[0]->image, 'image_attribute' => 'height="560" width="860"', 'image_size' => 'lg'))
          @endif
        @endif
      </div>
    </div>
  </div>
</div>