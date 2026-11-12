@if ($teaser->url)
  <a href="{{$teaser->url}}" {!! AppHelper::linkTarget($teaser->url) !!} title="{{$teaser->caption}}">
    <img class="lazyload" src="/assets/img/preview.png" data-src="{!! ImageHelper::get($teaser->name, isset($image_size) ? $image_size : 'lg') !!}" height="430" width="280" alt="{{ $teaser->caption ?: $teaser->client?->name ?: $teaser->project?->name }}">
  </a>
@else
  <img class="lazyload" src="/assets/img/preview.png" data-src="{!! ImageHelper::get($teaser->name, isset($image_size) ? $image_size : 'lg') !!}" height="430" width="280" alt="{{ $teaser->caption ?: $teaser->client?->name ?: $teaser->project?->name }}">
@endif