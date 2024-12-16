@extends('layouts.app')

@section('Title', $event->name)
@section('content')
<div class="space-y-10">
    
   <!-- Main Content Wrapper -->
   <div class="flex flex-col gap-10 lg:flex-row">
        <!-- Event Banner -->
        <img src="{{ asset('images/banner/' . ($event->banner->path ?? App\Models\Event::DEFAULT_BANNER)) }}" 
            alt="Banner of event {{ $event->name }}" 
            class="w-full lg:w-1/2 aspect-[16/9] object-cover rounded-lg shadow-lg transform transition-transform duration-300">

        <!-- Event Details -->
        <div class="relative flex flex-col justify-between flex-1 px-4">
            <!-- Event Title -->
            <h1 class="py-5 text-6xl font-bold text-ultraViolet font-montserrat">{{ $event->name }}</h1>
            <!-- Event Description -->
            <p class="text-4xl w-[90%] font-montserrat leading-relaxed text-darkSlateGrey">{{ $event->description }}</p>

            <!-- Options Icon -->
            <div class="absolute top-0 right-0">
                @if (Auth::guard('admin')->check() || $event->creator_id === Auth::id() || $event->end_date < now())
                    <button 
                        class="w-12 h-12 border-0 rounded-full text-ultraViolet hover:bg-whiteSmoke hover:text-ultraViolet active:bg-transparent focus:outline-none"
                        onclick="toggleMenu(event)" id="icon">
                        <i class="text-4xl fa-solid fa-ellipsis"></i>
                    </button>
                @endif
                <!-- Options Menu -->
                <div id="menu" class="absolute top-0 right-0 hidden w-64 mt-2 bg-white rounded-lg shadow-lg">
                    <div class="space-y-2">
                        @if (Auth::guard(name: 'admin')->check() || $event->creator_id === Auth::id())
                            <a href="{{ route('events.edit', $event->id) }}" class="block px-6 py-4 text-xl hover:text-darkSlateGrey text-darkSlateGrey hover:bg-gray-100">Edit Event</a>
                            <form action="{{ route('events.delete', $event->id) }}" method="POST" class="block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="block w-full px-6 py-4 text-xl text-left border-0 hover:text-darkSlateGrey text-darkSlateGrey hover:bg-gray-100">Delete Event</button>
                            </form>
                        @endif
                        @if (Auth::id() === $event->creator_id)
                            <button 
                                class="block w-full px-6 py-4 text-xl text-left border-0 text-darkSlateGrey hover:text-darkSlateGrey hover:bg-gray-100"
                                onclick="togglePollForm()">
                                Create Poll
                            </button>
                            @if ($event->end_date < now())
                                <a href="{{ route('events.surveyStats', $event->id) }}" class="block px-6 py-4 text-xl hover:text-darkSlateGrey text-darkSlateGrey hover:bg-gray-100">View Survey Results</a>
                            @else
                                <button 
                                    disabled 
                                    title="Survey results are available after the event ends."
                                    class="block w-full px-6 py-4 text-xl text-left text-gray-500 cursor-not-allowed hover:text-darkSlateGrey">
                                    Survey Results Not Available
                                </button>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-4 mt-auto">
                @if (Auth::guard('web')->check())
                    @if ($event->participators->contains('id', Auth::id()))
                        <button disabled class="px-6 py-2 font-bold text-gray-700 bg-gray-400 rounded-md cursor-not-allowed font-montserrat">Joined Event</button>
                    @elseif ($event->visibility === 'public' && $event->participators()->count() < $event->capacity)
                        <form id="joinEventForm" action="{{ route('events.join', $event->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-6 py-2 font-bold text-white transition-all duration-200 rounded-md font-montserrat bg-ultraViolet hover:bg-wisteria hover:text-ultraViolet border-ultraViolet">Join Event</button>
                        </form>
                    @elseif ($event->visibility === 'request_only')
                        @if ($event->requests->where('join_request_by_id', Auth::id())->where('accepted', false)->isNotEmpty())
                            <button disabled class="px-6 py-2 font-bold text-gray-700 bg-gray-400 rounded-md cursor-not-allowed font-montserrat">Request Sent</button>
                        @else
                            <form action="{{ route('events.request', $event->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-6 py-2 font-bold text-white transition-all duration-200 rounded-md font-montserrat bg-ultraViolet hover:bg-wisteria hover:text-ultraViolet border-ultraViolet">Request to Join</button>
                            </form>
                        @endif
                    @endif
                    <div class="relative">
                        <!-- Share Button -->
                        <button id="share-button" onclick="togglePopup()" class="flex items-center justify-center px-4 py-2 font-bold transition-all duration-300 border-transparent focus:text-wisteria focus:border-transparent focus:bg-white hover:border-transparent text-ultraViolet hover:bg-white hover:text-wisteria">
                            <i class="mr-2 fa-solid fa-share"></i>
                        </button>
                    
                        <!-- Popup (initially hidden) -->
                        <div id="share-popup" class="absolute left-0 hidden w-[350px] p-4 mt-2 bg-white border border-gray-200 rounded-lg shadow-lg">
                            <!-- Popup Text -->
                            <p class="text-darkSlateGray text-md font-montserrat">Copy the text below to share the event:</p>
                            
                            <!-- Input Field for Link with Copy Button -->
                            <div class="flex items-center h-auto p-2 mt-2 bg-gray-100 rounded-lg">
                                <input id="share-link" type="text" value="" readonly class="w-full h-auto bg-transparent text-md font-montserrat focus:outline-none text-darkSlateGray"/>
                                <button onclick="copyToClipboard()" class="ml-2 font-bold border-transparent text-md font-montserrat hover:text-wisteria text-ultraViolet focus:border-transparent hover:border-transparent hover:bg-transparent">
                                    Copy
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>


    <!-- Comments and Details Section -->
    <div class="flex flex-col gap-10 lg:flex-row">
        <!-- Comments Section -->
        <div class="flex-1 p-6">
            <h2 class="mb-6 text-4xl font-bold text-ultraViolet font-montserrat border-babyPowder">
                Comments
            </h2>
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
                            class="px-6 py-2 font-bold text-white transition-all duration-200 rounded-md font-montserrat bg-ultraViolet hover:bg-cambridgeBlue hover:text-darkSlateGrey border-ultraViolet hover:border-cambridgeBlue">
                            Comment
                        </button>
                    </div>
                </form>
            @endif
            <div class="mt-6 space-y-6">
                @each('partials.comment', $event->comments()->orderBy('id')->get(), 'comment')
            </div>
        </div>

        <!-- Details and Participants Section -->
        <div class="flex flex-col gap-6 lg:w-1/5">
            <!-- Event Details -->
            <div class="p-6 space-y-8">
                <h2 class="mb-6 text-4xl font-bold text-ultraViolet font-montserrat border-babyPowder">
                    Details
                </h2>
                @if (Auth::id() == $event->creator_id)
                    <p class="text-3xl text-darkSlateGrey font-montserrat">
                        <strong class="font-semibold">Visibility:</strong> {{ $event->visibility }}
                    </p>
                @endif
                <p class="text-3xl text-darkSlateGrey font-montserrat">
                    <strong class="font-semibold">Start:</strong> {{ \Carbon\Carbon::parse($event->start_date)->format('d/m/Y H:i') }}
                </p>
                <p class="text-3xl text-darkSlateGrey font-montserrat">
                    <strong class="font-semibold">End:</strong> {{ \Carbon\Carbon::parse($event->end_date)->format('d/m/Y H:i') }}
                </p>
                <p class="text-3xl text-darkSlateGrey font-montserrat">
                    <strong class="font-semibold">Location:</strong> {{ $event->location->name ?? 'No location specified' }}
                </p>
            </div>

            <!-- Participants -->
            <div class="p-6">
                <h2 class="mb-6 text-4xl font-bold text-ultraViolet font-montserrat border-babyPowder">
                Participants
                </h2>
                <ul class="space-y-4">
                @foreach ($event->participators()->orderBy('id')->get() as $participator)
                    @include('partials.participator', [
                    'participator' => $participator,
                    'creatorId' => $event->creator_id
                    ])
                @endforeach
                </ul>
            </div>
        </div>
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

    document.addEventListener('click', (event) => {
        const menu = document.getElementById('menu');
        const button = document.querySelector('button[onclick="toggleMenu(event)"]');

        // Check if click happened outside of dropdown and button
        if (!menu.contains(event.target) && !button.contains(event.target)) {
            menu.classList.add('hidden');

            const icon = document.getElementById('icon');
            icon.classList.toggle('hidden');
        }
    });

    function toggleMenu(event) {
        event.stopPropagation(); // Prevent click from closing the menu
        const menu = document.getElementById('menu');
        menu.classList.toggle('hidden');

        const icon = document.getElementById('icon');
        icon.classList.toggle('hidden');
    }

    // Set the input value to the current page URL dynamically
    const shareInput = document.getElementById('share-link');
    shareInput.value = `Hello! Look at this event I just found! Link: ${window.location.href}`;

    // Toggles the popup visibility when the share button is clicked
    function togglePopup() {
        const popup = document.getElementById('share-popup');
        popup.classList.toggle('hidden');
    }

    document.addEventListener('click', (event) => {
        const sharePopup = document.getElementById('share-popup');
        const shareButton = document.getElementById('share-button');

        // Check if the click is outside the popup and the button
        if (!event.target.closest('#share-button') && !event.target.closest('#share-popup')) {
            sharePopup.classList.add('hidden'); // Hide the popup
        }
    });

    // Copies the text inside the input field to the clipboard
    function copyToClipboard() {
        const link = document.getElementById('share-link');
        link.select();
        link.setSelectionRange(0, 99999);
        document.execCommand('copy');
        alert('Link copied to clipboard!');
    }
</script>
@endsection
