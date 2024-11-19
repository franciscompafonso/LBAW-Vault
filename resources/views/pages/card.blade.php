@extends('layouts.app')

@section('content')
<section id="event-detail" class="max-w-4xl mx-auto p-6 bg-white shadow-md rounded-lg">
    <div class="flex flex-col md:flex-row gap-6">
        <!-- Image Section (Left) -->
        <div class="flex-shrink-0 w-full md:w-1/3">
            <img src="{{ $card->image_url ?? 'default-image.jpg' }}" alt="{{ $card->name }}" class="w-full h-64 object-cover rounded-md">
        </div>

        <!-- Event Details Section (Right) -->
        <div class="flex-1">
            <h1 class="text-3xl font-bold text-gray-800">{{ $card->name }}</h1>
            <p class="text-gray-600 mt-4">{{ $card->description }}</p>

            <div class="mt-6 space-y-4">
                <p><strong>Date:</strong> {{ $card->start_date }}</p>
                <p><strong>Location:</strong> {{ $card->location }}</p>
            </div>
        </div>
    </div>

    <!-- Action & Comments Section -->
    <div class="flex flex-col md:flex-row mt-12 gap-6">
        <!-- Left: Join Event & Comments -->
        <div class="flex-1 space-y-6">
            <button class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
                Join Event
            </button>

            <!-- Reviews Section (Comments) -->
            <section id="comments" class="mt-12">
                <h2 class="text-2xl font-bold text-gray-800">Reviews</h2>
                <div class="space-y-4 mt-4">
                    @each('partials.item', $card->comments()->orderBy('id')->get(), 'item')
                </div>
            </section>
        </div>

        <!-- Right: Participants List -->
        <div class="w-full md:w-1/3 bg-gray-50 p-4 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold text-gray-800">Participants</h2>
            <ul class="mt-4 space-y-2">
                @each('partials.participator', $card->participators()->orderBy('id')->get(), 'item')
            </ul>
        </div>
    </div>
</section>
@endsection
