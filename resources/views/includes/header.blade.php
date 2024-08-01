<html>
<head>
  <title>Seller Backoffice | ATOM</title>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1" name="viewport">
  <link href="fonts/fontawesome/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="{{ asset('css/style.bundle.css') }}" rel="stylesheet" type="text/css">
  <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet" type="text/css">
  <link href="{{ asset('css/style.css?v=') }}{{ date('mdYhis') }}" rel="stylesheet" type="text/css">
</head>
@php ($style = "")
@if(! empty(Auth::user()))
@php ($style = "kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--fixed kt-subheader--solid kt-aside--enabled kt-aside--fixed")
@endif
<body class="{{ $style }}">
  @if(! empty(Auth::user()))
  <div id="kt_header_mobile" class="kt-header-mobile kt-header-mobile--fixed">
    <div class="kt-header-mobile__logo">
      <a href="/home/">
        <img alt="Logo" src="{{ asset('images/logo.png') }}" style="width: 100px; filter: invert(1);">
      </a>
    </div>
    <div class="kt-header-mobile__toolbar">
      <button class="kt-header-mobile__toggler" id="kt_header_mobile_toggler"><span></span></button>
    </div>
  </div>
  <div class="kt-aside">
    <div class="kt-aside__brand kt-grid__item" id="kt_aside_brand" kt-hidden-height="65">
      <div class="kt-aside__brand-logo">
        <a href="/home/">
          <img alt="Logo" src="{{ asset('images/logo.png') }}" style="width: 100px; filter: invert(1);">
        </a>
      </div>
      <div class="kt-aside__brand-tools">
        <button class="kt-aside__brand-aside-toggler" id="kt_aside_toggler">
          <span class="kt-menu__link-icon"><i class="fa fa-bars"></i></span>
        </button>
      </div>
    </div>
    <div class="kt-aside-menu-wrapper">
      <div class="kt-aside-menu kt-scroll">
        <ul class="kt-menu__nav">
          <li class="kt-menu__item {{ (Request::path() == 'home'?'active':'') }}">
            <a href="/home/" class="kt-menu__link">
              <span class="kt-menu__link-icon"><i class="fa fa-th-large icon-lg"></i></span>
              <span class="kt-menu__link-text">Dashboard</span>
            </a>
          </li>
          <li class="kt-menu__item {{ (Request::path() == 'product'?'active':'') }}">
            <a href="/product/" class="kt-menu__link">
              <span class="kt-menu__link-icon"><i class="fa fa-box-open icon-lg"></i></span>
              <span class="kt-menu__link-text">Product</span>
              <span class="kt-menu__link-icon caret"><i class="fas fa-caret-down"></i></span>
            </a>
            <ul class="kt-menu__nav kt-menu__nav__sub">
              <li class="kt-menu__item">
                <a href="/product/" class="kt-menu__link">
                  <span class="kt-menu__link-icon"><i class="fas fa-clipboard-list icon-lg"></i></span>
                  <span class="kt-menu__link-text">List</span>
                </a>
              </li>
              <li class="kt-menu__item">
                <a href="/product-category/" class="kt-menu__link">
                  <span class="kt-menu__link-icon"><i class="fas fa-boxes icon-lg"></i></span>
                  <span class="kt-menu__link-text">Category</span>
                </a>
              </li>
            </ul>
          </li>
          <li class="kt-menu__item {{ (Request::path() == 'order'?'active':'') }}">
            <a href="/order/" class="kt-menu__link">
              <span class="kt-menu__link-icon"><i class="fa fa-shopping-cart icon-lg"></i></span>
              <span class="kt-menu__link-text">Order</span>
            </a>
          </li>
          <li class="kt-menu__item {{ (Request::path() == 'home'?'shipping':'') }}">
            <a href="/shipping/" class="kt-menu__link">
              <span class="kt-menu__link-icon"><i class="fa fa-shipping-fast icon-lg"></i></span>
              <span class="kt-menu__link-text">Shipment</span>
            </a>
          </li>
          <li class="kt-menu__item {{ (Request::path() == 'home'?'customer':'') }}">
            <a href="/customer/" class="kt-menu__link">
              <span class="kt-menu__link-icon"><i class="fa fa-user icon-lg"></i></span>
              <span class="kt-menu__link-text">Customer</span>
            </a>
          </li>
          <li class="kt-menu__item {{ (Request::path() == 'ticket'?'active':'') }}">
            <a href="/ticket/" class="kt-menu__link">
              <span class="kt-menu__link-icon"><i class="fa fa-bullhorn icon-lg"></i></span>
              <span class="kt-menu__link-text">Ticketing</span>
            </a>
          </li>
          @if(! empty(Auth::user()) && Auth::user()->role == 'admin')
          <li class="kt-menu__item {{ (Request::path() == 'user'?'active':'') }}">
            <a href="/user/" class="kt-menu__link">
              <span class="kt-menu__link-icon"><i class="fa fa-user-tie icon-lg"></i></span>
              <span class="kt-menu__link-text">User</span>
            </a>
          </li>
          <li class="kt-menu__item {{ (Request::path() == 'log'?'active':'') }}">
            <a href="/log/" class="kt-menu__link">
              <span class="kt-menu__link-icon"><i class="fa fa-user-cog icon-lg"></i></span>
              <span class="kt-menu__link-text">Administrator</span>
            </a>
          </li>
          @endif
          <li class="kt-menu__item {{ (Request::path() == 'setting'?'active':'') }}">
            <a href="/setting/" class="kt-menu__link">
              <span class="kt-menu__link-icon"><i class="fa fa-cog icon-lg"></i></span>
              <span class="kt-menu__link-text">Setting</span>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </div>
  <div class="kt-header kt-grid__item kt-header--fixed">
    <div class="container-fluid d-flex align-items-stretch justify-content-between">
      <div class="d-flex align-items-stretch mr-4">
        <h3 class="d-none text-dark d-lg-flex align-items-center mr-10 mb-0">Dashboard</h3>
      </div>
      <div id="kt_header_menu" class="header-menu header-menu-mobile header-menu-layout-default">
        <ul class="d-none d-lg-flex menu-nav">
          <li class="menu-item menu-item-submenu menu-item-rel" data-menu-toggle="click" aria-haspopup="true">
            <a href="javascript:;" class="menu-link menu-toggle">
              <span class="menu-text"><i class="fas fa-th"></i></span>
              <span class="menu-desc"></span>
              <i class="menu-arrow"></i>
            </a>
          </li>
          <li class="menu-item menu-item-submenu" data-menu-toggle="click" aria-haspopup="true">
            <a href="javascript:;" class="menu-link menu-toggle">
              <span class="menu-text"><i class="fas fa-bell"></i></span>
              <span class="menu-desc"></span>
              <i class="menu-arrow"></i>
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="usericon">
      <img src="{{ asset('images/profiles/users/' . Auth::user()->id . '/profile.jpg') }}">
      <div class="kt-usermenu bg-white">
        <ul class="kt-menu__nav">
          <li class="kt-menu__item">
            <a href="/user/{{ Auth::user()->id }}/edit/" class="kt-menu__link">
              <span class="kt-menu__link-icon"><i class="fa fa-user"></i></span>
              <span id="txtFrom" class="kt-menu__link-text" data-id="">{{ Auth::user()->name }}</span>
            </a>
          </li>
          <li class="kt-menu__item">
            <a href="/logout/" class="kt-menu__link">
              <span class="kt-menu__link-icon"><i class="fa fa-sign-out-alt"></i></span>
              <span class="kt-menu__link-text">ออกจากระบบ</span>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </div>
  @endif
  <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper">
    <div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">
      @if(empty(Auth::user()))
      <section id="header">
        <div class="row">
          <div class="col-12 text-center">
            <img src="{{ asset('images/logo.png') }}" class="shadowed" style="width: 200px; margin-bottom: 30px;">
          </div>
        </div>
      </section>
      @endif
      <section id="content">
        @if ($errors->any())
        <div class="col-12">
          @foreach ($errors->all() as $error)
          <div class="message error"><i class="fas fa-exclamation-triangle"></i> {{ $error }}</div>
          @endforeach
        </div>
        @elseif (Session::has('message'))
        <div class="col-12">
          <div class="message"><i class="fas fa-check-circle"></i> {{ Session::get('message') }}</div>
        </div>
        @endif