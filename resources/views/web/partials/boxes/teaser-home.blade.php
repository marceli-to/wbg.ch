@if ($teaser->project->category_id == 3)
  <a href="/projekte/{{ $teaser->project->category_id }}/panoptikum/{{ $teaser->project->subcategory_id }}/-/!#{{\Str::slug($teaser->project->name)}}" 
    title="{{$teaser->project->name}}"
    class="hide-below-sm is-inline">
    <img class="lazyload" src="/assets/img/preview.png" data-src="{!! ImageHelper::get($teaser->name, isset($image_size) ? $image_size : 'lg') !!}" {!! $image_attribute !!} alt="{{ $teaser->caption ?: $teaser->client?->name ?: $teaser->project?->name }}">
  </a>
  <a href="/projekte/{{ $teaser->project->category_id }}/panoptikum!#{{\Str::slug($teaser->project->name)}}" 
    title="{{$teaser->project->name}}"
    class="hide-above-sm is-inline">
    <img class="lazyload" src="/assets/img/preview.png" data-src="{!! ImageHelper::get($teaser->name, isset($image_size) ? $image_size : 'lg') !!}" {!! $image_attribute !!} alt="{{ $teaser->caption ?: $teaser->client?->name ?: $teaser->project?->name }}">
  </a>
@else
  <a href="/projekt/{!! AppHelper::slug($teaser->project) !!}" 
    title="{{$teaser->project->name}}">
    <img class="lazyload" src="/assets/img/preview.png" data-src="{!! ImageHelper::get($teaser->name, isset($image_size) ? $image_size : 'lg') !!}" {!! $image_attribute !!} alt="{{ $teaser->caption ?: $teaser->client?->name ?: $teaser->project?->name }}">
  </a>
@endif
