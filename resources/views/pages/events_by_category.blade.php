@extends('layouts.app')

@if($category)
    @section('Title', $category->name)
@else
    @section('Title', 'All Categories')
@endif

@section('content')
<!-- Events by Category Section -->
<section class="w-full px-6 py-16">
    <h1 class="mb-6 text-5xl font-bold text-ultraViolet font-montserrat">
        @if($category)
            {{ $category->name }}
        @else
            All Categories
        @endif
    </h1>

    <!-- Main Grid Layout -->
    <div class="grid grid-cols-7 gap-6">
        <!-- Filters Section -->
        <form method="GET" id="filtersForm" class="flex flex-col gap-6">
            <!-- Category as Hidden Input -->
            <input type="hidden" name="category_id" value="{{ $category ? $category->id : '' }}">

            <!-- Filters Label -->
            <h2 class="text-4xl font-bold font-montserrat text-ultraViolet">Filters</h2>

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
                    <option value="date_asc" {{ request('order') === 'date_asc' ? 'selected' : '' }}>Date Ascending</option>
                    <option value="date_desc" {{ request('order') === 'date_desc' ? 'selected' : '' }}>Date Descending</option>
                    <option value="occu_asc" {{ request('order') === 'occu_asc' ? 'selected' : '' }}>Occupation Ascending</option>
                    <option value="occu_desc" {{ request('order') === 'occu_desc' ? 'selected' : '' }}>Occupation Descending</option>
                </select>
            </div>

            <!-- Submit Button -->
            <button type="submit" 
                    class="px-6 py-2 font-bold text-white transition-all duration-200 rounded-md font-montserrat bg-ultraViolet hover:bg-cambridgeBlue hover:text-darkSlateGrey border-ultraViolet hover:border-cambridgeBlue">
                Apply Filters
            </button>
        </form>

        <!-- Events Grid -->
        <div class="col-span-6">
            <div class="grid gap-10 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                @forelse ($events as $event)
                    @include('partials.event', ['event' => $event])
                @empty
                    <p class="text-eerieBlack font-montserrat col-span-full">No events available in this category.</p>
                @endforelse
            </div>
        </div>
    </div>
</section>



<!-- Validation Script -->
<script>
    const startDateInput = document.getElementById('startDate');
    const endDateInput = document.getElementById('endDate');

    // Function to update min/max dynamically
    function updateConstraints() {
        if (startDateInput.value) {
            endDateInput.min = startDateInput.value; // Set min for endDate
        } else {
            endDateInput.min = ''; // Reset min if no startDate
        }
        
        if (endDateInput.value) {
            startDateInput.max = endDateInput.value; // Set max for startDate
        } else {
            startDateInput.max = ''; // Reset max if no endDate
        }
    }

    // Event listeners to handle date changes
    startDateInput.addEventListener('change', () => {
        if (new Date(startDateInput.value) > new Date(endDateInput.value)) {
            endDateInput.value = startDateInput.value; // Sync end to start if invalid
        }
        updateConstraints(); // Update constraints dynamically
    });

    endDateInput.addEventListener('change', () => {
        if (new Date(endDateInput.value) < new Date(startDateInput.value)) {
            startDateInput.value = endDateInput.value; // Sync start to end if invalid
        }
        updateConstraints(); // Update constraints dynamically
    });

    // Initial Update
    updateConstraints();
</script>

@endsection

