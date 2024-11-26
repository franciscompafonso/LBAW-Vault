@extends('layouts.app')

@section('Title', $event->name)
@section('content')
<section id="event-detail" class="max-w-full p-8 mx-auto rounded-lg shadow-xl bg-babyPowder">   
    <div class="flex flex-col gap-10 lg:flex-row">
        <!-- Image Section (Left) -->
        <div class="flex-shrink-0 w-full lg:w-2/5">
            <img src="{{ asset('images/banner/' . $event->banner) }}" alt="{{ $event->name }}" class="object-cover w-full transition-transform duration-300 transform rounded-lg shadow-md h-80 hover:scale-105">
        </div>

        <!-- Event Details Section (Right) -->
        <div class="flex-1 space-y-6">
            <h1 class="text-4xl font-bold text-ultraViolet font-montserrat">{{ $event->name }}</h1>   
            <p class="text-lg leading-relaxed text-darkSlateGrey font-montserrat">{{ $event->description }}</p>

            <div class="mt-6 space-y-4">
                <p class="text-lg text-darkSlateGrey font-montserrat">
                    <strong class="font-semibold">Start:</strong> {{ \Carbon\Carbon::parse($event->start_date)->format('d/m/Y') }} {{ \Carbon\Carbon::parse($event->start_date)->format('H:i') }}
                </p>
                <p class="text-lg text-darkSlateGrey font-montserrat">
                    <strong class="font-semibold">End:</strong> {{ \Carbon\Carbon::parse($event->end_date)->format('d/m/Y') }} {{ \Carbon\Carbon::parse($event->end_date)->format('H:i') }}
                </p>
                <p class="text-lg text-darkSlateGrey font-montserrat">
                    <strong class="font-semibold">Location:</strong> {{ $event->location->name ?? 'No location specified' }}
                </p>
            </div>

            <!-- Event Actions for Regular Users -->
            @if (Auth::guard('web')->check())
                @if ($event->participators->contains('id', Auth::id()))
                    <form action="{{ route('events.leave', $event->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-6 py-2 font-bold text-white transition-all duration-200 rounded-md font-montserrat bg-ultraViolet hover:bg-cambridgeBlue hover:text-darkSlateGrey border-ultraViolet hover:border-cambridgeBlue">
                            Leave Event
                        </button>
                    </form>
                @else
                    <form action="{{ route('events.join', $event->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-6 py-2 font-bold text-white transition-all duration-200 rounded-md font-montserrat bg-ultraViolet hover:bg-cambridgeBlue hover:text-darkSlateGrey border-ultraViolet hover:border-cambridgeBlue">
                            Join Event
                        </button>
                    </form>
                @endif
            @endif

            <!-- Event Actions for Admins and Event Creators -->
            @if (Auth::guard('admin')->check() || $event->creator_id === Auth::id())
                <form action="{{ route('events.delete', $event->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-6 py-2 font-bold text-white transition-all duration-200 rounded-md font-montserrat bg-ultraViolet hover:bg-cambridgeBlue hover:text-darkSlateGrey border-ultraViolet hover:border-cambridgeBlue">
                        Delete Event
                    </button>
                </form>

                <a href="{{ route('events.edit', $event->id) }}" class="inline-block">
                    <button class="px-6 py-2 font-bold text-white transition-all duration-200 rounded-md font-montserrat bg-ultraViolet hover:bg-cambridgeBlue hover:text-darkSlateGrey border-ultraViolet hover:border-cambridgeBlue">
                        Edit Event
                    </button>
                </a>
            @endif
        </div>
    </div>

    <!-- Action & Comments Section -->
    <div class="flex flex-col gap-10 mt-12 lg:flex-row">
        <!-- Comments Section -->
        <div class="flex-1 p-6 rounded-lg shadow-lg bg-whiteSmoke">
            <h2 class="pb-4 mb-6 text-3xl font-bold border-b-2 font-montserrat text-burgundy border-babyPowder">Comments</h2>
            <!-- Make Comment -->
            @if (Auth::id())
                <form action="{{ route('comments.store', ['event' => $event->id]) }}" method="POST" class="mt-6" enctype="multipart/form-data">
                    @csrf
                    <textarea 
                        name="content" 
                        rows="4" 
                        class="w-full p-4 border border-gray-300 rounded-lg resize-none focus:outline-none focus:ring-2 focus:ring-burgundy"
                        placeholder="Add your comment here..."
                        required></textarea>

                    <!-- Photo Upload for Participators -->
                    @if ($event->participators->contains('id', Auth::id()))
                        <div class="mt-4">
                            <input 
                                type="file" 
                                name="photos[]" 
                                id="photos" 
                                multiple 
                                accept="image/*"
                                class="w-full p-2 mt-2 border border-gray-300 rounded-lg" 
                                onchange="validatePhotos()" />
                            <small class="text-gray-500">You can upload up to 15 photos.</small>
                        </div>
                    @endif

                    <div class="flex justify-end mt-4">
                        <button 
                            type="submit" 
                            class="px-6 py-2 text-white transition rounded-lg bg-burgundy hover:bg-opacity-90">
                            Submit Comment
                        </button>
                    </div>
                </form>
            @endif
            <!-- make Poll -->
            @if (Auth::id() === $event->creator_id)
                <div class="flex justify-end mt-4">
                    <button 
                        class="px-6 py-2 text-white bg-blue-500 rounded-lg hover:bg-blue-600"
                        onclick="togglePollForm()">
                        Create Poll
                    </button>
                </div>
            @endif
            <!-- Polls -->
            @each('partials.poll', $event->polls, 'poll')

            <!-- Comments -->
            <div class="space-y-6 text-babyPowder">
                @each('partials.comment', $event->comments()->orderBy('id')->get(), 'comment')
            </div>
        </div>

        <!-- Participants Section -->
        <div class="w-full p-6 rounded-lg shadow-lg lg:w-1/3 bg-whiteSmoke">   
            <h2 class="pb-4 mb-6 text-3xl font-bold border-b-2 font-montserrat text-keppel border-babyPowder">Participants</h2>
            <ul class="mt-4 space-y-4 text-babyPowder">
                @if ($event->participators->contains('id', Auth::id()) && Auth::guard('web')->check() )
                    <!-- Invite Form -->
                    <div class="mt-4">
                        <form action="{{ route('events.invite', $event->id) }}" method="POST" class="flex items-center space-x-2">
                            @csrf
                            <input 
                                id="invite_user" 
                                type="text" 
                                name="username" 
                                placeholder="@username" 
                                class="block w-full rounded-md shadow-sm font-montserrat border-darkSlateGrey focus:ring-ultraViolet focus:border-ultraViolet" 
                                required 
                            >
                            <input 
                                id="invite_desc" 
                                type="text" 
                                name="description" 
                                placeholder="Invite message" 
                                class="block w-full rounded-md shadow-sm font-montserrat border-darkSlateGrey focus:ring-ultraViolet focus:border-ultraViolet" 
                            >
                            <button 
                                type="submit" 
                                class="text-4xl transition-colors duration-200 bg-transparent border-none text-ultraViolet font-montserrat hover:text-cambridgeBlue hover:bg-transparent focus:outline-none">
                                <i class="fa-solid fa-check"></i>
                            </button>
                        </form>
                    </div>
                @endif
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

@include('partials.poll-form', ['event' => $event])

<script>
    function validatePhotos() {
        const photosInput = document.getElementById('photos');
        const fileCount = photosInput.files.length;

        if (fileCount > 15) {
            alert('You can only upload up to 15 photos.');
            photosInput.value = ''; // Clear the file input
        }
    }
</script>
@endsection
