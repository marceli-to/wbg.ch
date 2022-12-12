@extends('web.layout.app')
@section('seo_title', $pageTitle)
@section('seo_description', 'WBG AG – Visuelle Kommunikation, Binzstrasse 39, CH-8045 Zürich')
@section('content')
<section class="site-content site-content--contact">
  <div class="contact">
    <div class="contact-grid">
      <div class="span">
        <address>
          <h2>WBG AG – Visuelle Kommunikation</h2>
          <p>Binzstrasse 39, CH-8045 Zürich, <span class="nobr"><a href="tel:+41 44 269 43 43">+41 44 269 43 43</a>,</span> <a href="mailto:mail@wbg.ch">mail@wbg.ch</a></p>
          <p><span class="nobr">UID: CHE-103.423.461</span> / <span class="nobr">IBAN: CH 06 00225 22584124701N</span></p>
        </address>
      </div>
      <div class="span">
        <a href="https://goo.gl/maps/9dCKFMFqaiaYUudK7" class="btn-social is-maps" target="_blank" rel="noopener" title="Google Maps">Google Maps</a>
        <a href="https://www.linkedin.com/company/wbgag/about/?viewAsMember=true" target="_blank" class="btn-social is-linkedin" rel="noopener" title="Linkedin">Linkedin</a>
        <a href="https://www.instagram.com/wbg_ag/" class="btn-social is-instagram" target="_blank" rel="noopener" title="Instagram">Instagram</a>
      </div>
    </div>
    <figure class="map">
      <a href="https://goo.gl/maps/9dCKFMFqaiaYUudK7" target="_blank" rel="noopener" title="Google Maps">
        <img class="is-responsive" src="/assets/media/WBG_Binzstrasse_39.png" width="1000" height="652" alt="WBG AG - Binzstrasse 39, CH-8045 Zürich">
      </a>
    </figure>
  </div>
  <div class="jobs">
    @foreach($jobs as $j)
      <article>
        <h2>{{$j->title}}</h2>
        {!! $j->text !!}
      </article>
    @endforeach
  </div>
</section>
@endsection
