<div class="box-2x1fr">
  <div>
    <div class="box__a">
      <div>
        @if (isset($elements[0]))
          @if ($elements[0]->news)
            @include('web.partials.boxes.article', array('news' => $elements[0]->news))
          @endif
          @if ($elements[0]->image)
            @include('web.partials.boxes.teaser-project', array('teaser' => $elements[0]->image, 'image_attribute' => 'height="430" width="280"', 'image_size' => 'md'))
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
            @include('web.partials.boxes.teaser-project', array('teaser' => $elements[1]->image, 'image_attribute' => 'height="430" width="280"', 'image_size' => 'md'))
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
          @include('web.partials.boxes.teaser-project', array('teaser' => $elements[2]->image, 'image_attribute' => 'height="560" width="430"', 'image_size' => 'md'))
        @endif
      @endif
    </div>
  </div>
</div>