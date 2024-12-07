@if (!Request::is('login') && !Request::is('register'))
    <!-- Search Bar & Results -->
    <div id="searchBar" 
        class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full max-w-2xl z-50">
        <input type="text" id="searchInput" 
            class="w-full p-3 border border-gray-300 rounded-full search-input focus:ring-2 focus:ring-blue-500 focus:outline-none" 
            placeholder="Search events..." 
            onkeyup="searchEvents(event)" 
            onkeydown="searchEvents(event)">
        
        <!-- Floating Results -->
        <div id="searchResults" 
            class="absolute w-full mt-2 bg-white rounded-lg shadow-lg max-h-80 overflow-y-auto z-40">
        </div>
    </div>

    <script>
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
                        <a href="/events/${event.id}" class="block font-semibold text-md text-ultraViolet hover:text-cambridgeBlue">
                            ${event.name}
                        </a>
                    `;

                    resultsContainer.appendChild(eventItem);
                });
            }
        }
    </script>
@endif