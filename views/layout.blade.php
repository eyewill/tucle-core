<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>{{ config('app.brand') }}</title>

@section('stylesheet')
  <!-- Fonts -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700">

  <!-- Styles -->
  @if (app()->environment('local'))
    <link rel="stylesheet" href="/assets/css/app.css">
  @else
    <link rel="stylesheet" href="/assets/css/app.css">
  @endif
  <link rel="stylesheet" href="/assets/css/animate.min.css">
  <link rel="stylesheet" href="/assets/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css">
@show

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

      <!-- Branding Image -->
      <a class="navbar-brand" href="{{ url('/') }}">
        {{ config('app.brand') }}
      </a>
    </div>

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
              {{ Auth::user()->name }} <span class="caret"></span>
            </a>

            <ul class="dropdown-menu" role="menu">
              <li><a href="{{ url('logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
            </ul>
          </li>
        @endif
      </ul>
    </div>
  </div>
</nav>

@yield('content')

<footer class="footer">
  &copy Eyewill<br>
  Powered by Tucle5
</footer>

<!-- JavaScripts -->
@section('script')
  <script src="/assets/jquery/js/jquery.min.js"></script>
  <script src="/assets/bootstrap/js/bootstrap.min.js"></script>
  <script src="/assets/js/bootstrap-notify/bootstrap-notify.min.js"></script>
  <script src="/assets/moment/js/moment-with-locales.min.js"></script>
  <script src="/assets/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>

  @include('tucle::partial.scripts')

  <script>
    $('[data-provider=datetimepicker]').datetimepicker({
      locale: 'ja'
    });
  </script>
  {{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
@show

</body>
</html>
