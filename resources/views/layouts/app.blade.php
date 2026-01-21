<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" type="image/png" href="{{ asset('favicon-32x32.png') }}" sizes="32x32">
    <link rel="icon" type="image/png" href="{{ asset('favicon-16x16.png') }}" sizes="16x16">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body class="app-body" data-theme="mono">
    <div id="page-transition-overlay" class="page-transition-overlay"></div>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark app-navbar shadow-sm">
            <div class="container-fluid px-4">
                <a class="navbar-brand fw-bold text-uppercase tracking-wide d-flex align-items-center" href="{{ route('home') }}">
                    <img src="{{ asset('images/admin-icon.png') }}" alt="{{ config('app.name') }} logo" class="navbar-logo me-2">
                    <span>{{ config('app.name', 'Admin Panel') }}</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        @auth
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('home') }}">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('companies.index') }}">Companies</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('employees.index') }}">Employees</a>
                            </li>
                        @endauth
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item me-md-2 d-md-flex align-items-md-center">
                            <button
                                type="button"
                                class="btn btn-primary btn-sm theme-toggle"
                                id="theme-toggle"
                                aria-label="Switch to neon mode"
                                aria-pressed="true"
                            >
                                <span data-theme-toggle-label>Neon mode</span>
                            </button>
                        </li>
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link nav-tagline" href="{{ route('login') }}">
                                        The dashboard that won't leave you feeling dashbored<sup>™</sup>
                                    </a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item d-none d-md-flex align-items-center">
                                <div class="navbar-search position-relative me-3">
                                    <button
                                        type="button"
                                        class="btn btn-link p-0 navbar-search-toggle"
                                        aria-label="Search"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="32" height="32">
                                            <path fill="none" d="M0 0h24v24H0z"/>
                                            <path fill="currentColor" d="M11 4a7 7 0 0 1 5.292 11.59l3.559 3.56-1.414 1.414-3.56-3.559A7 7 0 1 1 11 4zm0 2a5 5 0 1 0 0 10 5 5 0 0 0 0-10z"/>
                                        </svg>
                                    </button>
                                    <input
                                        id="navbar-search"
                                        type="search"
                                        class="form-control form-control-sm navbar-search-input"
                                        placeholder="Search companies and employees"
                                        autocomplete="off"
                                        aria-label="Search companies and employees"
                                    >
                                    <div id="navbar-search-results" class="navbar-search-results d-none"></div>
                                </div>
                            </li>
                            <li class="nav-item">
                                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="nav-link d-flex align-items-center gap-1 logout-link">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="me-1">
                                            <path fill="none" d="M0 0h24v24H0z"/>
                                            <path fill="currentColor" d="M5 3h7a2 2 0 0 1 2 2v3h-2V5H5v14h7v-3h2v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2zm9.293 7.293 1.414-1.414L21.414 12l-5.707 5.121-1.414-1.414L17.086 13H10v-2h7.086l-2.793-2.707z"/>
                                        </svg>
                                        <span>{{ __('Logout') }}</span>
                                    </button>
                                </form>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="container-fluid px-4 app-main">
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
