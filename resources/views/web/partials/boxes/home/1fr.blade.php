<div class="box-1fr">
  <div>
    <div class="box__c">
      <div>
        @if (isset($elements[0]))
          @if ($elements[0]->news)
            @include('web.partials.boxes.article', array('news' => $elements[0]->news))
          @endif
          @if ($elements[0]->projectimage)
            @include('web.partials.boxes.teaser-home', array('teaser' => $elements[0]->projectimage, 'image_attribute' => 'height="560" width="860"'))
          @endif
        @endif
      </div>
    </div>
  </div>
</div>