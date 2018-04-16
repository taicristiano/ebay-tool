<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <title>@yield('title')</title>

        @include('auth.layouts.component.meta')
        @yield('meta')

        @include('auth.layouts.component.head')
        @yield('head')
    </head>
    <body class="hold-transition @yield('class-body')">
        <div class="wrapper">
            @include('auth.layouts.component.navbar')
            @include('auth.layouts.component.sidebar')
            @yield('content')
        </div>
        <!-- Scripts -->
        @include('auth.layouts.component.script')
        @yield('script')
    </body>
</html>
