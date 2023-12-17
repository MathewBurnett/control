<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        {{ config('app.name') }}
    </title>
    @stack('head')
</head>
<body>
<div class="page">
    <aside class="navbar navbar-vertical navbar-expand-lg" data-bs-theme="dark">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu" aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <h1 class="navbar-brand navbar-brand-autodark mt-1">
                <a href="{{ route('home') }}">
                    {{ config('app.name') }}
                </a>
            </h1>


            <div class="collapse navbar-collapse" id="sidebar-menu">
                <ul class="navbar-nav pt-lg-3">
                    <li class="nav-item @if(($activenav ?? null) === 'home') active @endif">
                        <a class="nav-link" href="{{ route('home') }}" >
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <i class="icon ti ti-home"></i>
                            </span>
                            <span class="nav-link-title">
                                Home
                            </span>
                        </a>
                    </li>
                    <li class="nav-item @if(($activenav ?? null) === 'tickets') active @endif">
                        <a class="nav-link" href="{{ route('tickets.index') }}" >
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <i class="icon ti ti-ticket"></i>
                            </span>
                            <span class="nav-link-title">
                                Tickets
                            </span>
                        </a>
                    </li>
                    <li class="nav-item @if(($activenav ?? null) === 'seating') active @endif">
                        <a class="nav-link" href="{{ route('seatingplans.index') }}" >
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <i class="icon ti ti-armchair"></i>
                            </span>
                            <span class="nav-link-title">
                                Seating Plans
                            </span>
                        </a>
                    </li>
                    <li class="nav-item @if(($activenav ?? null) === 'clans') active @endif">
                        <a class="nav-link" href="{{ route('clans.index') }}" >
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <i class="icon ti ti-users-group"></i>
                            </span>
                            <span class="nav-link-title">
                                Clans
                            </span>
                        </a>
                    </li>
                    <li class="nav-item @if(($activenav ?? null) === 'profile') active @endif">
                        <a class="nav-link" href="{{ route('user.profile') }}" >
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <i class="icon ti ti-user"></i>
                            </span>
                            <span class="nav-link-title">
                                Profile
                            </span>
                        </a>
                    </li>
                    @can('admin')
                        <li class="nav-item dropdown @if(($activenav ?? null) === 'admin') active @endif">
                            <a class="nav-link dropdown-toggle  @if(($activenav ?? null) === 'admin') show @endif" href="#navbar-admin" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <i class="icon ti ti-settings"></i>
                                </span>
                                    <span class="nav-link-title">
                                    Admin
                                </span>
                            </a>
                            <div class="dropdown-menu @if(($activenav ?? null) === 'admin') show @endif" data-bs-popper="static">
                                <a class="dropdown-item" href="{{ route('admin.dashboard') }}">Dashboard</a>
                                <a class="dropdown-item" href="{{ route('admin.users.index') }}">Users</a>
                                <a class="dropdown-item" href="{{ route('admin.clans.index') }}">Clans</a>
                                <a class="dropdown-item" href="{{ route('admin.events.index') }}">Events</a>
                                <a class="dropdown-item" href="{{ route('admin.tickets.index') }}">Tickets</a>
                                <a class="dropdown-item" href="{{ route('admin.settings.index') }}">Settings</a>
                            </div>
                        </li>
                    @endcan
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('logout') }}" >
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <i class="icon ti ti-logout"></i>
                        </span>
                            <span class="nav-link-title">
                            Logout
                        </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </aside>

    <div class="page-wrapper">
        @if(session()->get('impersonating'))
            <div class="navbar navbar-dark bg-dark-subtle">
                <div class="container-fluid">
                    <div class="navbar-text">
                        You are impersonating {{ Auth::user()->nickname }}
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('admin.unimpersonate') }}" class="btn btn-primary">Remove Impersonation</a>
                    </div>
                </div>
            </div>
        @endif
        <header class="navbar d-print-none">
            <div class="container-xl">
                <div class="navbar-nav flex-row">
                    <div class="d-flex py-2 px-0">
                        <ol class="breadcrumb" aria-label="breadcrumbs">
                            @yield('breadcrumbs')
                        </ol>
                    </div>
                </div>

                <div class="nav-item">
                    <a href="{{ route('user.profile') }}" class="nav-link d-flex lh-1 text-reset p-0">
                        <span class="avatar avatar-sm" style="background-image: url('{{ Auth::user()->avatarUrl() }}');"></span>
                        <div class="d-none d-sm-block ps-2">
                            <div>{{ Auth::user()->nickname }}</div>
                        </div>
                    </a>
                </div>
            </div>
        </header>
        @yield('header')
        <div class="page-body">
            <div class="container-xl">
                @if (session('successMessage'))
                    <div class="alert alert-success alert-important alert-dismissible" role="alert">
                        {{ session('successMessage') }}
                        <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                    </div>
                @endif
                @if (session('errorMessage'))
                    <div class="alert alert-danger alert-important alert-dismissible" role="alert">
                        {{ session('errorMessage') }}
                        <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                    </div>
                @endif
                @if (session('infoMessage'))
                    <div class="alert alert-info alert-important alert-dismissible" role="alert">
                        {{ session('infoMessage') }}
                        <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                    </div>
                @endif
                @if (session('warningMessage'))
                    <div class="alert alert-warning alert-important alert-dismissible" role="alert">
                        {{ session('warningMessage') }}
                        <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                    </div>
                @endif
                @yield('content')
            </div>
        </div>
        <footer class="footer footer-transparent d-print-none">
            <div class="container-xl">
                <div class="row text-center align-items-center flex-row-reverse">
                    <div class="col-lg-auto ms-lg-auto">
                        <ul class="list-inline list-inline-dots mb-0">
                            <li class="list-inline-item"><a href="#" target="_blank" class="link-secondary" rel="noopener">Terms and Conditions</a></li>
                            <li class="list-inline-item"><a href="#" class="link-secondary">Privacy Policy</a></li>
                        </ul>
                    </div>
                    <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                        <ul class="list-inline list-inline-dots mb-0">
                            <li class="list-inline-item">
                                Copyright &copy; {{ date('Y') }}
                                <a href="{{ route('home') }}" class="link-secondary">{{ config('app.name') }}</a>.
                                All rights reserved.
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</div>
@stack('footer')
</body>
</html>
