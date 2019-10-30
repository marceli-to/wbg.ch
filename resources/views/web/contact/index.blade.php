@extends('web.layout.app')
@section('seo_title', $pageTitle)
@section('seo_description', '')
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
        <a href="https://goo.gl/maps/5orzRm8TP9fX5GQ16" class="btn-social is-maps" rel="noopener" title="Google Maps">Google Maps</a>
        <a href="" class="btn-social is-linkedin" rel="noopener" title="Linkedin">Linkedin</a>
        <a href="" class="btn-social is-instagram" rel="noopener" title="Instagram">Instagram</a>
      </div>
    </div>
    <div class="maps-container" id="js-maps"></div>
  </div>
</section>
@endsection
