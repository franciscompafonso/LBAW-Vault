@if (!Request::is('login') && !Request::is('register') && !Request::is('password/reset'))
<script type="text/javascript" src="{{ url('js/partials/search-bar.js') }}" defer></script>
    <!-- Search Bar -->
    <div id="searchBar" class="w-full lg:w-[30%] lg:order-none order-last mt-6 lg:mt-0">
        <div class="relative w-full mx-auto">
            <input type="text" id="searchInput" 
                   class="w-full p-6 pl-6 pr-12 text-3xl text-black border-2 rounded-full border-ultraViolet font-montserrat search-input focus:ring-2 focus:ring-wisteria focus:border-wisteria focus:outline-none placeholder:text-3xl placeholder:text-black" 
                   placeholder="Search events..." 
                   autocomplete="off"
                   aria-label="Search events"
                   onkeyup="searchEvents(event)" 
                   onkeydown="searchEvents(event)"
                   onfocus="showSearchResults()" 
                   onblur="hideSearchResults()">

            <i class="absolute right-4 top-1/2 transform -translate-y-1/2 text-4xl fa-solid fa-search {{ Auth::guard('admin')->check() ? 'text-ultraViolet' : 'text-wisteria' }} cursor-pointer" 
               onclick="performSearch()"
               aria-label="Search button">
            </i>
        </div>

        <!-- Floating Results -->
        <div id="searchResults" class="absolute mt-2 bg-white rounded-lg shadow-lg max-h-80 overflow-y-auto z-40 w-full lg:w-[30%]" aria-live="polite" role="listbox">
            <!-- Dynamically populated search results will go here -->
        </div>
    </div>
@endif
