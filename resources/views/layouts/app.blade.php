<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="h-full">
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

        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">


        <script type="text/javascript" src="{{ url('js/app.js') }}" defer></script>
        @vite('resources/css/app.css')
    </head>
    <body class="bg-white h-full flex flex-col">
        <main class="flex-1 h-screen">
            <!-- Header Section -->
            <header class="flex justify-between items-center px-12 py-6 bg-wisteria shadow-lg">
                <!-- Logo/Title -->
                <h1 class="text-3xl font-bold text-whiteSmoke">
                    <a href="{{ url('/home') }}" class="hover:text-darkSlateGrey font-montserrat text-5xl">Lend-A-Hand</a>
                </h1>

                <!-- Navigation Icons -->
                <div class="flex items-center space-x-6">
                    <!-- All Events Page Button -->
                    <a href="{{ url('/events') }}" class="text-whiteSmoke hover:text-darkSlateGrey" title="Events">
                        <i class="fa-solid fa-house text-3xl"></i>
                    </a>

                    <!-- Create Event -->
                    <a href="{{ url('/event/create') }}" title="Create Event" class="text-whiteSmoke hover:text-darkSlateGrey">
                        <i class="fa-solid fa-plus text-3xl"></i>
                    </a>

                    <!-- Info Button -->
                    <a href="{{ url('/info') }}" class="text-whiteSmoke hover:text-darkSlateGrey" title="Info">
                        <i class="fa-solid fa-circle-info text-3xl"></i>
                    </a>

                    <!-- Help Button -->
                    <a href="{{ url('/help') }}" class="text-whiteSmoke hover:text-darkSlateGrey" title="Help">
                        <i class="fa-solid fa-question text-3xl"></i>
                    </a>

                    @if (Auth::guest() && !Request::is('login'))
                        <!-- Login Button -->
                        <a href="{{ url('/login') }}" title="Login" class="text-yellow hover:text-darkSlateGrey">
                            <i class="fa-solid fa-right-to-bracket text-3xl"></i>
                        </a>
                    @endif

                    @if (Auth::check())
                        <!-- Profile Button -->
                        @if (Request::is('user/*'))
                            <a href="{{ url('/logout') }}" title="Logout" class="text-ultraViolet hover:text-cambridgeBlue">
                                <i class="fa-solid fa-right-from-bracket text-3xl"></i>
                            </a>
                        @else
                            <a href="{{ route('user.profile', ['id' => Auth::user()->id]) }}">
                                <img src="{{ asset('storage/' . Auth::user()->profilepicture) }}" 
                                     alt="User Profile Picture" 
                                     class="w-12 h-12 rounded-full object-cover border-2 border-whiteSmoke hover:border-darkSlateGrey">
                            </a>
                        @endif
                    @endif
                </div>
            </header>

            <!-- Events Section -->
            <section id="content" class="p-8 bg-white rounded-md h-full shadow-md">
                @yield('content')
            </section>

            <!-- Footer Section -->
            <footer class="bg-wisteria font-bold font-montserrat text-whiteSmoke w-full fixed bottom-0 px-10 py-10 left-0 h-16 flex justify-center items-center shadow-lg">
                <p class="text-white">&copy; Lend-A-Hand 2024</p>
            </footer>
        </main>
    </body>
</html>
