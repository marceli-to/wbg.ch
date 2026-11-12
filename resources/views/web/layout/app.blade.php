<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@if(trim($__env->yieldContent('seo_title')))@yield('seo_title') - {{config('seo.title')}}@else{{config('seo.title')}}@endif</title>
<meta name="description" content="@if(trim($__env->yieldContent('seo_description')))@yield('seo_description')@else{{config('seo.description')}}@endif">
<meta name="keywords" content="Fassadenraster, Fassadenbespielung, Lichtinstallation, Fassadenanschrift, Signaletik, Orientierungs- und Leitsystem, Editorial-Design, Buchgestaltung, Kommunikationsmittel, Ausstellungsgestaltung, Design, Plakatserie, Schriftentwurf, Website-Design, Corporate Identity, Erscheinungsbild">
<meta property="og:title" content="@if(trim($__env->yieldContent('seo_title')))@yield('seo_title') - {{config('seo.title')}}@else{{config('seo.title')}}@endif">
<meta property="og:description" content="@if(trim($__env->yieldContent('seo_description')))@yield('seo_description')@else{{config('seo.description')}}@endif">
@php($canonicalUrl = $canonical ?? AppHelper::canonical(request()->path()))
<link rel="canonical" href="{{ $canonicalUrl }}">
<meta property="og:type" content="website">
<meta property="og:locale" content="de_CH">
<meta property="og:url" content="{{ $canonicalUrl }}">
<meta property="og:image" content="@if(trim($__env->yieldContent('og_image')))@yield('og_image')@else{{ AppHelper::canonical('assets/media/WBGAG.png') }}@endif">
<meta property="og:site_name" content="{{config('seo.title')}}">
<meta name="twitter:card" content="summary_large_image">
<meta name="format-detection" content="telephone=no">
<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32.png">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">
<link rel="manifest" href="/site.webmanifest">
<meta name="theme-color" content="#ffffff">
@if (request()->routeIs('home'))
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'Organization',
    'name' => 'WBG AG – Visuelle Kommunikation',
    'url' => AppHelper::canonical('/'),
    'logo' => AppHelper::canonical('icon-512.png'),
    'email' => 'mail@wbg.ch',
    'telephone' => '+41 44 269 43 43',
    'foundingDate' => '1991',
    'address' => [
        '@type' => 'PostalAddress',
        'streetAddress' => 'Binzstrasse 39',
        'postalCode' => '8045',
        'addressLocality' => 'Zürich',
        'addressCountry' => 'CH',
    ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endif
@vite('resources/sass/web/app.scss')
<script src="{{ asset('assets/js/modernizr.min.js') }}"></script>
</head>
<body>
<a href="#content" class="visually-hidden skip-link">Zum Inhalt springen</a>
<header class="site-header js-header">
  <div>
    <a href="javascript:;" class="btn-menu js-btn-menu" title="Menü anzeigen" role="button" aria-label="Menü anzeigen" aria-expanded="false" aria-controls="site-menu"></a>
    <div class="span header-logo">
      <a href="/" class="logo" title="WBG - Home">WBG</a>
    </div>
    <div class="span header-title">
      <span class="page-title">@if (isset($pageTitle) && !request()->routeIs('home')) {{ $pageTitle }} @else &nbsp; @endif</span>
    </div>
    <div class="span header-navigation">
      <nav class="header" aria-label="Hauptnavigation">
        <ul>
          <li>
            <a href="{{ route('project.index') }}" 
               title="Projekte"
               class="{{ request()->routeIs('project.*') ? 'is-active' : '' }}" @if(request()->routeIs('project.*')) aria-current="page" @endif>
               Projekte
            </a>
          </li>
          <li>
            <a href="{{ route('profile.attitude') }}"
               title="Profil"
               class="{{ request()->routeIs('profile.*') ? 'is-active' : '' }}" @if(request()->routeIs('profile.*')) aria-current="page" @endif>
               Profil
            </a>
          </li>
          <li>
            <a href="{{ route('contact') }}"
               title="Kontakt"
               class="{{ request()->routeIs('contact') ? 'is-active' : '' }}" @if(request()->routeIs('contact')) aria-current="page" @endif>
               Kontakt
            </a>
          </li>
          <li>
            <a href="{{ route('team') }}"
               title="Team"
               class="{{ request()->routeIs('team') ? 'is-active' : '' }}" @if(request()->routeIs('team')) aria-current="page" @endif>
               Team
            </a>
          </li>
        </ul>
      </nav>
    </div>
  </div>
</header>
<main id="content">
  <nav class="sidebar js-menu" id="site-menu" aria-label="Menü">
    <ul class="is-project">
      <li>
        <a href="javascript:;" class="{{ request()->routeIs('project.*') ? 'is-active' : '' }} js-btn-toggle-sub" role="button" aria-expanded="{{ request()->routeIs('project.*') ? 'true' : 'false' }}">Projekte</a>
        <ul style="{{ request()->routeIs('project.*') ? 'display:block' : 'display:none' }}">
          @foreach($menu['projects'] as $menu_project)
            <li>
              <a href="{{ $menu_project['slug'] }}" 
                 class="{{ $menu_project['is-active'] ? 'is-active' : '' }}">
                 {{ $menu_project['category'] }}
              </a>
              <ul class="{{ $menu_project['is-active'] ? 'is-visible' : '' }}">
                @foreach($menu_project['items'] as $menu_item)
                  <li>
                    <a 
                      href="{{ $menu_item['slug'] }}"
                      class="{{ $menu_item['is-active'] ? 'is-active hide-sm' : 'hide-sm' }}" @if($menu_item['is-active']) aria-current="page" @endif>
                      {{ $menu_item['name'] }}
                    </a>
                  </li>
                @endforeach
              </ul>
            </li>
          @endforeach
        </ul>
      </li>
    </ul>
    <ul class="is-profile">
      <li>
        <a href="javascript:;" 
           class="js-btn-toggle-sub {{ request()->routeIs('profile.*') ? 'is-active' : '' }}" role="button" aria-expanded="{{ request()->routeIs('profile.*') ? 'true' : 'false' }}">
           Profil
        </a>
        <ul style="{{ request()->routeIs('profile.*') ? 'display:block' : 'display:none' }}">
          <li>
            <a href="{{ route('profile.attitude') }}"
               class="{{ request()->routeIs('profile.attitude') ? 'is-active' : '' }}" @if(request()->routeIs('profile.attitude')) aria-current="page" @endif>
              Haltung
            </a>
          </li>
          <li>
            <a href="{{ route('profile.competences') }}"
               class="{{ request()->routeIs('profile.competences') || request()->routeIs('profile.competences.*') ? 'is-active' : '' }}" @if(request()->routeIs('profile.competences') || request()->routeIs('profile.competences.*')) aria-current="page" @endif>
              Kompetenzen
            </a>
          </li>
          <li>
            <a href="{{ route('profile.clients') }}"
               class="{{ request()->routeIs('profile.clients') ? 'is-active' : '' }}" @if(request()->routeIs('profile.clients')) aria-current="page" @endif>
              Kunden
            </a>
          </li>
          <li>
            <a href="{{ route('profile.legal') }}"
               class="{{ request()->routeIs('profile.legal') ? 'is-active' : '' }}" @if(request()->routeIs('profile.legal')) aria-current="page" @endif>
              Rechtliches
            </a>
          </li>
        </ul>
      </li>
    </ul>
    <ul class="hide-md">
      <li>
        <a href="{{ route('team') }}" class="{{ request()->routeIs('team') ? 'is-active' : '' }}" @if(request()->routeIs('team')) aria-current="page" @endif>Team</a>
      </li>
    </ul>
    <ul class="hide-md">
      <li>
        <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'is-active' : '' }}" @if(request()->routeIs('contact')) aria-current="page" @endif>Kontakt</a>
      </li>
    </ul>
  </nav>
  @yield('content')
</main>
@if (!request()->routeIs('project.detail'))
<footer class="site-footer js-footer">
  WBG AG – VISUELLE KOMMUNIKATION<br>BINZSTRASSE 39, CH-8045 ZÜRICH, +41 44 269 43 43, MAIL@WBG.CH
</footer>
@endif
@vite('resources/js/web/app.js')
@production
<script async src="https://www.googletagmanager.com/gtag/js?id=G-QKCSHMCHVT"></script>
<script>
 window.dataLayer = window.dataLayer || [];
 function gtag(){dataLayer.push(arguments);}
 gtag('js', new Date());
 gtag('config', 'G-QKCSHMCHVT');
</script>
@endproduction
</body>
<!-- made with ❤ by marceli.to -->
</html>