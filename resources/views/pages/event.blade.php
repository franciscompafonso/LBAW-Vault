@extends('layouts.app')

@section('content')
<section id="event-detail" class="max-w-5xl mx-auto p-8 bg-eerieBlack shadow-xl rounded-lg mt-10">   
    <div class="flex flex-col lg:flex-row gap-10">
        <!-- Image Section (Left) -->
        <div class="flex-shrink-0 w-full lg:w-2/5">
            <img src="{{ $event->image_url ?? 'default-image.jpg' }}" alt="{{ $event->name }}" class="w-full h-80 object-cover rounded-lg shadow-md transition-transform transform hover:scale-105 duration-300">
        </div>

        <!-- Event Details Section (Right) -->
        <div class="flex-1 space-y-6">
            <h1 class="text-4xl font-bold text-white">{{ $event->name }}</h1>   
            <p class="text-white leading-relaxed text-lg">{{ $event->description }}</p>

            <div class="mt-6 space-y-4">
                <p class="text-white text-lg">
                    <strong class="font-semibold">Start Date:</strong> {{ \Carbon\Carbon::parse($event->start_date)->format('d/m/Y') }}
                </p>
                <p class="text-white text-lg">
                    <strong class="font-semibold">Start Time:</strong> {{ \Carbon\Carbon::parse($event->start_date)->format('H:i') }} 
                </p>
                <p class="text-white text-lg">
                    <strong class="font-semibold">Location:</strong> {{ $event->location->name ?? 'No location specified' }}
                </p>
            </div>

            <!-- Action Button -->
            <button class="bg-charcoal text-white px-6 py-3 rounded-md font-semibold shadow-lg hover:bg-keppel transition-colors duration-200">   
                Join Event
            </button>
        </div>
    </div>

    <!-- Action & Comments Section -->
    <div class="flex flex-col lg:flex-row mt-12 gap-10">
        <!-- Left: Reviews Section (Comments) -->
        <div class="flex-1 bg-whiteSmoke p-6 rounded-lg shadow-lg">   
            <h2 class="text-3xl font-bold text-burgundy mb-6 border-b-2 border-eerieBlack pb-4">Reviews</h2>
            <div class="space-y-6 text-eerieBlack">
                @each('partials.item', $event->comments()->orderBy('id')->get(), 'item')
            </div>
        </div>

        <!-- Right: Participants List -->
        <div class="w-full lg:w-1/3 bg-whiteSmoke p-6 rounded-lg shadow-lg">   
            <h2 class="text-3xl font-bold text-keppel mb-6 border-b-2 border-eerieBlack pb-4">Participants</h2>
            <ul class="mt-4 text-eerieBlack space-y-4">
                @each('partials.participator', $event->participators()->orderBy('id')->get(), 'item')
            </ul>
        </div>
    </div>
</section>
@endsection
