<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Styles -->
        <link href="{{ url('css/milligram.min.css') }}" rel="stylesheet">
        <link href="{{ url('css/app.css') }}" rel="stylesheet">
        
        <script type="text/javascript">
            // Fix for Firefox autofocus CSS bug
            // See: http://stackoverflow.com/questions/18943276/html-5-autofocus-messes-up-css-loading/18945951#18945951
        </script>
        <script type="text/javascript" src={{ url('js/app.js') }} defer>
        </script>
        @vite('resources/css/app.css')
    </head>
        <body class="bg-gray-50">
            <main>
                <!-- Header Section -->
                <header class="flex justify-between items-center px-12 py-12 bg-blue-400 shadow-lg">
                    <!-- Logo/Title -->
                    <h1 class="text-3xl font-bold text-purple-900">
                        <a href="{{ url('/cards') }}" class="hover:text-gray-300 text-7xl font-semibold text-white">Lend-a-Hand</a>
                    </h1>

                    <!-- Navigation Icons -->
                    <div class="flex items-center space-x-6">
                        <a href="{{ url('/home') }}" class="text-white hover:text-gray-300" title="Home">
                            <i class="fa-solid fa-house text-5xl"></i>
                        </a>
                        <a href="{{ url('/info') }}" class="text-white hover:text-gray-300" title="Info">
                            <i class="fa-solid fa-circle-info text-5xl"></i>
                        </a>
                        <a href="{{ url('/help') }}" class="text-white hover:text-gray-300" title="Help">
                            <i class="fa-solid fa-question text-5xl"></i>
                        </a>
                        @if (Auth::check())
                            <!-- 
                            <a href="{{ url('/logout') }}" title="Logout" class="text-red-700 hover:text-red-500">
                                <i class="fa-solid fa-right-from-bracket text-5xl"></i>
                            </a> 
                            -->
                            <span class="text-purple-800 font-medium text-5xl">{{ Auth::user()->picture }}</span>
                        @else
                            <a href="{{ url('/login') }}" title="Login" class="text-red-700 hover:text-red-500">
                                <i class="fa-solid fa-right-to-bracket text-5xl"></i>
                            </a>
                        @endif
                    </div>
                </header>

                <!-- Main Content Section -->
                <section id="content" class="p-6 rounded-md max-w-6xl mx-auto">
                    @yield('content')
                </section>
            </main>
        </body>
</html>