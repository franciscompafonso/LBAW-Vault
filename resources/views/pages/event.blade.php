@extends('layouts.app')

@section('content')
<section id="event-detail" class="max-w-full mx-auto p-8 bg-babyPowder shadow-xl rounded-lg">   
    <div class="flex flex-col lg:flex-row gap-10">
        <!-- Image Section (Left) -->
        <div class="flex-shrink-0 w-full lg:w-2/5">
            <img src="{{ asset('images/banner/' . $event->banner) }}" alt="{{ $event->name }}" class="w-full h-80 object-cover rounded-lg shadow-md transition-transform transform hover:scale-105 duration-300">
        </div>

        <!-- Event Details Section (Right) -->
        <div class="flex-1 space-y-6">
            <h1 class="text-4xl font-bold text-ultraViolet font-montserrat">{{ $event->name }}</h1>   
            <p class="text-darkSlateGrey leading-relaxed text-lg font-montserrat">{{ $event->description }}</p>

            <div class="mt-6 space-y-4">
                <!-- Show Creator Info for Admins -->
                @if(Auth::guard('admin')->check())
                    <p class="text-lg text-darkSlateGrey font-montserrat">
                        <strong class="font-semibold">Creator:</strong> {{ $event->creator_id ?? 'No creator specified' }}
                    </p>
                @endif
                <p class="text-darkSlateGrey text-lg font-montserrat">
                    <strong class="font-semibold">Start:</strong> {{ \Carbon\Carbon::parse($event->start_date)->format('d/m/Y') }} {{ \Carbon\Carbon::parse($event->start_date)->format('H:i') }}
                </p>
                <p class="text-lg text-darkSlateGrey font-montserrat">
                    <strong class="font-semibold">End:</strong> {{ \Carbon\Carbon::parse($event->end_date)->format('d/m/Y') }} {{ \Carbon\Carbon::parse($event->end_date)->format('H:i') }}
                </p>
                <p class="text-darkSlateGrey text-lg font-montserrat">
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
    <div class="flex flex-col lg:flex-row mt-12 gap-10">
        <!-- Comments Section -->
        <div class="flex-1 bg-whiteSmoke p-6 rounded-lg shadow-lg">
            <h2 class="font-montserrat text-3xl font-bold text-burgundy mb-6 border-b-2 border-babyPowder pb-4">Comments</h2>
            <!-- Make Comment -->
            @if (Auth::id())
                <form action="{{ route('comments.store', ['event' => $event->id]) }}" method="POST" class="mt-6" enctype="multipart/form-data">
                    @csrf
                    <textarea 
                        name="content" 
                        rows="4" 
                        class="w-full p-4 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-burgundy resize-none"
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
                                class="mt-2 w-full p-2 border border-gray-300 rounded-lg" 
                                onchange="validatePhotos()" />
                            <small class="text-gray-500">You can upload up to 15 photos.</small>
                        </div>
                    @endif

                    <div class="mt-4 flex justify-end">
                        <button 
                            type="submit" 
                            class="bg-burgundy text-white px-6 py-2 rounded-lg hover:bg-opacity-90 transition">
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
        <div class="w-full lg:w-1/3 bg-whiteSmoke p-6 rounded-lg shadow-lg">   
            <h2 class="font-montserrat text-3xl font-bold text-keppel mb-6 border-b-2 border-babyPowder pb-4">Participants</h2>
            <ul class="mt-4 text-babyPowder space-y-4">
                @if ($event->participators->contains('id', Auth::id()) && Auth::guard('web')->check())
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
