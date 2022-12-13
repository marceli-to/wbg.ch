@if ($teaser->url)
  <a href="{{$teaser->url}}" {!! AppHelper::linkTarget($teaser->url) !!} title="{{$teaser->caption}}">
    <img class="lazyload" src="/assets/img/preview.png" data-src="{!! ImageHelper::get($teaser->name, isset($image_size) ? $image_size : 'lg') !!}" height="430" width="280" alt="{{$teaser->caption}}">
  </a>
@else

{{-- <picture>
  <source media="(min-width: 1024px)" data-srcset="{!! ImageHelper::get($teaser->name, 'lg') !!}" srcset="/assets/img/preview.png">        
  <source media="(min-width: 720px)" data-srcset="{!! ImageHelper::get($teaser->name, 'md') !!}" srcset="/assets/img/preview.png">
  <source data-srcset="{!! ImageHelper::get($teaser->name, 'lg') !!}" srcset="/assets/img/preview.png">
  <img src="/assets/img/preview.png" data-src="{!! ImageHelper::get($teaser->name, 'sm') !!}" alt="{{$teaser->caption}}" title="{{$teaser->caption}}"  height="430" width="280" class="is-responsive lazyload">
</picture> --}}


  <img class="lazyload" src="/assets/img/preview.png" data-src="{!! ImageHelper::get($teaser->name, isset($image_size) ? $image_size : 'lg') !!}" height="430" width="280" alt="{{$teaser->caption}}">
@endif