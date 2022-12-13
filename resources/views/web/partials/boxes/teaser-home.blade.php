@if ($teaser->project->category_id == 3)
  <a href="/projekte/{{ $teaser->project->category_id }}/panoptikum/{{ $teaser->project->subcategory_id }}/-/!#{{str_slug($teaser->project->name)}}" 
    rel="canonical" 
    title="{{$teaser->project->name}}"
    class="hide-below-sm is-inline">
    <img class="lazyload" src="/assets/img/preview.png" data-src="{!! ImageHelper::get($teaser->name, isset($image_size) ? $image_size : 'lg') !!}" {{ $image_attribute }} alt="{{$teaser->caption}}">
  </a>
  <a href="/projekte/{{ $teaser->project->category_id }}/panoptikum!#{{str_slug($teaser->project->name)}}" 
    rel="canonical" 
    title="{{$teaser->project->name}}"
    class="hide-above-sm is-inline">
    <img class="lazyload" src="/assets/img/preview.png" data-src="{!! ImageHelper::get($teaser->name, isset($image_size) ? $image_size : 'lg') !!}" {{ $image_attribute }} alt="{{$teaser->caption}}">
  </a>
@else
  <a href="/projekt/{!! AppHelper::slug($teaser->project) !!}" 
    rel="canonical" 
    title="{{$teaser->project->name}}">
    <img class="lazyload" src="/assets/img/preview.png" data-src="{!! ImageHelper::get($teaser->name, isset($image_size) ? $image_size : 'lg') !!}" {{ $image_attribute }} alt="{{$teaser->caption}}">
  </a>
@endif
