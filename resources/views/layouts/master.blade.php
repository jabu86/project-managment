
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

</head>
  <body>
    <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6" href="{{ url('/') }}">PMS</a>
    <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
        <div class="navbar-nav">
            <div class="nav-item text-nowrap">
            <a class="nav-link" href="{{ route('logout') }}"
                onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                {{ __('Logout') }}
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
            </form>
            </div>
        </div>
    </header>

    <div class="container-fluid" id="app">
    <div class="row">
        <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
        <div class="position-sticky pt-3">
            <ul class="nav flex-column">
            <li class="nav-item">
                <router-link class="nav-link active" aria-current="page" to="/dashboard">
               <i class="nav-icon fas fa-tachometer-alt indigo"></i>
                Dashboard
                </router-link>
            </li>
            @can('isAdmin')

                <li class="nav-item">
                    <router-link class="nav-link" to="/users">
                    <i class="nav-icon fas fa-users dark"></i>
                    Users
                    </router-link>
                </li>
                <li class="nav-item">
                    <router-link class="nav-link" to="/product">
                    <i class="nav-icon fas fa-shopping-cart teal"></i>
                    Products
                    </router-link>
                </li>
            @endcan
            <li class="nav-item">
                <router-link class="nav-link" to="/profile">
                <i class="nav-icon fas fa-user gray"></i>
                Profile
                </router-link>
            </li>
            </ul>
        </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <router-view></router-view>
            <!-- set progressbar -->
            <vue-progress-bar></vue-progress-bar>
        </main>
    </div>
    </div>
    @auth
        <script>window.user = @json(auth()->user())</script>
    @endauth
    <script src="{{ asset('js/app.js') }}" defer></script>
  </body>
</html>
