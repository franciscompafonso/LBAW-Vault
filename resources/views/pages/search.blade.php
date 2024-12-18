@extends('layouts.app')

@section('Title', 
    request('q') ? 'Search results for: ' . request('q') : 
    (request('category') ? implode(', ', $categories->whereIn('id', request('category'))->pluck('name')->toArray()) : 'Search')
)
@section('content')
<!-- Events by Category Section -->
<section class="w-full" role="region" aria-label="Events search and filters section">

    <h1 class="pb-[1em] lg:text-5xl text-4xl font-medium text-darkSlateGrey font-montserrat">
        @if(request('source') === 'bar')
            Search results for: <span class="lg:text-5xl text-4xl font-bold text-ultraViolet font-montserrat">{{ $searchQuery }}</span>
        @elseif(request('source') === 'category')
            Category: <span class="lg:text-5xl text-4xl font-bold text-ultraViolet font-montserrat">{{ $categories->whereIn('id', request('category'))->pluck('name')->first() }}</span>
        @elseif(request('source') === 'district')
            District: <span class="lg:text-5xl text-4xl font-bold text-ultraViolet font-montserrat">{{ $districts->whereIn('id', request('district'))->pluck('name')->first() }}</span>
        @else
            Search events
        @endif
    </h1>

    <!-- Main Grid Layout -->
    <div class="grid grid-cols-5 gap-6">
        
        <!-- Filters Section -->
        <form method="GET" id="filtersForm" class="flex flex-col gap-6">

            <p class="text-md font-montserrat font-semibold text-darkSlateGrey mb-4">
                Number of events: <span class="text-ultraViolet">{{ $eventsCount }}</span>
            </p>

            <!-- Search Context -->
            @if(request('q'))
                <!-- Hidden Input to Preserve Search Query -->
                <input type="hidden" name="q" value="{{ request('q') }}">
            @endif

            <!-- Sort by date -->
            <div class="flex items-center">
                <p class="text-md font-montserrat font-semibold text-darkSlateGrey">
                    Sort by date: 
                </p>
                <select name="order" id="order" aria-label="Sort events by date" class="pl-2 text-md font-montserrat font-semibold text-ultraViolet border-0 rounded w-[9em]">
                    <option value="date_asc"  {{ request('order') === 'date_asc'  ? 'selected' : '' }}>Ascending</option>
                    <option value="date_desc" {{ request('order') === 'date_desc' ? 'selected' : '' }}>Descending</option>
                </select>
            </div>

            <!-- Results per page -->
            <div class="flex items-center">
                <p class="text-md font-montserrat font-semibold text-darkSlateGrey">
                    Events per page: 
                </p>
                <select name="results" id="results" aria-label="Number of events per page" class="pl-2 text-md font-montserrat font-semibold text-ultraViolet border-0 rounded w-[4em]">
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
                    aria-label="Select start date for events"
                    min="" 
                >

                <label for="endDate" class="block font-montserrat font-semibold mt-4">End Date</label>
                <input 
                    type="date" 
                    name="endDate" 
                    id="endDate" 
                    class="p-2 border rounded mt-2" 
                    value="{{ request('endDate') }}"
                    aria-label="Select end date for events"
                    min="" 
                >
            </div>

            <!-- Visibility -->
            <div>
                <label class="block font-montserrat font-semibold cursor-pointer flex items-center justify-between" onclick="toggleSectionVisibility('visibility')">
                    <span>Visibility</span>
                    <i id="visibility-arrow" class="fa-solid fa-angle-right transform rotate-90 transition-transform duration-200"></i>
                </label>
                <div class="mt-2 hidden" id="visibility">
                    <label class="flex items-center space-x-2 mt-1">
                        <input type="checkbox" name="visibility[]" value="public" 
                            {{ request('visibility') && in_array('public', request('visibility')) ? 'checked' : '' }} />
                        <span>Public</span>
                    </label>
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="visibility[]" value="request_only" 
                            {{ request('visibility') && in_array('request_only', request('visibility')) ? 'checked' : '' }} />
                        <span>Request Only</span>
                    </label>
                </div>
            </div>

            <!-- Category -->
            <div class="{{ request('source') === 'category' ? 'hidden' : '' }}">
                <label class="block font-montserrat font-semibold cursor-pointer flex items-center justify-between" onclick="toggleSectionVisibility('category')">
                    <span>Category</span>
                    <i id="category-arrow" class="fa-solid fa-angle-right transform rotate-90 transition-transform duration-200"></i>
                </label>
                <div class="mt-2 hidden" id="category">
                    @foreach ($categories as $category)
                        <label class="flex items-center space-x-2 mt-1">
                            <input type="checkbox" name="category[]" value="{{ $category->id }}" 
                                {{ request('category') && in_array($category->id, request('category')) ? 'checked' : '' }} />
                            <span>{{ $category->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- District -->
            <div class="{{ request('source') === 'district' ? 'hidden' : '' }}">
                <label class="block font-montserrat font-semibold cursor-pointer flex items-center justify-between" onclick="toggleSectionVisibility('district')">
                    <span>District</span>
                    <i id="district-arrow" class="fa-solid fa-angle-right transform rotate-90 transition-transform duration-200"></i>
                </label>
                <div class="mt-2 hidden" id="district">
                    @foreach ($districts as $district)
                        <label class="flex items-center space-x-2 mt-1">
                            <input type="checkbox" name="district[]" value="{{ $district->id }}" 
                                {{ request('district') && in_array($district->id, request('district')) ? 'checked' : '' }} />
                            <span>{{ $district->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Status Section -->
            <div>
                <label class="block font-montserrat font-semibold cursor-pointer flex items-center justify-between" onclick="toggleSectionVisibility('status')">
                    <span>Status</span>
                    <i id="status-arrow" class="fa-solid fa-angle-right transform rotate-90 transition-transform duration-200"></i>
                </label>
                <div class="mt-2 hidden" id="status">
                    <label class="flex items-center space-x-2 mt-1">
                        <input type="checkbox" name="status_finished" value="1" 
                            {{ request('status_finished') ? 'checked' : '' }} />
                        <span>Finished</span>
                    </label>
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="status_upcoming" value="1" 
                            {{ request('status_upcoming') ? 'checked' : '' }} />
                        <span>Upcoming</span>
                    </label>
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="status_ongoing" value="1" 
                            {{ request('status_ongoing') ? 'checked' : '' }} />
                        <span>Ongoing</span>
                    </label>
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="status_full_capacity" value="1" 
                            {{ request('status_full_capacity') ? 'checked' : '' }} />
                        <span>At full capacity</span>
                    </label>
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="status_canceled" value="1" 
                            {{ request('status_canceled') ? 'checked' : '' }} />
                        <span>Not canceled</span>
                    </label>
                    @auth
                        <label class="flex items-center space-x-2 mt-1">
                            <input type="checkbox" name="status_joined" value="1" 
                                {{ request('status_joined') ? 'checked' : '' }} />
                            <span>Joined</span>
                        </label>
                    @endauth
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex gap-4 w-full">
                <!-- Preserve Source -->
                @if(request('source'))
                    <input type="hidden" name="source" value="{{ request('source') }}">
                @endif
                <!-- Submit Button -->
                <button type="submit" aria-label="Apply filters" 
                        class="flex-1 px-6 py-2 font-bold text-white transition-all duration-200 rounded-md font-montserrat bg-ultraViolet hover:bg-cambridgeBlue hover:text-darkSlateGrey border-ultraViolet hover:border-cambridgeBlue">
                    Apply
                </button>
                
                <!-- Reset Button -->
                <button type="button" aria-label="Reset filters"
                        onclick="
                            let params = new URLSearchParams();
                            
                            @if(request('source') === 'bar')
                                params.set('q', '{{ request('q') }}'); // Preserve search query
                                params.set('source', 'bar');
                            @elseif(request('source') === 'district')
                                @foreach(request('district', []) as $district)
                                    params.append('district[]', '{{ $district }}'); // Preserve districts
                                @endforeach
                                params.set('source', 'district');
                            @elseif(request('source') === 'category')
                                @foreach(request('category', []) as $category)
                                    params.append('category[]', '{{ $category }}'); // Preserve categories
                                @endforeach
                                params.set('source', 'category');
                            @endif

                            // Redirect with preserved parameters
                            window.location.href = '{{ url()->current() }}?' + params.toString();
                        "
                        class="flex-1 px-6 py-2 font-bold text-white transition-all duration-200 rounded-md font-montserrat bg-ultraViolet hover:bg-cambridgeBlue hover:text-darkSlateGrey border-ultraViolet hover:border-cambridgeBlue">
                    Reset
                </button>
            </div>
        </form>

        <!-- Events Grid -->
        <div class="col-span-4 grid gap-6 sm:grid-cols-2 md:grid-cols-3 auto-rows-min" aria-label="Events grid">
            @forelse ($events as $event)
                @include('partials.event', ['event' => $event])
            @empty
                <p class="text-eerieBlack font-montserrat col-span-full" aria-live="polite">No events available in this category.</p>
            @endforelse

            <!-- Pagination Links -->
            <div class="col-span-full mt-6 flex justify-center" aria-label="Pagination navigation">
                {{ $events->appends(request()->query())->links() }}
            </div>
        </div>

    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initial state: collapsed by default
        const collapsedSections = JSON.parse(localStorage.getItem('collapsedSections')) || {};

        const sections = ['visibility', 'category', 'district', 'status'];
        sections.forEach((sectionId) => {
            const section = document.getElementById(sectionId);
            const icon = document.getElementById(sectionId + '-arrow');
            
            if (!collapsedSections[sectionId] || collapsedSections[sectionId] === 'collapsed') {
                section.classList.add('hidden');
                icon.classList.add('transform', 'rotate-90');
                collapsedSections[sectionId] = 'collapsed'; // Ensure default is saved
            }
        });

        localStorage.setItem('collapsedSections', JSON.stringify(collapsedSections));

        // Toggle sections visibility and save state to localStorage
        window.toggleSectionVisibility = function (id) {
            const section = document.getElementById(id);
            const icon = document.getElementById(id + '-arrow');
            if (section.classList.contains('hidden')) {
                section.classList.remove('hidden');
                icon.classList.remove('rotate-90');
                collapsedSections[id] = 'expanded';
            } else {
                section.classList.add('hidden');
                icon.classList.add('rotate-90');
                collapsedSections[id] = 'collapsed';
            }
            localStorage.setItem('collapsedSections', JSON.stringify(collapsedSections));
        };

        // Set min and max for date inputs
        const startDate = document.getElementById('startDate');
        const endDate = document.getElementById('endDate');

        startDate.addEventListener('change', function () {
            endDate.setAttribute('min', startDate.value);
        });

        endDate.addEventListener('change', function () {
            startDate.setAttribute('max', endDate.value);
        });

        // Set initial min and max dates if values are already filled
        if (startDate.value) {
            endDate.setAttribute('min', startDate.value);
        }
        if (endDate.value) {
            startDate.setAttribute('max', endDate.value);
        }
    });
</script>

@endsection

