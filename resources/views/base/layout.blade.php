<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>{{ config('tucle.brand', 'TUCLE5') }}</title>

@section('stylesheet')
  <!-- Fonts -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700">

  <!-- Styles -->
  <link rel="stylesheet" href="/assets/css/animate.min.css">
  <link rel="stylesheet" href="{{ elixir('css/app.css') }}">

@show

  <!-- Scripts -->
  <script src="/assets/jquery/js/jquery.min.js"></script>

</head>
<body id="cms">

<nav class="navbar navbar-default navbar-static-top">
  <div class="container">
    <div class="navbar-header">

      <!-- Collapsed Hamburger -->
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
        <span class="sr-only">Toggle Navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>

      @section('navbar-brand')
      <!-- Branding Image -->
      <a class="navbar-brand" href="{{ config('app.url') }}">
        {{ config('tucle.brand', 'TUCLE5') }}
      </a>
      @show
    </div>

    @section('navbar-items')
    <div class="collapse navbar-collapse" id="app-navbar-collapse">
      <!-- Left Side Of Navbar -->
      <ul class="nav navbar-nav">
        @if (!Auth::guest())
          {{ $tucle->navigation() }}
        @endif
      </ul>

      <!-- Right Side Of Navbar -->
      <ul class="nav navbar-nav navbar-right">
        <!-- Authentication Links -->
        @if (Auth::guest())
          <li><a href="{{ url('login') }}">Login</a></li>
          <li><a href="{{ url('register') }}">Register</a></li>
        @else
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
              {{ Auth::user()->user_name }} <span class="caret"></span>
            </a>

            <ul class="dropdown-menu" role="menu">
              <li>
                <a href="{{ url('user') }}">
                  <span class="fa fa-btn fa-user"></span>
                  ユーザー管理
                </a>
              </li>
              <li role="separator" class="divider"></li>
              <li>
                <a href="{{ url('logout') }}">
                  <i class="fa fa-btn fa-sign-out"></i>
                  Logout
                </a>
              </li>
            </ul>
          </li>
        <li>
          <a href="{{ config('tucle.front_url') }}" target="_blank">
            <i class="fa fa-external-link"></i>
            ウェブサイト
          </a>
        </li>
      </ul>
      @endif
    </div>
    @show
  </div>
</nav>

@yield('content')

<footer class="footer">
  &copy Eyewill<br>
  Powered by Tucle5
</footer>

<!-- JavaScripts -->
@section('script')
  <script src="/assets/bootstrap/js/bootstrap.min.js"></script>

  @include('tucle::partial.scripts')
  @include('tucle::module.notify')

  {{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
@show

</body>
</html>
