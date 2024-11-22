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
    <body class="bg-white flex flex-col min-h-screen">
        <!-- Header Section -->
        <header class="flex justify-between items-center p-4 bg-wisteria shadow-lg relative">
            <!-- Logo/Title -->
            <h1 class="text-3xl font-bold text-whiteSmoke">
                <a href="{{ url('/home') }}" class="hover:text-darkSlateGrey font-montserrat text-5xl">Lend-A-Hand</a>
            </h1>

            <!-- Navigation Icons -->
            <div class="flex items-center space-x-6">
                <!-- Search Icon -->
                <a href="javascript:void(0)" onclick="toggleSearchBar()" class="text-whiteSmoke hover:text-darkSlateGrey" title="Events">
                    <i class="fa-solid fa-magnifying-glass text-3xl"></i>
                </a>

                <!-- Create Event -->
                <a href="{{ url('/event/create') }}" title="Create Event" class="text-whiteSmoke hover:text-darkSlateGrey">
                    <i class="fa-solid fa-plus text-3xl"></i>
                </a>

                <!-- Info Icon -->
                <a href="{{ url('/info') }}" class="text-whiteSmoke hover:text-darkSlateGrey" title="Info">
                    <i class="fa-solid fa-circle-info text-3xl"></i>
                </a>

                <!-- Help Icon -->
                <a href="{{ url('/help') }}" class="text-whiteSmoke hover:text-darkSlateGrey" title="Help">
                    <i class="fa-solid fa-question text-3xl"></i>
                </a>

                @if (Auth::guest() && !Request::is('login'))
                    <!-- Login Icon -->
                    <a href="{{ url('/login') }}" title="Login" class="text-yellow hover:text-darkSlateGrey">
                        <i class="fa-solid fa-right-to-bracket text-3xl"></i>
                    </a>
                @endif

                @if (Auth::check())
                    <!-- Profile Icon -->
                    @if (Request::is('user/*'))
                        <a href="{{ url('/logout') }}" title="Logout" class="text-ultraViolet hover:text-cambridgeBlue">
                            <i class="fa-solid fa-right-from-bracket text-3xl"></i>
                        </a>
                    @else
                        <a href="{{ route('user.profile', ['id' => Auth::user()->id]) }}">
                            <img src="{{ asset('images/profile-pictures/' . Auth::user()->profilepicture) }}" 
                                 alt="User Profile Picture" 
                                 class="w-16 h-16 rounded-full object-cover border-2 border-whiteSmoke hover:border-darkSlateGrey">
                        </a>
                    @endif
                @endif
            </div>
        </header>

        <!-- Search Bar & Results -->
        <div id="searchBar" class="hidden absolute top-0 left-1/2 transform -translate-x-1/2 mt-16 w-full max-w-4xl p-4 bg-white rounded-lg shadow-lg z-10">
            <div class="relative w-full">
                <input type="text" id="searchInput" 
                    class="search-input p-4 w-full rounded-full border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none pl-12" 
                    placeholder="Search events..." onkeyup="searchEvents()">
            </div>
            
            <div id="searchResults" class="mt-4 max-h-80 overflow-y-auto"></div>
        </div>

        <main id="main" class="flex items-center justify-center flex-1 overflow-auto p-8">
            <!-- Content Section -->
            @yield('content')
        </main>

        <!-- Footer Section -->
        <footer class="bg-wisteria flex items-center justify-center text-center font-bold font-montserrat text-surface/75 h-16">
            <p class="text-white">&copy; Lend-A-Hand 2024</p>
        </footer>

        <script>
            function toggleSearchBar() {
                const searchBar = document.getElementById('searchBar');
                const searchInput = document.getElementById('searchInput');
                const searchResults = document.getElementById('searchResults');

                searchBar.classList.toggle('hidden');

                if (searchBar.classList.contains('hidden')) {
                    searchInput.value = '';
                    searchResults.innerHTML = '';
                }
            }

            function searchEvents() {
                const query = document.getElementById('searchInput').value;
                console.log("Searching for: " + query); // Add this line to debug the input

                if (query.length >= 3) {
                    fetch(`/search?q=${query}`)
                        .then(response => response.json())
                        .then(data => {
                            console.log('Search results:', data); // Debug the response
                            displaySearchResults(data);
                        })
                        .catch(error => {
                            console.error('Error fetching search results:', error);
                        });
                } else {
                    document.getElementById('searchResults').innerHTML = ''; // Clear results if less than 3 characters
                }
            }


            function displaySearchResults(events) {
                const resultsContainer = document.getElementById('searchResults');
                resultsContainer.innerHTML = '';

                if (events.length === 0) {
                    resultsContainer.innerHTML = '<p class="text-center text-gray-500">No events found.</p>';
                } else {
                    events.forEach(event => {
                        const eventItem = document.createElement('div');
                        eventItem.classList.add('bg-white', 'p-4', 'rounded-lg', 'shadow-md', 'mb-4', 'border', 'border-gray-200');

                        const formattedDate = event.start_date 
                            ? new Date(event.start_date).toLocaleString() 
                            : 'Not available';

                        eventItem.innerHTML = `
                            <div class="event-card">
                                <h3 class="font-semibold text-xl text-gray-800">
                                    <a href="/events/${event.id}" class="text-md font-semibold font-montserrat text-ultraViolet hover:text-cambridgeBlue">${event.name}</a>
                                </h3>
                                <p class="text-sm font-semibold font-montserrat text-darkSlateGrey">${event.description || 'No description available'}</p>
                                <p class="text-sm font-semibold font-montserrat text-darkSlateGrey">${formattedDate}</p>
                            </div>
                        `;

                        resultsContainer.appendChild(eventItem);
                    });
                }
            }
        </script>
    </body>
</html>
