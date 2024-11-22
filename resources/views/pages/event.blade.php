@extends('layouts.app')

@section('content')
<section id="event-detail" class="max-w-5xl mx-auto p-8 bg-babyPowder shadow-xl rounded-lg">   
    <div class="flex flex-col lg:flex-row gap-10">
        <!-- Image Section (Left) -->
        <div class="flex-shrink-0 w-full lg:w-2/5">
            <img src="{{ $event->image_url ?? 'default-image.jpg' }}" alt="{{ $event->name }}" class="w-full h-80 object-cover rounded-lg shadow-md transition-transform transform hover:scale-105 duration-300">
        </div>

        <!-- Event Details Section (Right) -->
        <div class="flex-1 space-y-6">
            <h1 class="text-4xl font-bold text-ultraViolet">{{ $event->name }}</h1>   
            <p class="text-darkSlateGrey leading-relaxed text-lg">{{ $event->description }}</p>

            <div class="mt-6 space-y-4">
                <p class="text-darkSlateGrey text-lg">
                    <strong class="font-semibold">Start Date:</strong> {{ \Carbon\Carbon::parse($event->start_date)->format('d/m/Y') }}
                </p>
                <p class="text-darkSlateGrey text-lg">
                    <strong class="font-semibold">Start Time:</strong> {{ \Carbon\Carbon::parse($event->start_date)->format('H:i') }} 
                </p>
                <p class="text-darkSlateGrey text-lg">
                    <strong class="font-semibold">Location:</strong> {{ $event->location->name ?? 'No location specified' }}
                </p>
            </div>

            <!-- cool for debugging!
                @if (session('message'))
                    <div class="bg-green-500 text-white px-4 py-2 rounded-md shadow-md">
                        {{ session('message') }}
                    </div>
                @endif
            -->

            @if ($event->participators->contains('id', Auth::id()))
                <form action="{{ route('events.leave', $event->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-ultraViolet hover:bg-cambridgeBlue text-white px-6 py-2 rounded-md hover:text-darkSlateGrey border-ultraViolet hover:border-cambridgeBlue font-montserrat font-bold transition-all duration-200">
                        Leave Event
                    </button>
                </form>
            @else
                <form action="{{ route('events.join', $event->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-ultraViolet hover:bg-cambridgeBlue text-white px-6 py-2 rounded-md hover:text-darkSlateGrey border-ultraViolet hover:border-cambridgeBlue font-montserrat font-bold transition-all duration-200">
                        Join Event
                    </button>
                </form>
            @endif

            @if ($event->creator_id === Auth::id())
                <form action="{{ route('events.delete', $event->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-ultraViolet hover:bg-cambridgeBlue text-white px-6 py-2 rounded-md hover:text-darkSlateGrey border-ultraViolet hover:border-cambridgeBlue font-montserrat font-bold transition-all duration-200">
                        Cancel Event
                    </button>
                </form>
                <button class="bg-ultraViolet hover:bg-cambridgeBlue text-white px-6 py-2 rounded-md hover:text-darkSlateGrey border-ultraViolet hover:border-cambridgeBlue font-montserrat font-bold transition-all duration-200">   
                    <a href="{{ route('events.edit', $event->id) }}">
                        Edit Event
                    </a>
                </button>
            @endif
        </div>
    </div>

    <!-- Action & Comments Section -->
    <div class="flex flex-col lg:flex-row mt-12 gap-10">
        <!-- Left: Reviews Section (Comments) -->
        <div class="flex-1 bg-whiteSmoke p-6 rounded-lg shadow-lg">   
            <h2 class="text-3xl font-bold text-burgundy mb-6 border-b-2 border-babyPowder pb-4">Reviews</h2>
            <div class="space-y-6 text-babyPowder">
                @each('partials.comment', $event->comments()->orderBy('id')->get(), 'comment')
            </div>
        </div>

        <!-- Right: Participants List -->
        <div class="w-full lg:w-1/3 bg-whiteSmoke p-6 rounded-lg shadow-lg">   
            <h2 class="text-3xl font-bold text-keppel mb-6 border-b-2 border-babyPowder pb-4">Participants</h2>
            <ul class="mt-4 text-babyPowder space-y-4">
            @foreach ($event->participators()->orderBy('id')->get() as $participator)
                @include('partials.participator', [
                    'participator' => $participator,
                    'creatorId' => $event->creator_id
                ])
            @endforeach
            </ul>
        </div>
    </div>
</section>
@endsection
