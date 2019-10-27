@extends('web.layout.app')
@section('seo_title', $pageTitle)
@section('seo_description', '')
@section('content')
<section class="site-content">
  <div class="contact">
    <div class="contact-grid">
      <div class="span">
        <address>
          <h2>WBG AG – Visuelle Kommunikation</h2>
          <p>Binzstrasse 39, CH-8045 Zürich, <a href="tel:+41 44 269 43 43">+41 44 269 43 43</a>, <a href="mailto:mail@wbg.ch">mail@wbg.ch</a></p>
          <p>UID: CHE-103.423.461 / IBAN: CH 06 00225 22584124701N</p>
        </address>
      </div>
      <div class="span">
        <a href="" class="btn-social is-maps" rel="noopener" title="Standort auf Google Maps">Google Maps</a>
        <a href="" class="btn-social is-linkedin" rel="noopener" title="WBG auf Linkedin">Linkedin</a>
        <a href="" class="btn-social is-instagram" rel="noopener" title="WBG auf Instagram">Instagram</a>
      </div>
    </div>
    <div class="maps-container" id="js-maps"></div>
  </div>
</section>
@endsection
