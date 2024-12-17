<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

        <link rel="icon" type="image/jpg" href="{{ asset('images/others/icon.png') }}">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('Title')</title>

        <!-- Styles -->
        <link href="{{ url('css/milligram.min.css') }}" rel="stylesheet">
        <link href="{{ url('css/app.css') }}" rel="stylesheet">

        <!-- Pusher -->
        <script>
        const pusherAppKey = "{{ env('PUSHER_APP_KEY') }}";
        const pusherCluster = "{{ env('PUSHER_APP_CLUSTER') }}";
        </script>
        <script src="https://js.pusher.com/7.0/pusher.min.js" defer></script>

        <script type="text/javascript" src="{{ url('js/app.js') }}" defer></script>
        @vite('resources/css/app.css')
    </head>
    <body class="flex flex-col min-h-screen">
        <!-- Top Bar -->
        <div class="lg:max-w-[75%] w-full mx-auto">
            @include('partials.topbar')
        </div>

        <!-- Main Content -->
        <main id="main" class="flex flex-col justify-start flex-1 p-8 overflow-auto">
            <div class="lg:max-w-[75%] w-full mx-auto">
                @yield('content')
            </div>
            <div id="notification-container" class="fixed z-50 bottom-5 right-5" aria-live="polite"></div>
        </main>


        <!-- Footer -->
        @include('partials.footer')
    </body>

</html>
