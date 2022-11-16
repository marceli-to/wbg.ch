<article>
  <div>
    @if ($news->title)
      <h2>{{ $news->title }}</h2>
    @endif
    @if ($news->text)
      <p>{!! nl2br(e($news->text)) !!}</p>
    @endif
    @if ($news->link)
      @if (strpos($news->link, '@') !== false)
        <a href="mailto:{{ $news->link }}" {{ $news->linkNewWindow ? 'target="_blank"' : '' }} class="icon-arrow">
          @if ($news->linkText) {{ $news->linkText }} @else Mehr @endif
        </a>
      @else
        <a href="{{ $news->link }}" {{ $news->linkNewWindow ? 'target="_blank"' : '' }} class="icon-arrow">
          @if ($news->linkText) {{ $news->linkText }} @else Mehr @endif
        </a>
      @endif
    @elseif ($news->linkInternal)
      <a href="{{ route('profile.competences') }}/!#{{ str_slug($news->competence->title)}}" class="icon-arrow">
        @if ($news->linkText){{ $news->linkText }} @else Mehr @endif
      </a>
    @endif
  </div>
</article>