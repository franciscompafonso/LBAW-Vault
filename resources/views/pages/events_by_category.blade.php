@extends('layouts.app')

@section('content')
<!-- Events by Category Section -->
<section class="py-16 px-6">
    <h1 class="text-4xl font-bold text-deepNavy mb-6">{{ $category->name }}</h1> <!-- Changed heading color to blue -->

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($events as $event)
            <div class="bg-white shadow-md rounded-lg p-4">
                <h2 class="text-2xl font-semibold text-skyBlue">{{ $event->name }}</h2> <!-- Changed event title color to sky blue -->
                <p class="text-deepNavy mt-2">{{ $event->description }}</p> <!-- Changed description text to a darker gray -->
                <p class="text-deepNavy text-sm mt-4">Date: {{ $event->date }}</p> <!-- Kept the date in gray -->
                <a href="{{ url('events/' . $event->id) }}" class="text-skyBlue hover:text-deepNavy mt-4 block">View Details</a> <!-- Changed hover color to green -->
            </div>
        @empty
            <p class="text-deepNavy col-span-full">No events available in this category.</p> <!-- Kept the fallback message in gray -->
        @endforelse
    </div>
</section>
@endsection
