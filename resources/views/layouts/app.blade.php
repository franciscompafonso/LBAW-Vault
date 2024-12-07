<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

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
    <body class="flex flex-col min-h-screen bg-white">
        @include('partials.topbar')

        <!-- Search Bar & Results -->
        <div id="searchBar" class="absolute top-0 z-10 hidden w-full max-w-4xl p-4 mt-16 transform -translate-x-1/2 bg-white rounded-lg shadow-lg left-1/2">
            <div class="relative w-full">
            <input type="text" id="searchInput" 
                class="w-full p-4 pl-12 border border-gray-300 rounded-full search-input focus:ring-2 focus:ring-blue-500 focus:outline-none" 
                placeholder="Search events..." 
                onkeyup="searchEvents(event)" 
                onkeydown="searchEvents(event)">
            </div>
            
            <div id="searchResults" class="mt-4 overflow-y-auto max-h-80"></div>
        </div>

        <main id="main" 
            class="flex items-center justify-center flex-1 p-8 overflow-auto" 
            style="
                {{ Request::is('faqs') ? 'padding-top: 250px;' : '' }}
                {{ Request::is('events/*/survey/stats') && isset($hasResponses) && $hasResponses ? 'padding-top: 1000px;' : '' }}
            ">
            @yield('content')
            <div id="notification-container" class="fixed bottom-5 right-5 z-50"></div>
        </main>

        @include('partials.footer')

        <script>

            // Search
            function toggleSearchBar() {
                const searchBar = document.getElementById('searchBar');
                const searchInput = document.getElementById('searchInput');
                const searchResults = document.getElementById('searchResults');

                searchBar.classList.toggle('hidden');

                if (!searchBar.classList.contains('hidden')) {
                    searchInput.focus(); // Auto-focus the input field
                } else {
                    searchInput.value = '';
                    searchResults.innerHTML = '';
                }
            }

            function searchEvents(event) {
                const query = document.getElementById('searchInput').value;

                // Detect Enter Key
                if (event.key === 'Enter' && query.length >= 3) {
                    window.location.href = `/events/search?q=${encodeURIComponent(query)}`;
                    return; // Stop further execution
                }

                // Fetch and display results for dropdown
                if (query.length >= 3) {
                    fetch(`/api/search?q=${query}`)
                        .then(response => response.json())
                        .then(data => {
                            displaySearchResults(data);
                        })
                        .catch(error => {
                            console.error('Error fetching search results:', error);
                        });
                } else {
                    document.getElementById('searchResults').innerHTML = '';
                }
            }


            function displaySearchResults(events) {
                const resultsContainer = document.getElementById('searchResults');
                resultsContainer.innerHTML = '';

                if (events.length === 0) {
                    resultsContainer.innerHTML = '<p class="text-center text-gray-500">No events found.</p>';
                } else {
                    events.slice(0, 5).forEach(event => { // Limit to 4 results here for the dropdown
                        const eventItem = document.createElement('div');
                        eventItem.classList.add('p-2', 'hover:bg-gray-100', 'cursor-pointer', 'text-ultraViolet', 'font-montserrat');

                        const eventUrl = `/events/${event.id}`;

                        eventItem.innerHTML = `
                            <a href="${eventUrl}" class="font-semibold text-md font-montserrat text-ultraViolet hover:text-cambridgeBlue">${event.name}</a>
                        `;

                        resultsContainer.appendChild(eventItem);
                    });
                }
            }
        </script>
    </body>
</html>
