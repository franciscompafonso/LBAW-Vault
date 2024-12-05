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
            <!-- Filters Label -->
            <h2 class="text-2xl font-bold font-montserrat text-ultraViolet">Filters</h2>

            <!-- Participant Range -->
            <div>
                <label for="minParticipants" class="block font-montserrat font-semibold">Min Participants</label>
                <input type="range" name="minParticipants" id="minParticipants" min="0" max="500" value="{{ request('minParticipants', 0) }}" class="w-full mt-2">
                <label for="maxParticipants" class="block font-montserrat font-semibold mt-4">Max Participants</label>
                <input type="range" name="maxParticipants" id="maxParticipants" min="0" max="500" value="{{ request('maxParticipants', 500) }}" class="w-full mt-2">
                <div class="flex justify-between text-sm font-montserrat mt-1">
                    <span id="minParticipantsValue">{{ request('minParticipants', 0) }}</span>
                    <span id="maxParticipantsValue">{{ request('maxParticipants', 500) }}</span>
                </div>
            </div>

            <!-- Date Range -->
            <div>
                <label for="startDate" class="block font-montserrat font-semibold">Start Date</label>
                <input type="date" name="startDate" id="startDate" class="p-2 border rounded mt-2" value="{{ request('startDate') }}">
                <label for="endDate" class="block font-montserrat font-semibold mt-4">End Date</label>
                <input type="date" name="endDate" id="endDate" class="p-2 border rounded mt-2" value="{{ request('endDate') }}">
            </div>

            <!-- Visibility -->
            <div>
                <label for="visibility" class="block font-montserrat font-semibold">Visibility</label>
                <select name="visibility" id="visibility" class="p-2 border rounded mt-2">
                    <option value="">All</option>
                    <option value="public" {{ request('visibility') === 'public' ? 'selected' : '' }}>Public</option>
                    <option value="private" {{ request('visibility') === 'private' ? 'selected' : '' }}>Private</option>
                    <option value="request_only" {{ request('visibility') === 'request_only' ? 'selected' : '' }}>Request Only</option>
                    <option value="canceled" {{ request('visibility') === 'canceled' ? 'selected' : '' }}>Canceled</option>
                </select>
            </div>

            <!-- Location -->
            <div>
                <label for="location" class="block font-montserrat font-semibold">Location</label>
                <select name="location" id="location" class="p-2 border rounded mt-2">
                    <option value="">All Locations</option>
                    @foreach ($locations as $location)
                        <option value="{{ $location->id }}" {{ request('location') == $location->id ? 'selected' : '' }}>
                            {{ $location->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Sort by -->
            <h2 class="text-xl font-semibold font-montserrat text-ultraViolet mt-8">Sort by</h2>
            <div>
                <label for="order" class="block font-montserrat font-semibold">Date</label>
                <select name="order" id="order" class="p-2 border rounded mt-2">
                    <option value="asc" {{ request('order') === 'asc' ? 'selected' : '' }}>Ascending</option>
                    <option value="desc" {{ request('order') === 'desc' ? 'selected' : '' }}>Descending</option>
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
    const minInput = document.getElementById('minParticipants');
    const maxInput = document.getElementById('maxParticipants');
    const minLabel = document.getElementById('minParticipantsValue');
    const maxLabel = document.getElementById('maxParticipantsValue');

    const startDateInput = document.getElementById('startDate');
    const endDateInput = document.getElementById('endDate');

    // Update Slider Labels
    function updateLabels() {
        minLabel.textContent = minInput.value;
        maxLabel.textContent = maxInput.value;
    }

    minInput.addEventListener('input', () => {
        if (parseInt(minInput.value) > parseInt(maxInput.value)) {
            maxInput.value = minInput.value; // Sync max to min
        }
        updateLabels();
    });

    maxInput.addEventListener('input', () => {
        if (parseInt(maxInput.value) < parseInt(minInput.value)) {
            minInput.value = maxInput.value; // Sync min to max
        }
        updateLabels();
    });

    // Date Validation
    endDateInput.addEventListener('change', () => {
        if (new Date(endDateInput.value) < new Date(startDateInput.value)) {
            startDateInput.value = endDateInput.value; // Sync start to end
        }
    });

    startDateInput.addEventListener('change', () => {
        if (new Date(startDateInput.value) > new Date(endDateInput.value)) {
            endDateInput.value = startDateInput.value; // Sync end to start
        }
    });

    // Initial Update
    updateLabels();
</script>

@endsection

