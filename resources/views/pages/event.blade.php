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
                <button 
                    id="toggleMenu"
                    class="w-12 h-12 border-0 rounded-full text-ultraViolet hover:bg-whiteSmoke hover:text-ultraViolet active:bg-transparent focus:outline-none"
                    onclick="toggleMenu(event)" id="icon">
                    <i class="text-4xl fa-solid fa-ellipsis"></i>
                </button>

                <!-- Options dropdown -->
                <div id="menu" class="absolute top-0 right-0 hidden bg-white rounded-lg shadow-lg w-[12em]">
                    <div class="space-y-2">
                        @php
                            $eventName = urlencode($event->name); // Event name
                            $startDate = urlencode($event->start_date->format('Ymd\THis')); // Start time in UTC
                            $endDate = urlencode($event->end_date->format('Ymd\THis')); // End time in UTC
                            $description = urlencode($event->description); // Event description
                            $location = urlencode($event->location); // Event location
                    
                            $googleCalendarUrl = "https://www.google.com/calendar/render?action=TEMPLATE&text={$eventName}&dates={$startDate}/{$endDate}&details={$description}&location={$location}";
                        @endphp
                    
                        <a target="_blank" href="{{ $googleCalendarUrl }}" class="block px-6 py-4 text-xl font-montserrat hover:text-darkSlateGrey text-darkSlateGrey hover:bg-gray-100">Add to Google calendar</a>
                    
                        @if (auth()->guard('admin')->check() || (auth()->check() && auth()->user()->can('update', $event)))
                            <a href="{{ route('events.edit', $event->id) }}" class="block px-6 py-4 text-xl font-montserrat hover:text-darkSlateGrey text-darkSlateGrey hover:bg-gray-100">Edit Event</a>
                        @endif
                    
                        @if (auth()->guard('admin')->check() || (auth()->check() && auth()->user()->can('delete', $event)))
                            <form action="{{ route('events.delete', $event->id) }}" method="POST" class="block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="block w-full px-6 py-4 text-xl text-left border-0 font-montserrat hover:text-darkSlateGrey text-darkSlateGrey hover:bg-gray-100">Delete Event</button>
                            </form>
                        @endif
                        
                        @can('createPoll', $event)
                            <button 
                                class="block w-full px-6 py-4 text-xl text-left border-0 font-montserrat text-darkSlateGrey hover:text-darkSlateGrey hover:bg-gray-100"
                                onclick="togglePollForm()">
                                Create Poll
                            </button>
                        @endcan
                    
                        @if (auth()->guard('admin')->check() || (auth()->check() && auth()->user()->can('viewSurveyResults', $event)))
                            @if($event->end_date < now())
                                <a href="{{ route('events.surveyStats', $event->id) }}" class="block px-6 py-4 text-xl font-montserrat hover:text-darkSlateGrey text-darkSlateGrey hover:bg-gray-100">View Survey Results</a>
                            @else
                                <button 
                                    title="Survey results are available after the event ends."
                                    class="block w-full px-6 py-4 text-xl text-left border-0 font-montserrat text-darkSlateGrey hover:text-darkSlateGrey hover:bg-gray-100">
                                    Survey Results Not Available
                                </button>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-4 mt-auto">
                <div id="actions" class="flex flex-wrap gap-4 mt-auto">
                    @can('leave', $event)
                        <button disabled class="px-6 py-2 font-bold text-gray-700 bg-gray-400 rounded-md cursor-not-allowed font-montserrat">Joined!</button>
                        <form id="leaveEventForm" action="{{ route('events.leave', $event->id) }}" method="POST" class="block">
                            @csrf
                            <button type="submit" class="px-6 py-2 font-bold text-white transition-all duration-200 rounded-md font-montserrat bg-ultraViolet hover:bg-wisteria hover:text-ultraViolet border-ultraViolet">Leave Event</button>
                        </form>
                    @endcan

                    @can('join', $event)
                        <form id="joinEventForm" action="{{ route('events.join', $event->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-6 py-2 font-bold text-white transition-all duration-200 rounded-md font-montserrat bg-ultraViolet hover:bg-wisteria hover:text-ultraViolet border-ultraViolet">Join Event</button>
                        </form>
                    @endcan

                    @can ('requestJoin', $event)
                        <form action="{{ route('events.request', $event->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-6 py-2 font-bold text-white transition-all duration-200 rounded-md font-montserrat bg-ultraViolet hover:bg-wisteria hover:text-ultraViolet border-ultraViolet">Request to Join</button>
                        </form>
                    @endcan
                </div>
                    
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
            <!-- Comment Form -->
            <div id="comment-form" class="mt-4">
                @include('partials.create-comment', [
                            'event' => $event
                        ])
            </div>
            <!-- Comment List -->
            <div class="mt-6 space-y-6" aria-labelledby="comments-heading">
                @each('partials.comment', $event->comments()->orderBy('id')->get(), 'comment')
            </div>
                <!-- make Poll -->
                @can('createPoll', $event)
                    <div class="flex justify-end mt-4">
                        <button 
                            class="px-6 py-2 font-bold text-white transition-all duration-200 rounded-md font-montserrat bg-ultraViolet hover:bg-cambridgeBlue hover:text-darkSlateGrey border-ultraViolet hover:border-cambridgeBlue""
                            onclick="togglePollForm()">
                            Create Poll
                        </button>
                    </div>
                @endcan

                @if (auth()->guard('admin')->check() || (auth()->check() && auth()->user()->can('viewSurveyResults', $event)))
                    <a href="{{ route('events.surveyStats', $event->id) }}" class="block mt-4 text-center">
                        <button class="px-6 py-2 font-bold text-white transition-all duration-200 rounded-md font-montserrat bg-burgundy hover:bg-cambridgeBlue hover:text-darkSlateGrey border-burgundy hover:border-cambridgeBlue">
                            View Survey Results
                        </button>
                    </a>
                @else
                    <p class="text-lg text-red-600">Survey results are available after the event ends.</p>
                @endif
                <!-- Polls -->
                @each('partials.poll', $event->polls, 'poll')
            </div>

        <!-- Details and Participants Section -->
        <div class="flex flex-col gap-6 lg:w-1/5">
            <!-- Event Details -->
            <div class="p-6 space-y-8">
                <h2 class="mb-6 text-4xl font-bold text-ultraViolet font-montserrat border-babyPowder">
                    Details
                </h2>
                @if (auth()->guard('admin')->check() || (auth()->check() && auth()->user()->can('update', $event)))
                    <p class="text-3xl text-darkSlateGrey font-montserrat">
                        <strong class="font-semibold">Visibility:</strong> 
                        @if ($event->visibility == 'public')
                            Public
                        @elseif ($event->visibility == 'private')
                            Private
                        @elseif ($event->visibility == 'request_only')
                            Request only
                        @elseif ($event->visibility == 'canceled')
                            Canceled
                        @else
                            {{ $event->visibility }}
                        @endif
                    </p>
                @endif
                <p class="text-3xl text-darkSlateGrey font-montserrat">
                    <strong class="font-semibold">Start:</strong> {{ \Carbon\Carbon::parse($event->start_date)->format('d/m/Y H:i') }}
                </p>
                <p class="text-3xl text-darkSlateGrey font-montserrat">
                    <strong class="font-semibold">End:</strong> {{ \Carbon\Carbon::parse($event->end_date)->format('d/m/Y H:i') }}
                </p>
                <p class="text-3xl text-darkSlateGrey font-montserrat">
                    <strong class="font-semibold">District:</strong> 
                    <a href="{{ route('events.search', ['district[]' => $event->district->id, 'source' => 'district']) }}">
                        {{ $event->district->name ?? 'No district specified' }}
                    </a>
                </p>
                <p class="text-3xl text-darkSlateGrey font-montserrat">
                    <strong class="font-semibold">Location:</strong> {{ $event->location ?? 'No location specified' }}
                </p>
                <p class="text-3xl text-darkSlateGrey font-montserrat">
                    <strong class="font-semibold">Creator:</strong>
                    <a href="{{ route('user.profile', ['id' => $event->creator->id]) }}">
                        {{ $event->creator->name ?? 'No category specified' }}
                    </a>
                </p>
                <p class="text-3xl text-darkSlateGrey font-montserrat">
                    <strong class="font-semibold">Category:</strong> 
                    <a href="{{ route('events.search', ['category[]' => $event->category->id, 'source' => 'category']) }}">
                        {{ $event->category->name ?? 'No category specified' }}
                    </a>
                </p>
            </div>

            <!-- Participants -->
            <div class="p-6" aria-labelledby="participants-heading">
                <!-- Header with Participant Counter -->
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-4xl font-bold text-ultraViolet font-montserrat border-babyPowder">
                        Participants
                    </h2>
                    <!-- Participant Counter -->
                    <span id="participant-count" class="text-3xl font-semibold text-darkSlateGrey font-montserrat">
                        {{ $event->participators()->count() }} / {{ $event->capacity }}
                    </span>
                </div>

                <!-- Participant List or Empty State -->
                <ul id="participant-list" class="space-y-4" aria-label="List of event participants">
                    <!-- Invite Form -->
                    @include('partials.invite-form', [
                        'event' => $event
                    ])
                    @if ($event->participators()->count() > 0)
                        @foreach ($event->participators()->orderBy('id')->get() as $participator)
                            @include('partials.participator', [
                                'participator' => $participator,
                                'creatorId' => $event->creator_id
                            ])
                        @endforeach
                    @else
                        <!-- No Participants Message -->
                        <p class="text-xl font-medium text-gray-500" aria-label="No participants message">
                            No participants yet.
                        </p>
                    @endif
                </ul>
            </div>
        </div>
    </div>


