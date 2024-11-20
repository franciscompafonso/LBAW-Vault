@extends('layouts.app')

@section('content')
<!-- Events by Category Section -->
<section class="py-16 px-6">
    <h1 class="text-4xl font-bold text-eerieBlack mb-6">{{ $category->name }}</h1> 

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($events as $event)
            <div class="bg-eerieBlack shadow-md rounded-lg p-4">
                <h2 class="text-2xl font-semibold text-whiteSmoke">{{ $event->name }}</h2> 
                <p class="text-whiteSmoke mt-2">{{ $event->description }}</p> 
                <p class="text-whiteSmoke text-sm mt-4">
                    Date: {{ $event->date ? \Carbon\Carbon::parse($event->date)->format('d/m/Y') : 'Not available' }}
                </p> 
                <a href="{{ url('events/' . $event->id) }}" class="text-keppel hover:text-white mt-4 block">View Details</a>
            </div>
        @empty
            <p class="text-eerieBlack col-span-full">No events available in this category.</p> 
        @endforelse
    </div>
</section>
@endsection
