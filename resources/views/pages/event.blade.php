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
        <div class="flex flex-col px-4 justify-between flex-1 relative">
            <!-- Event Title -->
            <h1 class="text-6xl py-5 font-bold text-ultraViolet font-montserrat">{{ $event->name }}</h1>
            <!-- Event Description -->
            <p class="text-4xl w-[90%] leading-relaxed text-darkSlateGrey">{{ $event->description }}</p>

            <!-- Options Icon -->
            <div class="absolute top-0 right-0">
                @if (Auth::guard('admin')->check() || $event->creator_id === Auth::id() || $event->end_date < now())
                    <button 
                        class="w-12 h-12 rounded-full border-0 text-ultraViolet hover:bg-whiteSmoke hover:text-ultraViolet active:bg-transparent focus:outline-none"
                        onclick="toggleMenu(event)" id="icon">
                        <i class="text-4xl fa-solid fa-ellipsis"></i>
                    </button>
                @endif
                <!-- Options Menu -->
                <div id="menu" class="absolute top-0 right-0 hidden mt-2 bg-white rounded-lg shadow-lg w-64">
                    <div class="space-y-2">
                        @if (Auth::guard(name: 'admin')->check() || $event->creator_id === Auth::id())
                            <a href="{{ route('events.edit', $event->id) }}" class="block px-6 py-4 text-xl hover:text-darkSlateGrey text-darkSlateGrey hover:bg-gray-100">Edit Event</a>
                            <form action="{{ route('events.delete', $event->id) }}" method="POST" class="block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="block border-0 w-full px-6 py-4 text-left text-xl hover:text-darkSlateGrey text-darkSlateGrey hover:bg-gray-100">Delete Event</button>
                            </form>
                        @endif
                        @if (Auth::id() === $event->creator_id)
                            <button 
                                class="block w-full px-6 py-4 border-0 text-left text-xl text-darkSlateGrey hover:text-darkSlateGrey hover:bg-gray-100"
                                onclick="togglePollForm()">
                                Create Poll
                            </button>
                            @if ($event->end_date < now())
                                <a href="{{ route('events.surveyStats', $event->id) }}" class="block px-6 py-4 text-xl hover:text-darkSlateGrey text-darkSlateGrey hover:bg-gray-100">View Survey Results</a>
                            @else
                                <button 
                                    disabled 
                                    title="Survey results are available after the event ends."
                                    class="block w-full px-6 py-4 text-left hover:text-darkSlateGrey text-xl text-gray-500 cursor-not-allowed">
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
                @endif
            </div>
        </div>
    </div>


    <!-- Comments and Details Section -->
    <div class="flex flex-col gap-10 lg:flex-row">
        <!-- Comments Section -->
        <div class="flex-1 p-6">
            <h2 class="text-4xl font-bold mb-6 text-ultraViolet font-montserrat border-babyPowder">
                Comments
            </h2>
            @if (Auth::id())
                <form action="{{ route('comments.store', ['event' => $event->id]) }}" method="POST" class="space-y-4">
                @csrf
                <textarea 
                    name="content" 
                    rows="4" 
                    class="w-full p-4 border border-gray-300 rounded-lg resize-none focus:outline-none focus:ring-2 focus:ring-ultraViolet"
                    placeholder="Add your comment here..."
                    required></textarea>
                <button 
                    type="submit" 
                    class="px-6 py-2 font-bold text-white transition-all duration-200 rounded-md font-montserrat bg-ultraViolet hover:bg-cambridgeBlue hover:text-darkSlateGrey border-ultraViolet">
                    Comment
                </button>
                </form>
            @endif
            <div class="mt-6 space-y-6">
                @each('partials.comment', $event->comments()->orderBy('id')->get(), 'comment')
            </div>
        </div>

        <!-- Details and Participants Section -->
        <div class="flex flex-col gap-6 lg:w-1/5">
            <!-- Event Details -->
            <div class="p-6  space-y-8">
                <h2 class="text-4xl font-bold mb-6 text-ultraViolet font-montserrat border-babyPowder">
                    Details
                </h2>
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
                <h2 class="text-4xl font-bold mb-6 text-ultraViolet font-montserrat border-babyPowder">
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


</script>
@endsection
