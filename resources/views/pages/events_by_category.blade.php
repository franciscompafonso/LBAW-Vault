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
        @include('partials.filters')

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

