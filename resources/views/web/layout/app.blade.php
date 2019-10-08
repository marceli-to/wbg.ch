<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>wbg.ch</title>
<meta name="csrf-token" value="{{ csrf_token() }}" />
<meta name="format-detection" content="telephone=no">
<link href="{{ asset('assets/css/app.css') }}" type="text/css" rel="stylesheet" />
<script src="{{ asset('assets/js/modernizr.min.js') }}"></script>
</head>
<body>
<header class="site-header">
  <div>
    <a href="javascript:;" class="btn-menu js-btn-menu"></a>
    <div class="span header-logo">
      <a href="/" class="logo" title="WBG - Home">WBG</a>
      <hr>
    </div>
    <div class="span header-navigation">
      <nav class="header" role="navigation">
        <ul>
          <li>
            <a href="" title="Projekte">Projekte</a>
          </li>
          <li>
            <a href="" title="Profil">Profil</a>
          </li>
          <li>
            <a href="" title="Team">Team</a>
          </li>
          <li>
            <a href="" title="Kontakt">Kontakt</a>
          </li>
        </ul>
      </nav>
      <hr>
    </div>
  </div>
</header>
<main role="main">
  <nav class="sidebar js-menu">
    <ul class="is-project">
      <li>
        <a href="">Projekte</a>
        <ul>
          <li>
            <a href="">Identity</a>
            <ul>
              <li>
                <a href="">Muster 1</a>
              </li>
              <li>
                  <a href="">Muster 2</a>
                </li>
            </ul>
          </li>
          <li>
            <a href="">Signaletik</a>
          </li>
          <li>
            <a href="">Print</a>
          </li>
          <li>
            <a href="">Panoptikum</a>
          </li>
        </ul>
      </li>
    </ul>
    <ul class="is-profile">
      <li>
        <a href="">Profil</a>
        <ul>
          <li>
            <a href="">Haltung</a>
          </li>
          <li>
            <a href="">Kompetenzen</a>
          </li>
          <li>
            <a href="">Kunden</a>
          </li>
          <li>
            <a href="">Impressum</a>
          </li>
        </ul>
      </li>
    </ul>
    <ul class="hide-md">
      <li>
        <a href="">Team</a>
      </li>
    </ul>
    <ul class="hide-md">
      <li>
        <a href="">Kontakt</a>
      </li>
    </ul>
  </nav>
  <section class="site-content">
    @yield('content')
  </section>
</main>
<footer class="site-footer">
  WBG AG – VISUELLE KOMMUNIKATION<br>BINZSTRASSE 39, CH-8045 ZÜRICH, +41 44 269 43 43, MAIL@WBG.CH
</footer>
<script src="{{ asset('assets/js/app.js') }}" type="text/javascript"></script>
</body>
<!-- made with ❤ by marceli.to -->
</html>