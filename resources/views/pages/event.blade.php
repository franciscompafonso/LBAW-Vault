@extends('layouts.app')

@section('Title', $event->name)
@section('content')
    <h1 class="text-6xl font-bold text-ultraViolet font-montserrat mb-6">{{ $event->name }}</h1>
    <div class="flex flex-col gap-10 lg:flex-row">
        <!-- Image Section (Left) -->
        <img src="{{ asset('images/banner/'. ($event->banner->path ?? App\Models\Event::DEFAULT_BANNER)) }}" alt="{{ $event->name }}" 
            class="object-cover transition-transform duration-300 transform rounded-lg shadow-md hover:scale-105">

    <!-- Event Details Section (Right) -->
    <div class="flex-1 space-y-6">
        <p class="text-3xl leading-relaxed text-darkSlateGrey">{{ $event->description }}</p>
    </div>

<!-- Action Center -->
<div class="mb-6">
    <h2 class="pb-4 mb-6 text-3xl font-bold font-montserrat text-ultraViolet border-babyPowder">Actions</h2>
    <div class="flex flex-col space-y-4">
        <!-- Visible Buttons -->
        @if (Auth::guard('web')->check())
            @if ($event->participators->contains('id', Auth::id()))
                <!-- Joined Event -->
                <button disabled class="w-full px-6 py-2 font-bold text-gray-700 transition-all duration-200 bg-gray-400 rounded-md cursor-not-allowed font-montserrat">
                    Joined Event
                </button>
            @elseif ($event->end_date > now() && !$event->participators->contains('id', Auth::id()) && !Auth::user()->isblocked)
                @if ($event->visibility === 'public' && $event->participators()->count() < $event->capacity)
                    <!-- Join Event -->
                    <form id="joinEventForm" action="{{ route('events.join', $event->id) }}" method="POST" data-event-id="{{ $event->id }}">
                        @csrf
                        <button type="submit" class="w-full px-6 py-2 font-bold text-white transition-all duration-200 rounded-md font-montserrat bg-ultraViolet hover:bg-wisteria hover:text-ultraViolet border-ultraViolet">
                            Join Event
                        </button>
                    </form>
                @elseif ($event->visibility === 'request_only')
                    @if ($event->requests->where('join_request_by_id', Auth::id())->where('accepted', false)->isNotEmpty())
                        <!-- Request Sent -->
                        <button disabled class="w-full px-6 py-2 font-bold text-gray-700 transition-all duration-200 bg-gray-400 rounded-md cursor-not-allowed font-montserrat">
                            Request Sent
                        </button>
                    @else
                        <!-- Request to Join -->
                        <form action="{{ route('events.request', $event->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full px-6 py-2 font-bold text-white transition-all duration-200 rounded-md font-montserrat bg-ultraViolet hover:bg-wisteria hover:text-ultraViolet border-ultraViolet">
                                Request to Join
                            </button>
                        </form>
                    @endif
                @endif
            @endif
        @endif

        <!-- Dropdown Menu for Other Actions -->
        @if (Auth::guard(name: 'admin')->check() || $event->creator_id === Auth::id())
            <div class="relative">
                <button onclick="toggleDropdownMenu()" class="w-10 h-10 text-white transition-all duration-200 rounded-full bg-ultraViolet hover:bg-wisteria hover:text-ultraViolet">
                    <i class="fas fa-cog"></i>
                </button>
                <div id="dropdownMenu" class="absolute hidden bg-babyPowder border border-ultraViolet rounded-md shadow-md right-0 mt-2 w-48">
                    <ul class="flex flex-col space-y-2 p-4">
                        <li>
                            <a href="{{ route('events.edit', $event->id) }}" class="block px-4 py-2 text-ultraViolet hover:bg-wisteria hover:text-babyPowder rounded-md">
                                Edit Event
                            </a>
                        </li>
                        <li>
                            <form action="{{ route('events.delete', $event->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="block w-full text-left px-4 py-2 text-ultraViolet hover:bg-wisteria hover:text-babyPowder rounded-md">
                                    Delete Event
                                </button>
                            </form>
                        </li>
                        <li>
                            <button onclick="togglePollForm()" class="block w-full text-left px-4 py-2 text-ultraViolet hover:bg-wisteria hover:text-babyPowder rounded-md">
                                Create Poll
                            </button>
                        </li>
                        <li>
                            @if ($event->end_date < now())
                                <a href="{{ route('events.surveyStats', $event->id) }}" class="block px-4 py-2 text-ultraViolet hover:bg-wisteria hover:text-babyPowder rounded-md">
                                    Survey Results
                                </a>
                            @else
                                <span class="block px-4 py-2 text-gray-500 cursor-not-allowed rounded-md">
                                    Survey results not available
                                </span>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>
        @endif
    </div>
</div>