@include('partials.poll-form', ['event' => $event])

<script src="https://js.pusher.com/7.0/pusher.min.js" defer></script>
<script>
    function validatePhotos() {
        const photosInput = document.getElementById('photos');
        const fileCount = photosInput.files.length;

        if (fileCount > 15) {
            alert('You can only upload up to 15 photos.');
            photosInput.value = ''; // Clear the file input
        }
    }

    // Handle Join Event button click
    document.addEventListener('submit', function (event) {
        if (event.target.id === "joinEventForm") {
            event.preventDefault(); // Prevent form submission (page reload)

            const form = event.target;
            const formData = new FormData(form);

            // Make AJAX request to join the event
            fetch(form.action, {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                showPopup(data.success, data.message);

                if (data.success) {
                    const button = form.querySelector('button');
                    button.textContent = "Joined!";
                    button.disabled = true;
                }

                updateParticipantInfo();
                updateCommentForm();
                updateEventActions();
            })
            .catch(error => {
                showPopup(false, 'Something went wrong. Please try again later.');
                console.error(error);
            });
        }
    });

    // Handle Leave Event button click
    document.addEventListener('submit', function (event) {
        if (event.target.id === "leaveEventForm") {
            event.preventDefault();
            
            const form = event.target;
            const formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                showPopup(data.success, data.message);

                if (data.success) {
                    const button = form.querySelector('button');
                    button.textContent = "Join Event";
                    button.disabled = false;
                }

                updateParticipantInfo();
                updateCommentForm();
                updateEventActions();
            })
            .catch(error => {
                showPopup(false, 'Something went wrong. Please try again later.');
                console.error(error);
            });
        }
    });

    // Reload Participant Info (count or list)
    function updateParticipantInfo() {
        fetch(`/events/{{$event->id}}/participants`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('participant-count').innerText = `${data.count} / ${data.capacity}`;
                
                const participantList = document.getElementById('participant-list');
                participantList.innerHTML = data.participantHtml;
            })
            .catch(error => console.error('Error updating participants:', error));
    }

    // Reload Comment Form
    function updateCommentForm() {
        fetch(`/events/{{$event->id}}/update-comment-form`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error updating comment form.');
                }
                return response.json();
            })
            .then(data => {
                document.getElementById('comment-form').innerHTML = data.commentFormHtml;
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    // Reload Event Options
    function updateEventActions() {
        fetch(`/events/{{$event->id}}/event-actions`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error updating event actions.');
                }
                return response.json();
            })
            .then(data => {
                document.getElementById('actions').innerHTML = data.html;
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }
    
    document.addEventListener('click', (event) => {
        const menu = document.getElementById('menu');
        const button = document.getElementById('toggleMenu');
        const sharePopup = document.getElementById('share-popup');
        const shareButton = document.getElementById('share-button');

        // Check if the click is outside the popup and the button
        if (!event.target.closest('#share-button') && !event.target.closest('#share-popup')) {
            sharePopup.classList.add('hidden'); // Hide the popup
        }
        // Check if click happened outside of dropdown and button
        if (!menu.contains(event.target) && !button.contains(event.target)) {
            menu.classList.add('hidden');
        }
    });

    function toggleMenu(event) {
        event.stopPropagation(); // Prevent click from closing the menu
        const menu = document.getElementById('menu');
        menu.classList.toggle('hidden');
    }

    // Set the input value to the current page URL dynamically
    const shareInput = document.getElementById('share-link');
    shareInput.value = `Hello! Look at this event I just found! Link: ${window.location.href}`;

    // Toggles the popup visibility when the share button is clicked
    function togglePopup() {
        const popup = document.getElementById('share-popup');
        popup.classList.toggle('hidden');
    }

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
