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
        
        <script type="text/javascript">
            // Fix for Firefox autofocus CSS bug
            // See: http://stackoverflow.com/questions/18943276/html-5-autofocus-messes-up-css-loading/18945951#18945951
        </script>
        <script type="text/javascript" src="{{ url('js/app.js') }}" defer></script>
        @vite('resources/css/app.css')
    </head>
    <body class="bg-gray-50 h-full flex flex-col">
        <main class="flex-1 h-screen">
            <!-- Header Section -->
            <header class="flex justify-between items-center px-12 py-12 bg-blue-400 shadow-lg">
                <!-- Logo/Title -->
                <h1 class="text-3xl font-bold text-purple-900">
                    <a href="{{ url('/cards') }}" class="hover:text-gray-300 text-7xl font-semibold text-white">Lend-a-Hand</a>
                </h1>

                <!-- Navigation Icons -->
                <div class="flex items-center space-x-6">
                    @if (!Request::is('login')) <!-- Only show if NOT on the login page -->
                        <!-- Logout Button -->
                        @if (Auth::check())
                            <a href="{{ url('/logout') }}" title="Logout" class="text-red-700 hover:text-red-500">
                                <i class="fa-solid fa-right-from-bracket text-5xl"></i>
                            </a>
                        @endif
                    @endif
                    
                    <!-- Home Button -->
                    <a href="{{ url('/home') }}" class="text-white hover:text-gray-300" title="Home">
                        <i class="fa-solid fa-house text-5xl"></i>
                    </a>

                    <!-- Info Button -->
                    <a href="{{ url('/info') }}" class="text-white hover:text-gray-300" title="Info">
                        <i class="fa-solid fa-circle-info text-5xl"></i>
                    </a>

                    <!-- Help Button -->
                    <a href="{{ url('/help') }}" class="text-white hover:text-gray-300" title="Help">
                        <i class="fa-solid fa-question text-5xl"></i>
                    </a>

                    <!-- Login Button (only visible if user is not logged in and not on login page) -->
                    @if (Auth::guest() && !Request::is('login'))
                        <a href="{{ url('/login') }}" title="Login" class="text-red-700 hover:text-red-500">
                            <i class="fa-solid fa-right-to-bracket text-5xl"></i>
                        </a>
                    @endif

                    <!-- User Profile -->
                    @if (Auth::check())
                        <img src="{{ asset('images/profile-pictures/' . Auth::user()->profilepicture) }}" 
                            alt="User Profile Picture" 
                            class="w-16 h-16 rounded-full object-cover">
                    @endif
                </div>
            </header>

            <!-- Banner Section -->
            <section class="bg-white py-16 shadow-md">
                <div class="max-w-6xl mx-auto flex items-center flex-wrap lg:flex-nowrap px-6">
                    <!-- Text Section -->
                    <div class="w-full lg:w-1/2 text-left mb-8 lg:mb-0">
                        <h2 class="text-4xl font-semibold mb-4 text-gray-800">Empowering Communities, One Event at a Time.</h2>
                        <p class="text-2xl text-gray-600 mb-6">Join us now!</p>
                    </div>

                    <!-- Image Section -->
                    <div class="w-full lg:w-1/2 flex justify-center lg:justify-end">
                        <img src="{{ asset('images/others/main-pic.png') }}" alt="Main Picture" class="w-64 h-64 rounded-full object-cover shadow-lg">
                    </div>
                </div>
            </section>

            <!-- Categories Section -->
            <section class="py-8 px-4">
                <div class="flex flex-col items-center space-y-4">
                    <button class="w-full max-w-md bg-blue-500 text-white text-lg font-medium py-3 rounded-lg shadow-md hover:bg-blue-600">
                        Category #1
                    </button>
                    <button class="w-full max-w-md bg-blue-500 text-white text-lg font-medium py-3 rounded-lg shadow-md hover:bg-blue-600">
                        Category #2
                    </button>
                    <button class="w-full max-w-md bg-blue-500 text-white text-lg font-medium py-3 rounded-lg shadow-md hover:bg-blue-600">
                        ...
                    </button>
                </div>
            </section>

            <!-- Footer Section -->
            <footer class="bg-white w-full h-16 flex justify-center items-center shadow-md mt-auto">
                <p class="text-gray-600">&copy; Lend-A-Hand 2024</p>
            </footer>
        </main>
    </body>
</html>
