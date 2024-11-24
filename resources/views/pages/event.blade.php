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
                <p class="text-darkSlateGrey text-lg font-montserrat">
                    <strong class="font-semibold">Start Date:</strong> {{ \Carbon\Carbon::parse($event->start_date)->format('d/m/Y') }}
                </p>
                <p class="text-darkSlateGrey text-lg font-montserrat">
                    <strong class="font-semibold">Start Time:</strong> {{ \Carbon\Carbon::parse($event->start_date)->format('H:i') }} 
                </p>
                <p class="text-darkSlateGrey text-lg font-montserratfont-montserrat">
                    <strong class="font-semibold">Location:</strong> {{ $event->location->name ?? 'No location specified' }}
                </p>
            </div>

            <!-- cool for debugging! -->
            <!-- @if (session('message'))
                <div class="bg-green-500 text-white px-4 py-2 rounded-md shadow-md">
                    {{ session('message') }}
                </div>
            @endif -->
            

            @if ($event->participators->contains('id', Auth::id()))
                <form action="{{ route('events.leave', $event->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="font-montserrat bg-ultraViolet hover:bg-cambridgeBlue text-white px-6 py-2 rounded-md hover:text-darkSlateGrey border-ultraViolet hover:border-cambridgeBlue font-montserrat font-bold transition-all duration-200">
                        Leave Event
                    </button>
                </form>
            @elseif (Auth::guard('web')->check())
                <form action="{{ route('events.join', $event->id) }}" method="POST">
                    @csrf
                    <button type="submit" class=" font-montserrat bg-ultraViolet hover:bg-cambridgeBlue text-white px-6 py-2 rounded-md hover:text-darkSlateGrey border-ultraViolet hover:border-cambridgeBlue font-montserrat font-bold transition-all duration-200">
                        Join Event
                    </button>
                </form>
            @endif

            @if ($event->creator_id === Auth::id() )
                <form action="{{ route('events.delete', $event->id) }}" method="POST">
                    @csrf
                    <button type="submit" class=" font-montserrat bg-ultraViolet hover:bg-cambridgeBlue text-white px-6 py-2 rounded-md hover:text-darkSlateGrey border-ultraViolet hover:border-cambridgeBlue font-montserrat font-bold transition-all duration-200">
                        Cancel Event
                    </button>
                </form>
                <button class="font-montserrat bg-ultraViolet hover:bg-cambridgeBlue text-white px-6 py-2 rounded-md hover:text-darkSlateGrey border-ultraViolet hover:border-cambridgeBlue font-montserrat font-bold transition-all duration-200">   
                    <a href="{{ route('events.edit', $event->id) }}">
                        Edit Event
                    </a>
                </button>
                
            @endif
            
        </div>
    </div>

    <!-- Action & Comments Section -->
    <div class="flex flex-col lg:flex-row mt-12 gap-10">
        <!-- Left: Comments Section (Comments) -->
        <div class="flex-1 bg-whiteSmoke p-6 rounded-lg shadow-lg">
            <h2 class="font-montserrat text-3xl font-bold text-burgundy mb-6 border-b-2 border-babyPowder pb-4">Comments</h2>
            <div class="space-y-6 text-babyPowder">
                @each('partials.comment', $event->comments()->orderBy('id')->get(), 'comment')
            </div>
            @if ($event->participators->contains('id', Auth::id()))
                <form action="{{ route('comments.store', ['event' => $event->id]) }}" method="POST" class="mt-6" enctype="multipart/form-data">
                    @csrf
                    <textarea 
                        name="content" 
                        rows="4" 
                        class="w-full p-4 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-burgundy resize-none"
                        placeholder="Add your comment here..."
                        required></textarea>

                    <!-- Photo upload section -->
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

                    <div class="mt-4 flex justify-end">
                        <button 
                            type="submit" 
                            class="bg-burgundy text-white px-6 py-2 rounded-lg hover:bg-opacity-90 transition">
                            Submit Comment
                        </button>
                    </div>
                </form>
            @endif
        </div>
            <!-- Right: Participants List -->
            <div class="w-full lg:w-1/3 bg-whiteSmoke p-6 rounded-lg shadow-lg">   
                <h2 class="font-montserrat text-3xl font-bold text-keppel mb-6 border-b-2 border-babyPowder pb-4">Participants</h2>
                <ul class="mt-4 text-babyPowder space-y-4">
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
                                class="block w-full rounded-md font-montserrat border-darkSlateGrey shadow-sm focus:ring-ultraViolet focus:border-ultraViolet" 
                                required 
                            >
                            <!-- Optional Submit Button -->
                            <button 
                                type="submit" 
                                class="text-ultraViolet font-montserrat hover:text-cambridgeBlue text-4xl transition-colors duration-200 border-none hover:bg-transparent bg-transparent focus:outline-none"
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
