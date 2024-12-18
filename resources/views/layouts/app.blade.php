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
        <meta name="user-id" content="{{ Auth::id() }}">

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
            
            <!-- Message Popup -->
            <div id="popup" class="fixed bottom-0 right-0 z-50 p-4 mb-4 mr-4 transition-transform duration-500 ease-in-out transform translate-x-full bg-white rounded-lg shadow-lg opacity-0 text-darkSlateGrey">
                <div class="flex items-start justify-between">
                    <div class="pr-4">
                        <h3 id="popup-title" class="text-2xl font-semibold font-montserrat"></h3>
                        <p id="popup-message" class="mt-1 text-md"></p>
                    </div>

                    <button id="popup-close" class="bg-transparent border-transparent hover:border-transparent focus:border-transparent hover:bg-transparent focus:bg-transparent focus:text-red-500 text-darkSlateGrey hover:text-red-500">
                        <i class="text-4xl fa-solid fa-times"></i>
                    </button>
                </div>
            </div>
            @if (session('message'))
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const popup = document.getElementById('popup');
                        const popupTitle = document.getElementById('popup-title');
                        const popupMessage = document.getElementById('popup-message');
                        const closeBtn = document.getElementById('popup-close');

                        const success = '{{ session('success') }}';
                        const message = '{{ session('message') }}';

                        popupMessage.textContent = message;

                        if (success) {
                            popupTitle.textContent = 'Success';
                            popupTitle.classList.add('text-green-500');
                            popup.classList.add('border-green-500');
                            popup.style.borderWidth = '1mm'; 
                        } else {
                            popupTitle.textContent = 'Error';
                            popupTitle.classList.add('text-red-500');
                            popup.classList.add('border-red-500');
                            popup.style.borderWidth = '1mm'; 
                        }

                        popup.classList.remove('translate-x-full', 'opacity-0');
                        popup.classList.add('translate-x-0', 'opacity-100');

                        closeBtn.addEventListener('click', function () {
                            popup.classList.remove('translate-x-0', 'opacity-100');
                            popup.classList.add('translate-x-full', 'opacity-0');
                        });

                        setTimeout(() => {
                            popup.classList.remove('translate-x-0', 'opacity-100');
                            popup.classList.add('translate-x-full', 'opacity-0');
                        }, 5000);
                    });
                </script>
            @endif
        </div>
        </main>


        <!-- Footer -->
        @include('partials.footer')
    </body>

</html>
