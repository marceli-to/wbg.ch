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
<header class="site-header js-header">
  <div>
    <a href="javascript:;" class="btn-menu js-btn-menu"></a>
    <div class="span header-logo">
      <a href="/" class="logo" title="WBG - Home">WBG</a>
      {{-- <hr> --}}
    </div>
    <div class="span header-title">
      <h1 class="page-title">@if (isset($pageTitle)) {{ $pageTitle }} @else &nbsp; @endif</h1>
      {{-- <hr> --}}
    </div>
    <div class="span header-navigation">
      <nav class="header" role="navigation">
        <ul>
          <li>
            <a href="{{ route('project.index') }}" 
               title="Projekte"
               class="{{ request()->routeIs('project.*') ? 'is-active' : ''}}">
               Projekte
            </a>
          </li>
          <li>
            <a href="{{ route('profile.attitude') }}"
               title="Profil"
               class="{{ request()->routeIs('profile.*') ? 'is-active' : ''}}">
               Profil
            </a>
          </li>
          <li>
            <a href="{{ route('team') }}"
               title="Team"
               class="{{ request()->routeIs('team') ? 'is-active' : ''}}">
               Team
            </a>
          </li>
          <li>
            <a href="{{ route('contact') }}"
               title="Team"
               class="{{ request()->routeIs('contact') ? 'is-active' : ''}}">
               Kontakt
            </a>
          </li>
        </ul>
      </nav>
      {{-- <hr> --}}
    </div>
  </div>
</header>
<main role="main">
  <nav class="sidebar js-menu">
    <ul class="is-project">
      <li>
        <a href="javascript:;" class="{{ request()->routeIs('project.*') ? 'is-active' : '' }} js-btn-toggle-sub">Projekte</a>
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
                      class="{{ $menu_item['is-active'] ? 'is-active hide-sm' : 'hide-sm' }}">
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
           class="js-btn-toggle-sub {{ request()->routeIs('profile.*') ? 'is-active' : '' }}">
           Profil
        </a>
        <ul style="{{ request()->routeIs('profile.*') ? 'display:block' : 'display:none' }}">
          <li>
            <a href="{{ route('profile.attitude') }}"
                class="{{ request()->routeIs('profile.attitude') ? 'is-active' : '' }}">
              Haltung
            </a>
          </li>
          <li>
            <a href="{{ route('profile.competences') }}"
                class="{{ request()->routeIs('profile.competences') ? 'is-active' : '' }}">
              Kompetenzen
            </a>
          </li>
          <li>
            <a href="{{ route('profile.clients') }}"
                class="{{ request()->routeIs('profile.clients') ? 'is-active' : '' }}">
              Kunden
            </a>
          </li>
          <li>
            <a href="{{ route('profile.imprint') }}"
                class="{{ request()->routeIs('profile.imprint') ? 'is-active' : '' }}">
              Impressum
            </a>
          </li>
        </ul>
      </li>
    </ul>
    <ul class="hide-md">
      <li>
        <a href="{{ route('team') }}" class="{{ request()->routeIs('team') ? 'is-active' : '' }}">Team</a>
      </li>
    </ul>
    <ul class="hide-md">
      <li>
        <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'is-active' : '' }}">Kontakt</a>
      </li>
    </ul>
  </nav>
  @yield('content')
</main>
<footer class="site-footer js-footer">
  WBG AG – VISUELLE KOMMUNIKATION<br>BINZSTRASSE 39, CH-8045 ZÜRICH, +41 44 269 43 43, MAIL@WBG.CH
</footer>
<script src="{{ asset('assets/js/app.js') }}" type="text/javascript"></script>
</body>
<!-- made with ❤ by marceli.to -->
</html>