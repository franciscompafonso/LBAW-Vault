@extends('layouts.app')

@if($category)
    @section('Title', $category->name)
@else
    @section('Title', 'All Categories')
@endif

@section('content')
<!-- Events by Category Section -->
<section class="px-6 py-16">
    <h1 class="mb-6 text-5xl font-bold text-ultraViolet font-montserrat">
        @if($category)
            {{ $category->name }}
        @else
            All Categories
        @endif
    </h1>

    <!-- Filters and Sorting -->
    <form method="GET" id="filtersForm" class="mb-8">
        <div class="flex flex-wrap gap-4">
            <!-- Status Filter -->
            <p class="text-eerieBlack font-montserrat col-span-full">Filter by status of the event:</p>
            <select name="status" class="p-2 border rounded" onchange="document.getElementById('filtersForm').submit()">
                <option value="">All Events</option>
                <option value="ended" {{ request('status') === 'ended' ? 'selected' : '' }}>Ended</option>
                <option value="ongoing" {{ request('status') === 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                <option value="future" {{ request('status') === 'future' ? 'selected' : '' }}>Future</option>
            </select>

            <!-- Order By -->
            <p class="text-eerieBlack font-montserrat col-span-full">Order by date of the event:</p>
            <select name="order" class="p-2 border rounded" onchange="document.getElementById('filtersForm').submit()">
                <option value="asc" {{ request('order') === 'asc' ? 'selected' : '' }}>Ascending</option>
                <option value="desc" {{ request('order') === 'desc' ? 'selected' : '' }}>Descending</option>
            </select>
        </div>
    </form>

    <!-- Events Grid -->
    <div class="grid gap-10 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
        @forelse ($events as $event)
            @include('partials.event', ['event' => $event])
        @empty
            <p class="text-eerieBlack font-montserrat col-span-full">No events available in this category.</p>
        @endforelse
    </div>
</section>
@endsection
