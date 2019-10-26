<article>
  <div>
    @if ($news->title)
      <h2>{{ $news->title }}</h2>
    @endif
    @if ($news->text)
      <p>{!! nl2br(e($news->text)) !!}</p>
    @endif
    @if ($news->link)
      <a href="{{ $news->link }}" {{ $news->linkNewWindow ? 'target="_blank"' : '' }} class="icon-arrow">
        @if ($news->linkText)
          {{ $news->linkText }}
        @else
          Mehr
        @endif
      </a>
    @endif
  </div>
</article>