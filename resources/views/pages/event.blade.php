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
                    <strong class="font-semibold">Start Date:</strong> {{ \Carbon\Carbon::parse($event->start_date)->format('d/m/Y') }}
                </p>
                <p class="text-darkSlateGrey text-lg font-montserrat">
                    <strong class="font-semibold">Start Time:</strong> {{ \Carbon\Carbon::parse($event->start_date)->format('H:i') }} 
                </p>
                <p class="text-lg text-darkSlateGrey font-montserrat">
                    <strong class="font-semibold">End Date:</strong> {{ \Carbon\Carbon::parse($event->end_date)->format('d/m/Y') }}
                </p>
                <p class="text-lg text-darkSlateGrey font-montserrat">
                    <strong class="font-semibold">End Time:</strong> {{ \Carbon\Carbon::parse($event->end_date)->format('H:i') }} 
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
            <div class="space-y-6 text-babyPowder">
                @each('partials.comment', $event->comments()->orderBy('id')->get(), 'comment')
            </div>
            @foreach($event->polls as $poll)
                <div class="poll bg-whiteSmoke p-6 rounded-lg shadow-lg mb-6">
                    <!-- Poll Question -->
                    <h3 class="text-xl font-bold text-ultraViolet font-montserrat mb-4">{{ $poll->name }}</h3>
                    <p class="text-lg text-darkSlateGrey font-montserrat mb-4">
                        <strong class="font-semibold">Poll Duration:</strong> 
                        {{ \Carbon\Carbon::parse($poll->start_time)->format('d/m/Y H:i') }} - 
                        {{ \Carbon\Carbon::parse($poll->end_time)->format('d/m/Y H:i') }}
                    </p>

                    @if(session('error'))
                        <div class="alert alert-danger text-red-500 mb-4 p-2 rounded-md">
                            {{ session('error') }}
                        </div>
                    @endif

                <!-- Poll Form -->
                <form action="{{ route('polls.vote', $poll->id) }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        @foreach(explode(',', $poll->options) as $index => $option)
                            <div class="poll-option flex items-center space-x-2">
                                <input 
                                    type="radio" 
                                    name="option" 
                                    value="{{ $index }}" 
                                    id="option_{{ $poll->id }}_{{ $index }}"
                                    @if(session('voted_option') === (string) $index) checked @endif
                                    @if(session('voted_option') !== null) disabled @endif
                                    class="w-5 h-5 text-ultraViolet border-darkSlateGrey focus:ring-ultraViolet"
                                >
                                <label for="option_{{ $poll->id }}_{{ $index }}" class="text-lg text-darkSlateGrey font-montserrat">
                                    {{ $option }}
                                </label>
                            </div>
                        @endforeach
                    </div>

                    @if(!session('voted_option')) 
                        <div class="mt-4">
                            <button 
                                type="submit" 
                                class="px-6 py-2 font-bold text-white bg-ultraViolet hover:bg-cambridgeBlue border-ultraViolet hover:border-cambridgeBlue rounded-md transition-all duration-200">
                                Vote
                            </button>
                        </div>
                    @endif
                </form>
        </div>
        @endforeach
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
                            <label for="photos" class="block text-gray-700">Upload up to 15 photos:</label>
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

            @if (Auth::id() === $event->creator_id)
                <div class="flex justify-end mt-4">
                    <button 
                        class="px-6 py-2 text-white bg-blue-500 rounded-lg hover:bg-blue-600"
                        onclick="togglePollForm()">
                        Create Poll
                    </button>
                </div>
            @endif
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
                                id="invite" 
                                type="text" 
                                name="username" 
                                placeholder="@username" 
                                class="block w-full rounded-md shadow-sm font-montserrat border-darkSlateGrey focus:ring-ultraViolet focus:border-ultraViolet" 
                                required 
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
