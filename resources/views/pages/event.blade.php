@extends('layouts.app')

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
                    <strong class="font-semibold">Start Date:</strong> {{ \Carbon\Carbon::parse($event->start_date)->format('d/m/Y') }}
                </p>
                <p class="text-lg text-darkSlateGrey font-montserrat">
                    <strong class="font-semibold">Start Time:</strong> {{ \Carbon\Carbon::parse($event->start_date)->format('H:i') }} 
                </p>
                <p class="text-lg text-darkSlateGrey font-montserratfont-montserrat">
                    <strong class="font-semibold">Location:</strong> {{ $event->location->name ?? 'No location specified' }}
                </p>
            </div>

            <!-- cool for debugging! -->
            <!-- @if (session('message'))
                <div class="px-4 py-2 text-white bg-green-500 rounded-md shadow-md">
                    {{ session('message') }}
                </div>
            @endif -->
            

            @if ($event->participators->contains('id', Auth::id()))
                <form action="{{ route('events.leave', $event->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-6 py-2 font-bold text-white transition-all duration-200 rounded-md font-montserrat bg-ultraViolet hover:bg-cambridgeBlue hover:text-darkSlateGrey border-ultraViolet hover:border-cambridgeBlue">
                        Leave Event
                    </button>
                </form>
            @elseif (Auth::guard('web')->check())
                <form action="{{ route('events.join', $event->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-6 py-2 font-bold text-white transition-all duration-200 rounded-md font-montserrat bg-ultraViolet hover:bg-cambridgeBlue hover:text-darkSlateGrey border-ultraViolet hover:border-cambridgeBlue">
                        Join Event
                    </button>
                </form>
            @endif

            @if ($event->creator_id === Auth::id() )
                <form action="{{ route('events.delete', $event->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-6 py-2 font-bold text-white transition-all duration-200 rounded-md font-montserrat bg-ultraViolet hover:bg-cambridgeBlue hover:text-darkSlateGrey border-ultraViolet hover:border-cambridgeBlue">
                        Cancel Event
                    </button>
                </form>
                <button class="px-6 py-2 font-bold text-white transition-all duration-200 rounded-md font-montserrat bg-ultraViolet hover:bg-cambridgeBlue hover:text-darkSlateGrey border-ultraViolet hover:border-cambridgeBlue">   
                    <a href="{{ route('events.edit', $event->id) }}">
                        Edit Event
                    </a>
                </button>
                
            @endif
            
        </div>
    </div>

    <!-- Action & Comments Section -->
    <div class="flex flex-col gap-10 mt-12 lg:flex-row">
        <!-- Left: Comments Section (Comments) -->
        <div class="flex-1 p-6 rounded-lg shadow-lg bg-whiteSmoke">
            <h2 class="pb-4 mb-6 text-3xl font-bold border-b-2 font-montserrat text-burgundy border-babyPowder">Comments</h2>
            <div class="space-y-6 text-babyPowder">
                @each('partials.comment', $event->comments()->orderBy('id')->get(), 'comment')
            </div>
            @if (Auth::id())
                <form action="{{ route('comments.store', ['event' => $event->id]) }}" method="POST" class="mt-6" enctype="multipart/form-data">
                    @csrf
                    <textarea 
                        name="content" 
                        rows="4" 
                        class="w-full p-4 border border-gray-300 rounded-lg resize-none focus:outline-none focus:ring-2 focus:ring-burgundy"
                        placeholder="Add your comment here..."
                        required></textarea>

                    <!-- Photo upload section -->
                    @if ($event->participators->contains('id', Auth::id()))
                        <div class="mt-4">
                            <label for="photos" class="block text-gray-700">Upload up to 15 photos:</label>
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
        </div>
            <!-- Right: Participants List -->
            <div class="w-full p-6 rounded-lg shadow-lg lg:w-1/3 bg-whiteSmoke">   
                <h2 class="pb-4 mb-6 text-3xl font-bold border-b-2 font-montserrat text-keppel border-babyPowder">Participants</h2>
                <ul class="mt-4 space-y-4 text-babyPowder">
                @if ($event->participators->contains('id', Auth::id()))
                    <!-- Invite Form -->
                    <div class="mt-4">
                        <form action="{{ route('events.invite', $event->id) }}" method="POST" class="flex items-center space-x-2">
                            @csrf
                            <!-- Input Text Box -->
                            <input 
                                id="invite" 
                                type="text" 
                                name="username" 
                                placeholder="@username" 
                                class="block w-full rounded-md shadow-sm font-montserrat border-darkSlateGrey focus:ring-ultraViolet focus:border-ultraViolet" 
                                required 
                            >
                            <!-- Optional Submit Button -->
                            <button 
                                type="submit" 
                                class="text-4xl transition-colors duration-200 bg-transparent border-none text-ultraViolet font-montserrat hover:text-cambridgeBlue hover:bg-transparent focus:outline-none"
                            >
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
