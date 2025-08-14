<!doctype html>
<html lang="en" @if (Auth::user()->style == 'dark') class="dark-theme" @endif>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="{{ url('assets/images/favicon-32x32.png') }}" type="image/png" />
        <link href="{{ url('assets/plugins/simplebar/css/simplebar.min.css?v2.944') }}" rel="stylesheet" />
        <link href="{{ url('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.min.css?v2.944') }}" rel="stylesheet" />
        <link href="{{ url('assets/plugins/metismenu/css/metisMenu.min.css?v2.944') }}" rel="stylesheet" />
        <link href="{{ url('assets/css/bootstrap.min.css?v2.944') }}" rel="stylesheet">
        <link href="{{ url('assets/css/app.min.css?v2.944') }}" rel="stylesheet">
        <link href="{{ url('assets/css/icons.min.css?v2.944') }}" rel="stylesheet">
        <link rel="stylesheet" href="{{ url('assets/css/dark-theme.min.css?v2.944') }}" />
        <link rel="stylesheet" href="{{ url('assets/css/semi-dark.min.css?v2.944') }}" />
        <link rel="stylesheet" href="{{ url('assets/css/header-colors.min.css?v2.944') }}" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.css">
        <title>@yield('title', 'EliteX - CRM')</title>
        <style>
            .nav-container ,.nav-container ul{
                box-shadow: none !important;
                border-bottom: 0;
                background-color: #0555cb !important;
            }
            .nav-container *{
                color: white !important;
            }
            .user-box{
                border-left: 0;
            }
            .user-box *{
                color: white !important;
            }
            .page-wrapper{
                padding: 0;
                margin-top: 60px;
            }
            .page-content{
                padding: 0;
            }
            .topbar ,.topbar ul{
                background: #0555cb;
                border-bottom: 0;
            }
            .profile-dropdown li:hover,.profile-dropdown a:hover{
                background-color: #0b5ed7 !important;
            }
            .select2-results__options {
                scrollbar-width: none !important;
                background-color: transparent;
                overflow-y: scroll;
            }
            .max-w-160 * {
                max-width: 160px !important;
            }
        </style>
        @yield("style")
    </head>
    <body>
        <div class="wrapper">
            @include("layouts.header")
            @include("layouts.top_nav")
            <div class="error-area">
                @if ($errors->any())
                <div class="alert alert-danger" >
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<?php
/*
@if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    */
    ?>
            </div>
            @yield("wrapper")
            <div class="overlay toggle-icon"></div>
            <footer class="page-footer">
                <p class="mb-0">Copyright © 2023. All right reserved.</p>
            </footer>
        </div>
        <script src="{{ url('assets/js/bootstrap.bundle.min.js?v2.944') }}"></script>
        <script src="{{ url('assets/js/jquery.min.js?v2.944') }}"></script>
        <script src="{{ url('assets/plugins/simplebar/js/simplebar.min.js?v2.944') }}"></script>
        <script src="{{ url('assets/plugins/metismenu/js/metisMenu.min.js?v2.944') }}"></script>
        <script src="{{ url('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.min.js?v2.944') }}"></script>
        <script src="{{ url('assets/js/scrollbar.min.js?v2.944') }}"></script>
        <script src="{{ url('assets/js/app.min.js?v2.944') }}"></script>
        <script src="https://cdn.datatables.net/2.3.2/js/dataTables.js"></script>
        @yield("script")
        
        <!-- Global Subscription Overlay - Shows on ALL pages when subscription is inactive -->
        @include('partials.subscription-overlay')
    </body>
</html>
