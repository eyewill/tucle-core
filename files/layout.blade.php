<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>{{ $tucle->title() }}</title>

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
          @if (!Auth::guest())
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                {{ Auth::user()->user_name }} <span class="caret"></span>
              </a>

              <ul class="dropdown-menu" role="menu">
                @can('show-user', App\User::class)
                <li>
                  <a href="{{ route('user.index') }}">
                    <span class="fa fa-btn fa-user"></span>
                    ユーザー管理
                  </a>
                </li>
                @endcan
                @if (config('tucle.event_log.enabled'))
                  @can('show-eventlog', App\EventLog::class)
                  <li>
                    <a href="{{ route('eventlog.index') }}">
                      <span class="fa fa-btn fa-list"></span>
                      イベントログ
                    </a>
                  </li>
                  @endcan
                @endif
                <li role="separator" class="divider"></li>
                <li>
                  <a href="{{ url('logout') }}">
                    <i class="fa fa-btn fa-sign-out"></i>
                    ログアウト
                  </a>
                </li>
              </ul>
            </li>
          @endif
          <li>
            <a href="{{ config('tucle.front_url') }}" target="_blank">
              <i class="fa fa-external-link"></i>
              ウェブサイト
            </a>
          </li>
        </ul>
      </div>
    @show
  </div>
</nav>

@yield('content')

<footer class="footer">
  &copy {{ config('tucle.copyright', 'Eyewill') }}<br>
  Powered by {{ config('tucle.powered_by', 'Tucle5') }}
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
