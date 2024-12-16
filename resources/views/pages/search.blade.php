@extends('layouts.app')

@section('Title', 'Search')

@section('content')
<!-- Events by Category Section -->
<section class="w-full px-6 py-16">

    <!-- Main Grid Layout -->
    <div class="grid grid-cols-5 gap-6">
        
        <!-- Filters Section -->
        <form method="GET" id="filtersForm" class="flex flex-col gap-6">

            <p class="text-md font-montserrat font-semibold text-darkSlateGrey mb-4">
                Number of events: <span class="text-ultraViolet">{{ request('count') }}</span>
            </p>

            <!-- Display Search Context -->
            @if(request('q'))
                <p class="text-md font-montserrat font-semibold text-darkSlateGrey mb-4">
                    Showing events for: <span class="text-ultraViolet">{{ request('q') }}</span>
                </p>
                <!-- Hidden Input to Preserve Search Query -->
                <input type="hidden" name="q" value="{{ request('q') }}">
            @endif

            <!-- Sort by date -->
            <div class="flex items-center">
                <p class="text-md font-montserrat font-semibold text-darkSlateGrey">
                    Sort by date: 
                </p>
                <select name="order" id="order" class="pl-2 text-md font-montserrat font-semibold text-ultraViolet border-0 rounded w-[9em]">
                    <option value="date_asc"  {{ request('order') === 'date_asc'  ? 'selected' : '' }}>Ascending</option>
                    <option value="date_desc" {{ request('order') === 'date_desc' ? 'selected' : '' }}>Descending</option>
                </select>
            </div>

            <!-- Results per page -->
            <div class="flex items-center">
                <p class="text-md font-montserrat font-semibold text-darkSlateGrey">
                    Events per page: 
                </p>
                <select name="results" id="results" class="pl-2 text-md font-montserrat font-semibold text-ultraViolet border-0 rounded w-[4em]">
                    <option value="21"  {{ request('results') === '21'  ? 'selected' : '' }}>21</option>
                    <option value="30"  {{ request('results') === '30'  ? 'selected' : '' }}>30</option>
                    <option value="45"  {{ request('results') === '45'  ? 'selected' : '' }}>45</option>
                </select>
            </div>


            <!-- Filters Label -->
            <h2 class="text-4xl font-bold font-montserrat text-ultraViolet">Filter</h2>

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
                <label class="block font-montserrat font-semibold cursor-pointer flex items-center justify-between" onclick="toggleSectionVisibility('visibility')">
                    <span>Visibility</span>
                    <i id="visibility-arrow" class="fa-solid fa-angle-right transform transition-transform duration-200"></i>
                </label>
                <div class="mt-2 hidden" id="visibility">
                    <label class="flex items-center space-x-2 mt-1">
                        <input type="checkbox" name="status[]" value="public" 
                            {{ request('visibility') === 'public' ? 'checked' : '' }} />
                        <span>Public</span>
                    </label>
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="status[]" value="request_only" 
                            {{ request('visibility') === 'request_only' ? 'checked' : '' }} />
                        <span>Request Only</span>
                    </label>
                </div>
            </div>

            <!-- Category -->
            <div>
                <label class="block font-montserrat font-semibold cursor-pointer flex items-center justify-between" onclick="toggleSectionVisibility('category')">
                    <span>Category</span>
                    <i id="category-arrow" class="fa-solid fa-angle-right transform transition-transform duration-200"></i>
                </label>
                <div class="mt-2 hidden" id="category">
                    @foreach ($categories as $category)
                        <label class="flex items-center space-x-2 mt-1">
                            <input type="checkbox" name="category_id[]" value="{{ $category->id }}" 
                                {{ request('category_id') && in_array($category->id, request('category_id')) ? 'checked' : '' }} />
                            <span>{{ $category->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Location -->
            <div>
                <label class="block font-montserrat font-semibold cursor-pointer flex items-center justify-between" onclick="toggleSectionVisibility('location')">
                    <span>Location</span>
                    <i id="location-arrow" class="fa-solid fa-angle-right transform transition-transform duration-200"></i>
                </label>
                <div class="mt-2 hidden" id="location">
                    @foreach ($locations as $location)
                        <label class="flex items-center space-x-2 mt-1">
                            <input type="checkbox" name="location[]" value="{{ $location->id }}" 
                                {{ request('location') && in_array($location->id, request('location')) ? 'checked' : '' }} />
                            <span>{{ $location->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Status -->
            <div>
                <label class="block font-montserrat font-semibold cursor-pointer flex items-center justify-between" onclick="toggleSectionVisibility('status')">
                    <span>Status</span>
                    <i id="status-arrow" class="fa-solid fa-angle-right transform transition-transform duration-200"></i>
                </label>
                <div class="mt-2 hidden" id="status">
                    <label class="flex items-center space-x-2 mt-1">
                        <input type="checkbox" name="status[]" value="full" 
                            {{ request('status') && in_array('full', request('status')) ? 'checked' : '' }} />
                        <span>Finished</span>
                    </label>
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="status[]" value="canceled" 
                            {{ request('status') && in_array('canceled', request('status')) ? 'checked' : '' }} />
                        <span>On going</span>
                    </label>
                    <label class="flex items-center space-x-2 mt-1">
                        <input type="checkbox" name="status[]" value="upcoming" 
                            {{ request('status') && in_array('upcoming', request('status')) ? 'checked' : '' }} />
                        <span>Upcoming</span>
                    </label>
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="status[]" value="canceled" 
                            {{ request('status') && in_array('canceled', request('status')) ? 'checked' : '' }} />
                        <span>Canceled</span>
                    </label>
                    <label class="flex items-center space-x-2 mt-1">
                        <input type="checkbox" name="status[]" value="full_capacity" 
                            {{ request('status') && in_array('full_capacity', request('status')) ? 'checked' : '' }} />
                        <span>At full capacity</span>
                    </label>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex space-x-4 w-full">
                <!-- Submit Button -->
                <button type="submit" 
                    class="flex-1 px-6 py-2 font-bold text-white transition-all duration-200 rounded-md font-montserrat bg-ultraViolet hover:bg-cambridgeBlue hover:text-darkSlateGrey border-ultraViolet hover:border-cambridgeBlue">
                    Apply
                </button>
                
               <!-- Reset Button -->
                <button type="button" 
                        onclick="window.location.href = '{{ url()->current() }}';"
                        class="flex-1 px-6 py-2 font-bold text-white transition-all duration-200 rounded-md font-montserrat bg-ultraViolet hover:bg-cambridgeBlue hover:text-darkSlateGrey border-ultraViolet hover:border-cambridgeBlue">
                    Reset
                </button>
            </div>
        </form>

        <!-- Events Grid -->
        <div class="col-span-4 grid gap-6 sm:grid-cols-2 md:grid-cols-3 auto-rows-min">
            @forelse ($events as $event)
                @include('partials.event', ['event' => $event])
            @empty
                <p class="text-eerieBlack font-montserrat col-span-full">No events available in this category.</p>
            @endforelse
        </div>

    </div>
</section>

<script>
    function toggleSectionVisibility(id) {
        const content = document.getElementById(id);
        const arrow = document.getElementById(id + '-arrow');
        
        // Toggle visibility of the content
        if (content.classList.contains('hidden')) {
            content.classList.remove('hidden');
            content.classList.add('block');
            // Rotate the arrow down (90 degrees)
            arrow.classList.remove('rotate-0');
            arrow.classList.add('rotate-90');
        } else {
            content.classList.remove('block');
            content.classList.add('hidden');
            // Rotate the arrow right (0 degrees)
            arrow.classList.remove('rotate-90');
            arrow.classList.add('rotate-0');
        }
    }
</script>

@endsection

