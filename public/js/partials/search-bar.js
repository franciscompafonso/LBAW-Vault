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
        window.location.href = `/events/search?q=${encodeURIComponent(query)}&source=bar`;
    }
}

function searchEvents(event) {
    const query = document.getElementById('searchInput').value;

    if (event.key === 'Enter' && query.length >= 3) {
        window.location.href = `/events/search?q=${encodeURIComponent(query)}&source=bar`;
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