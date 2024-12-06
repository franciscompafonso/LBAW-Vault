<!-- Filters Section -->
<form method="GET" id="filtersForm" class="flex flex-col gap-6">

    <!-- Filters Label -->
    <h2 class="text-4xl font-bold font-montserrat text-ultraViolet">Filters</h2>

    <!-- Display Search Context -->
    @if(request('q'))
        <p class="text-md font-montserrat font-semibold text-darkSlateGrey">
            Searching for: <span class="text-ultraViolet">{{ request('q') }}</span>
        </p>
        <!-- Hidden Input to Preserve Search Query -->
        <input type="hidden" name="q" value="{{ request('q') }}">
    @endif

    <!-- Date Range -->
    <div>
        <label for="startDate" class="block font-montserrat font-semibold">Start Date</label>
        <input 
            type="date" 
            name="startDate" 
            id="startDate" 
            class="p-2 border rounded mt-2" 
            value="{{ request('startDate') }}"
            max="" 
        >
        
        <label for="endDate" class="block font-montserrat font-semibold mt-4">End Date</label>
        <input 
            type="date" 
            name="endDate" 
            id="endDate" 
            class="p-2 border rounded mt-2" 
            value="{{ request('endDate') }}"
            min="" 
        >
    </div>

    <!-- Visibility -->
    <div>
        <label class="block font-montserrat font-semibold">Visibility</label>
        <div class="mt-2">
            <label class="flex items-center space-x-2">
                <input type="checkbox" name="visibility[]" value="public" 
                    {{ request('visibility') && in_array('public', request('visibility')) ? 'checked' : '' }} />
                <span>Public</span>
            </label>
            <label class="flex items-center space-x-2 mt-1">
                <input type="checkbox" name="visibility[]" value="request_only" 
                    {{ request('visibility') && in_array('request_only', request('visibility')) ? 'checked' : '' }} />
                <span>Request Only</span>
            </label>
        </div>
    </div>

    <!-- Status -->
    <div>
        <label class="block font-montserrat font-semibold">Status</label>
        <div class="mt-2">
            <label class="flex items-center space-x-2">
                <input type="checkbox" name="status[]" value="canceled" 
                    {{ request('status') && in_array('canceled', request('status')) ? 'checked' : '' }} />
                <span>Canceled</span>
            </label>
            <label class="flex items-center space-x-2 mt-1">
                <input type="checkbox" name="status[]" value="full" 
                    {{ request('status') && in_array('full', request('status')) ? 'checked' : '' }} />
                <span>Full Event</span>
            </label>
        </div>
    </div>

    <!-- Category -->
    <div>
        <label for="category" class="block font-montserrat font-semibold">Category</label>
        <select name="category_id" id="category" class="p-2 border rounded mt-2">
            <option value="">All Categories</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Location -->
    <div>
        <label for="location" class="block font-montserrat font-semibold">Location</label>
        <select name="location" id="location" class="p-2 border rounded mt-2">
            <option value="">Any Location</option>
            @foreach ($locations as $location)
                <option value="{{ $location->id }}" {{ request('location') == $location->id ? 'selected' : '' }}>
                    {{ $location->name }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Sort by -->
    <div>
        <label for="order" class="block font-montserrat font-semibold">Sort by</label>
        <select name="order" id="order" class="p-2 border rounded mt-2">
            <option value="date_asc"  {{ request('order') === 'date_asc'  ? 'selected' : '' }}>Date Ascending</option>
            <option value="date_desc" {{ request('order') === 'date_desc' ? 'selected' : '' }}>Date Descending</option>
            <option value="part_asc"  {{ request('order') === 'part_asc'  ? 'selected' : '' }}>Participants Ascending</option>
            <option value="part_desc" {{ request('order') === 'part_desc' ? 'selected' : '' }}>Participants Descending</option>
        </select>
    </div>

    <!-- Buttons -->
    <div class="flex space-x-4">
        <!-- Submit Button -->
        <button type="submit" 
                class="px-6 py-2 font-bold text-white transition-all duration-200 rounded-md font-montserrat bg-ultraViolet hover:bg-cambridgeBlue hover:text-darkSlateGrey border-ultraViolet hover:border-cambridgeBlue">
            Apply Filters
        </button>
        
        <!-- Reset Button -->
        <a href="{{ url()->current() }}" 
           class="px-6 py-2 font-bold text-darkSlateGrey transition-all duration-200 rounded-md font-montserrat bg-gray-200 hover:bg-gray-300 border-gray-300 hover:border-gray-400">
            Reset Filters
        </a>
    </div>
</form>