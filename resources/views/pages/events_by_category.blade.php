@extends('layouts.app')

@section('content')
<!-- Events by Category Section -->
<section class="py-16 px-6">
    <h1 class="text-4xl font-bold text-ultraViolet mb-6">{{ $category->name }}</h1> 

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($events as $event)
            <div class=" event bg-white shadow-md rounded-lg overflow-hidden hover:scale-105 transition-transform duration-300 bg-babyPowder shadow-md rounded-lg p-4">
                <h2 class="text-2xl font-semibold text-ultraViolet">{{ $event->name }}</h2> 
                <p class="text-darkSlateGrey mt-2">{{ $event->description }}</p> 
                <p class="text-darkSlateGrey text-sm mt-4">
                    Date: {{ $event->date ? \Carbon\Carbon::parse($event->date)->format('d/m/Y') : 'Not available' }}
                </p>
                <div class="flex flex-col items-center space-y-6 py-8 px-4">
                    <a href="{{ url('events/' . $event->id) }}" 
                        class="w-full max-w-md text-cambridgeBlue text-2xl font-bold py-3 rounded-lg hover:text-darkSlateGrey text-center transition-all duration-200 font-montserrat">
                        View Details
                    </a>
                </div>
            </div>
        @empty
            <p class="text-eerieBlack col-span-full">No events available in this category.</p> 
        @endforelse
    </div>
</section>
@endsection
