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
        <link rel="stylesheet" href="{{ url('css/print.css') }}" media="print">

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
            <div id="popup" class="fixed bottom-0 right-0 z-50 p-4 mb-4 mr-4 transition-transform duration-500 ease-in-out transform translate-x-full bg-white border-2 rounded-lg shadow-lg opacity-0 text-darkSlateGrey">
                <div class="flex items-start justify-between">
                    <div class="pr-4">
                        <div class="grid items-center grid-cols-8 gap-4">
                            <div class="flex justify-center col-span-1">
                                <i id="success-icon" class="hidden text-3xl text-green-500 fas fa-check-circle"></i>
                                <i id="warning-icon" class="hidden text-3xl text-red-500 fas fa-exclamation-circle"></i>
                            </div>
                            <div class="col-span-7">
                                <h3 id="popup-title" class="text-2xl font-semibold font-montserrat"></h3>
                            </div>
                        </div>
                        <p id="popup-message" class="mt-1 text-md"></p>
                    </div>

                    <button id="popup-close" class="bg-transparent border-transparent hover:border-transparent focus:border-transparent hover:bg-transparent focus:bg-transparent focus:text-red-500 text-darkSlateGrey hover:text-red-500">
                        <i class="text-4xl fa-solid fa-times"></i>
                    </button>
                </div>
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const success = '{{ session('success') }}';
                    const message = '{{ session('message') }}';

                    console.log('Success:', success);
                    console.log('Message:', message);

                    if (message) {
                        const popup = document.getElementById('popup');
                        const popupTitle = document.getElementById('popup-title');
                        const popupMessage = document.getElementById('popup-message');
                        const closeBtn = document.getElementById('popup-close');
                        const warningIcon = document.getElementById('warning-icon');
                        const checkIcon = document.getElementById('success-icon');

                        popupMessage.textContent = message;

                        if (success) {
                            popupTitle.textContent = 'Success';
                            popupTitle.classList.add('text-green-500');
                            popup.classList.add('border-green-500');
                            warningIcon.classList.add('hidden');
                            checkIcon.classList.remove('hidden');
                        } else {
                            popupTitle.textContent = 'Error';
                            popupTitle.classList.add('text-red-500');
                            popup.classList.add('border-red-500');
                            warningIcon.classList.remove('hidden');
                            checkIcon.classList.add('hidden');
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
                    } else {
                        console.warn('No message found in session.');
                    }
                });

                // Function to display the popup
                function showPopup(success, message) {
                    const popup = document.getElementById('popup');
                    const popupTitle = document.getElementById('popup-title');
                    const popupMessage = document.getElementById('popup-message');
                    const closeBtn = document.getElementById('popup-close');
                    const warningIcon = document.getElementById('warning-icon');
                    const checkIcon = document.getElementById('success-icon');

                    popupMessage.textContent = message;

                    if (success) {
                        popupTitle.textContent = 'Success';
                        popupTitle.classList.remove('text-red-500');
                        popupTitle.classList.add('text-green-500');
                        popup.classList.remove('border-red-500');
                        popup.classList.add('border-green-500');
                        warningIcon.classList.add('hidden');
                        checkIcon.classList.remove('hidden');
                    } else {
                        popupTitle.textContent = 'Error';
                        popupTitle.classList.remove('text-green-500');
                        popupTitle.classList.add('text-red-500');
                        popup.classList.remove('border-green-500');
                        popup.classList.add('border-red-500');
                        warningIcon.classList.remove('hidden');
                        checkIcon.classList.add('hidden');
                    }

                    popup.classList.remove('translate-x-full', 'opacity-0');
                    popup.classList.add('translate-x-0', 'opacity-100');

                    closeBtn.addEventListener('click', () => hidePopup(popup));

                    setTimeout(() => hidePopup(popup), 6000);
                }

                // Function to hide the popup
                function hidePopup(popup) {
                    popup.classList.remove('translate-x-0', 'opacity-100');
                    popup.classList.add('translate-x-full', 'opacity-0');
                }
            </script>
        </main>


        <!-- Footer -->
        @include('partials.footer')
    </body>

</html>
