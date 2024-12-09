@if (!Request::is('login') && !Request::is('register') && !Request::is('password/reset'))
    <!-- Search Bar -->
    <div id="searchBar" class="w-full lg:w-[30%] lg:order-none order-last mt-6 lg:mt-0">
        <div class="relative w-full mx-auto">
            <input type="text" id="searchInput" 
                   class="w-full p-6 pl-6 pr-12 border-2 border-ultraViolet rounded-full search-input focus:ring-2 focus:ring-wisteria focus:border-wisteria focus:outline-none text-3xl text-black placeholder:text-3xl placeholder:text-black" 
                   placeholder="Search events..." 
                   autocomplete="off"
                   onkeyup="searchEvents(event)" 
                   onkeydown="searchEvents(event)"
                   onfocus="showSearchResults()" 
                   onblur="hideSearchResults()">

            <i class="absolute right-4 top-1/2 transform -translate-y-1/2 text-4xl fa-solid fa-search {{ Auth::guard('admin')->check() ? 'text-ultraViolet' : 'text-wisteria' }} cursor-pointer" 
               onclick="performSearch()">
            </i>
        </div>

        <!-- Floating Results -->
        <div id="searchResults" class="absolute mt-2 bg-white rounded-lg shadow-lg max-h-80 overflow-y-auto z-40 w-full lg:w-[30%]">
            <!-- Dynamically populated search results will go here -->
        </div>
    </div>

    <script>
        function showSearchResults() {
            document.getElementById('searchResults').classList.remove('hidden');
        }

        function hideSearchResults() {
            setTimeout(() => {
                document.getElementById('searchResults').classList.add('hidden');
            }, 200);  // Delay hiding for smooth user experience, in case user clicks on the results
        }

        function performSearch() {
            const query = document.getElementById('searchInput').value.trim();
            
            // Only perform search if the query length is greater than or equal to 3
            if (query.length >= 3) {
                window.location.href = `/events/search?q=${encodeURIComponent(query)}`;
            }
        }

        function searchEvents(event) {
            const query = document.getElementById('searchInput').value;

            if (event.key === 'Enter' && query.length >= 3) {
                window.location.href = `/events/search?q=${encodeURIComponent(query)}`;
                return;
            }

            if (query.length >= 3) {
                fetch(`/api/search?q=${query}`)
                    .then(response => response.json())
                    .then(data => displaySearchResults(data))
                    .catch(error => console.error('Error fetching search results:', error));
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
                events.slice(0, 5).forEach(event => {
                    const eventItem = document.createElement('div');
                    eventItem.classList.add('p-2', 'hover:bg-gray-100', 'cursor-pointer', 'text-ultraViolet', 'font-montserrat');

                    eventItem.innerHTML = `
                        <a href="/events/${event.id}" class="block font-semibold text-md text-ultraViolet hover:text-cambridgeBlue p-1">
                            ${event.name}
                        </a>
                    `;

                    resultsContainer.appendChild(eventItem);
                });
            }
        }
    </script>
@endif
