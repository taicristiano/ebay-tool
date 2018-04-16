<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <title>@yield('title')</title>

        @include('layouts.component.meta')
        @yield('meta')

        @include('layouts.component.head')
        @yield('head')
    </head>
    <body @yield('class-body') class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper">
            @include('layouts.component.navbar')
            @include('layouts.component.sidebar')
            @yield('content')
            @include('layouts.component.modal-warning')
        </div>
        <!-- Scripts -->
        @include('layouts.component.script')
        @yield('script')
    </body>
</html>