</div>

    <!-- Comments Section -->
    <div class="flex flex-col gap-10 mt-12 lg:flex-row">
        <div class="flex-1 p-6 rounded-lg shadow-lg bg-wisteria bg-opacity-15">
            <h2 class="pb-4 mb-6 text-3xl font-bold font-montserrat text-ultraViolet border-babyPowder">Comments</h2>
            <!-- Make Comment -->
            @if (Auth::id())
                <form action="{{ route('comments.store', ['event' => $event->id]) }}" method="POST" class="mt-6" enctype="multipart/form-data">
                    @csrf
                    <textarea 
                        name="content" 
                        rows="4" 
                        class="w-full p-4 border border-gray-300 rounded-lg resize-none focus:outline-none focus:ring-2 focus:ring-burgundy bg-white"
                        placeholder="Add your comment here..."
                        required></textarea>

                        <div class="flex items-start justify-between mt-4 gap-4">
                            <!-- Photo Upload for Participators -->
                            @if ($event->participators->contains('id', Auth::id()))
                                <div class="flex-grow">
                                    <input 
                                        type="file" 
                                        name="photos[]" 
                                        id="photos" 
                                        multiple 
                                        accept="image/*"
                                        class="w-full border rounded-lg" 
                                        onchange="validatePhotos()" />
                                    <small class="text-gray-500 block">You can upload up to 15 photos.</small>
                                </div>
                            @endif

                            <!-- Submit Button -->
                            <button 
                                type="submit" 
                                class="px-6 py-2 font-bold text-white transition-all duration-200 rounded-md font-montserrat bg-ultraViolet hover:bg-cambridgeBlue hover:text-darkSlateGrey border-ultraViolet hover:border-cambridgeBlue">
                                Comment
                            </button>
                        </div>

            </form>
        @endif
        <!-- Polls -->
        @each('partials.poll', $event->polls, 'poll')

        <!-- Comments -->
        <div class="space-y-6 text-babyPowder">
            @each('partials.comment', $event->comments()->orderBy('id')->get(), 'comment')
        </div>
    </div>

    <!-- Right Section -->
    <div class="w-full space-y-8 lg:w-1/4"> 
        <div class="p-6 rounded-lg shadow-lg bg-wisteria bg-opacity-15">
            <!-- Event details Section -->
            <h2 class="pb-4 text-3xl font-bold font-montserrat text-ultraViolet border-babyPowder">Details</h2>
            <div class="mt-6 space-y-4">
                @if (Auth::id() == $event->creator_id)
                    <p class="text-3xl text-darkSlateGrey font-montserrat">This is a {{ $event->visibility }} event.</p>
                @endif
                <p class="text-3xl text-darkSlateGrey font-montserrat">
                    <strong class="font-semibold">Start:</strong> {{ \Carbon\Carbon::parse($event->start_date)->format('d/m/Y') }} {{ \Carbon\Carbon::parse($event->start_date)->format('H:i') }}
                </p>
                <p class="text-3xl text-darkSlateGrey font-montserrat">
                    <strong class="font-semibold">End:</strong> {{ \Carbon\Carbon::parse($event->end_date)->format('d/m/Y') }} {{ \Carbon\Carbon::parse($event->end_date)->format('H:i') }}
                </p>
                <p class="text-3xl text-darkSlateGrey font-montserrat">
                    <strong class="font-semibold">Location:</strong> {{ $event->location->name ?? 'No location specified' }}
                </p>
            </div>
        </div>

        <div class="p-6 rounded-lg shadow-lg bg-wisteria bg-opacity-15">
            <!-- Participant Section -->
            <h2 class="pb-4 mb-6 text-3xl font-bold font-montserrat text-ultraViolet border-babyPowder">Participants</h2>
            <ul class="mt-4 space-y-4 text-babyPowder">
                @if ($event->participators->contains('id', Auth::id()) && Auth::guard('web')->check() )
                <div class="mt-4">
                    <form action="{{ route('events.invite', $event->id) }}" method="POST" class="space-y-2">
                        @csrf
                        <!-- Username Input -->
                        <div>
                            <input 
                                id="invite_user" 
                                type="text" 
                                name="username" 
                                placeholder="@username" 
                                class="block w-full rounded-md shadow-sm font-montserrat border-darkSlateGrey focus:ring-ultraViolet focus:border-ultraViolet" 
                                required 
                            >
                        </div>
                        
                        <!-- Invite Message and Check Icon -->
                        <div class="flex items-center space-x-2">
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
                        </div>
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

    // Select all the Join Event forms on the page
    document.querySelectorAll('form#joinEventForm').forEach(form => {
        form.addEventListener('submit', function (event) {
            event.preventDefault(); // Prevent the default form submission (page reload)

            const formData = new FormData(this); // Get the form data
            const eventId = this.getAttribute('data-event-id'); // Get event ID from the data attribute

            // Send AJAX request using fetch API
            fetch(this.action, {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json()) // Assuming the server responds with JSON
            .then(data => {
                // Check if the response contains a success message or notification
                if (data.success) {
                    // No notification, just log success message to console if needed
                    console.log('Success:', data.message);
                    
                    // Update the UI to reflect the changes (like updating the button text)
                    const button = this.querySelector('button');
                    button.textContent = "Joined!";
                    button.disabled = true; // Disable the button to prevent multiple clicks
                } else {
                    // No error notification, just log error to console
                    console.error('Error:', data.message);
                }
            })
            .catch(error => {
                // Log network or other errors to the console
                console.error('Something went wrong. Please try again later.', error);
            });
        });
    });


    // Function to show notifications
    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `px-4 py-3 rounded-lg shadow-lg mb-4 ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white`;
        notification.textContent = message;
        
        // Append notification to container
        const container = document.getElementById('notification-container');
        container.appendChild(notification);

        // Remove notification after 5 seconds
        setTimeout(() => notification.remove(), 5000);
    }

    function toggleDropdownMenu() {
        const dropdownMenu = document.getElementById('dropdownMenu');
        dropdownMenu.classList.toggle('hidden');
    }

</script>
@endsection
