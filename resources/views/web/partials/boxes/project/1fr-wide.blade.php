<div class="box-1fr">
  <div>
    <div class="box__d">
      <div>
        @if (isset($elements[0]))
          @if ($elements[0]->news)
            @include('web.partials.boxes.article', array('news' => $elements[0]->news))
          @endif
          @if ($elements[0]->image)
            @include('web.partials.boxes.teaser-project', array('teaser' => $elements[0]->image, 'image_attribute' => 'height="280" width="860"', 'image_size' => 'lg'))
          @endif
        @endif
      </div>
    </div>
  </div>
</div>