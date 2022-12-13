<div class="box-2x1fr">
  <div>
    <div class="box__a">
      <div>
        @if (isset($elements[0]))
          @if ($elements[0]->news)
            @include('web.partials.boxes.article', array('news' => $elements[0]->news))
          @endif
          @if ($elements[0]->image)
            @include('web.partials.boxes.teaser-project', array('teaser' => $elements[0]->image, 'image_attribute' => 'height="280" width="430"', 'image_size' => 'sm'))
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
            @include('web.partials.boxes.teaser-project', array('teaser' => $elements[1]->image, 'image_attribute' => 'height="280" width="430"', 'image_size' => 'sm'))
          @endif
        @endif
      </div>
    </div>
  </div>
</div>