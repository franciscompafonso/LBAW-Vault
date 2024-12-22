@extends('layouts.app')

@section('Title', $event->name)
@section('content')
<script>
    window.eventId = '{{ $event->id }}';
    window.startEvent = '{{ $event->start_date }}';
    window.endEvent = '{{ $event->end_date }}';
</script>
<script src="{{ url('js/pages/event-page.js') }}" defer></script>
<div class="space-y-10"> 
   <!-- Main Content Wrapper -->
   <div class="flex flex-col gap-10 lg:flex-row">
        <!-- Event Banner -->
        <img src="{{ asset('images/banner/' . ($event->banner->path ?? App\Models\Event::DEFAULT_BANNER)) }}" 
            alt="Banner of event {{ $event->name }}" 
            class="w-full lg:w-1/2 aspect-[16/9] object-cover rounded-lg shadow-lg transform transition-transform duration-300">

        <!-- Event Details -->
        <div class="relative flex flex-col justify-between flex-1">
            <!-- Event Title -->
            <div class="flex items-center justify-between py-5">
                <h1 class="text-6xl font-bold text-ultraViolet font-montserrat">{{ $event->name }}</h1>
                <div class="flex items-center space-x-4">
                    <!-- Options Icon -->
                    <div class="relative">
                        <button 
                            id="toggleMenu"
                            class="w-12 h-12 border-0 rounded-full text-ultraViolet hover:bg-whiteSmoke hover:text-ultraViolet active:bg-transparent focus:outline-none"
                            onclick="toggleMenu(event)">
                            <i class="text-4xl fa-solid fa-ellipsis"></i>
                        </button>

                        <!-- Options dropdown -->
                        <div id="menu" class="absolute top-0 right-0 hidden bg-white rounded-lg shadow-lg w-[14em]">
                            <div class="space-y-2">
                                @php
                                    $eventName = urlencode($event->name); // Event name
                                    $description = urlencode($event->description); // Event description
                                    $location = urlencode($event->location); // Event location

                                    $googleStartDate = urlencode($event->start_date->format('Ymd\THis'));
                                    $googleEndDate = urlencode($event->end_date->format('Ymd\THis'));

                                    $outlookStartDate = urlencode($event->start_date->format('Y-m-d\TH:i:s'));
                                    $outlookEndDate = urlencode($event->end_date->format('Y-m-d\TH:i:s'));

                                    $googleCalendarUrl = "https://www.google.com/calendar/render?action=TEMPLATE&text={$eventName}&dates={$googleStartDate}/{$googleEndDate}&details={$description}&location={$location}";

                                    $outlookCalendarUrl = "https://outlook.live.com/calendar/0/deeplink/compose?subject={$eventName}&startdt={$outlookStartDate}&enddt={$outlookEndDate}&body={$description}&location={$location}";
                                @endphp

                                <a target="_blank" href="{{ $googleCalendarUrl }}" class="block px-6 py-4 text-xl font-montserrat hover:text-darkSlateGrey text-darkSlateGrey hover:bg-gray-100">Add to Google calendar</a>

                                <a target="_blank" href="{{ $outlookCalendarUrl }}" class="block px-6 py-4 text-xl font-montserrat hover:text-darkSlateGrey text-darkSlateGrey hover:bg-gray-100">Add to Outlook Calendar</a>
                                
                                @if ((auth()->guard('admin')->check() && $event->visibility !== 'canceled' && $event->start_date > now()) || (auth()->check() && auth()->user()->can('update', $event)))
                                    <a href="{{ route('events.edit', $event->id) }}" class="block px-6 py-4 text-xl font-montserrat hover:text-darkSlateGrey text-darkSlateGrey hover:bg-gray-100">Edit Event</a>
                                @endif

                                @if ((auth()->guard('admin')->check() && $event->visibility !== 'canceled' && $event->start_date > now())|| (auth()->check() && auth()->user()->can('cancel', $event)))
                                    <form action="{{ route('events.cancel', $event->id) }}" method="POST" class="block m-0">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="block w-full px-6 py-4 text-xl text-left text-red-500 border-0 font-montserrat hover:text-red-700 hover:bg-gray-100">
                                            Cancel Event
                                        </button>
                                    </form>
                                @endif

                                @if (auth()->guard('admin')->check() || (auth()->check() && auth()->user()->can('delete', $event)))
                                    <form action="{{ route('events.delete', $event->id) }}" method="POST" class="block m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="block w-full px-6 py-4 text-xl text-left text-red-500 border-0 font-montserrat hover:text-red-700 hover:bg-gray-100">Delete Event</button>
                                    </form>
                                @endif

                                @can('createPoll', $event)
                                    <button 
                                        class="block w-full px-6 py-4 text-xl text-left border-0 font-montserrat text-darkSlateGrey hover:text-darkSlateGrey hover:bg-gray-100"
                                        onclick="togglePollForm()">
                                        Create Poll
                                    </button>
                                @endcan

                                @can('survey', $event)
                                    <a href="{{ route('events.survey', $event->id) }}" class="block px-6 py-4 text-xl font-montserrat hover:text-darkSlateGrey text-darkSlateGrey hover:bg-gray-100">Take Survey</a>
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
                </div>
            </div>

            <!-- Event Description -->
            <p class="text-4xl w-[90%] font-montserrat leading-relaxed text-darkSlateGrey">{{ $event->description }}</p>

            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-4 mt-auto">
                <div id="actions" class="flex flex-wrap gap-4 mt-auto">
                    @include('partials.event-actions')
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
    <div class="flex flex-col gap-20 lg:flex-row">
        <!-- Comments Section -->
        <div class="flex-1">
            <div>
                @if($event->polls->count() > 0)
                    <h2 id="polls-heading" class="mb-6 text-4xl font-bold text-ultraViolet font-montserrat border-babyPowder">
                        {{ $event->polls->count() === 1 ? 'Poll' : 'Polls' }}
                    </h2>
                    @each('partials.poll', $event->polls, 'poll')
                @endif
            </div>
            
            <h2 id="comments-heading" class="mb-6 text-4xl font-bold text-ultraViolet font-montserrat border-babyPowder">
                Comments
            </h2>

            <!-- Comment Form -->
            <div id="comment-form" class="mt-4">
                @include('partials.create-comment')
            </div>

            <!-- Scrollable Comment List -->
            <div id="comment-list" 
                class="mt-6 space-y-6 max-h-[400px] sm:max-h-[600px] lg:max-h-[800px] overflow-y-scroll rounded-md p-4" 
                aria-labelledby="comments-heading">
                <ul class="space-y-4 list-none">
                    @each('partials.comment', $event->comments()->orderBy('date', 'desc')->get(), 'comment')
                </ul>
            </div>

        </div>

        <!-- Details and Participants Section -->
        <div class="flex flex-col gap-6 lg:w-1/4">
            <!-- Event Details -->
            <div class="mb-10 space-y-8">
                <h2 class="mb-6 text-4xl font-bold text-ultraViolet font-montserrat border-babyPowder">
                    Details
                </h2>
                <div id="countdown" class="flex items-center p-4 font-semibold text-white rounded-md shadow-md justify-evenly sm:text-5xl md:text-5xl lg:text-3xl xl:text-4xl bg-ultraViolet font-montserrat"></div>
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
                    <a href="{{ route('user.profile', ['username' => $event->creator->username]) }}">
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
            <div aria-labelledby="participants-heading" class="h-full mt-10">
                <!-- Header with Participant Counter -->
                <div class="flex items-center justify-between mb-6">
                    <h2 id="participants-heading" class="text-4xl font-bold text-ultraViolet font-montserrat border-babyPowder">
                        Participants
                    </h2>
                    <!-- Participant Counter -->
                    <span id="participant-count" class="text-3xl font-semibold text-darkSlateGrey font-montserrat">
                        {{ $event->participators()->count() }} / {{ $event->capacity }}
                    </span>
                </div>

                <!-- Invite Form -->
                    @include('partials.invite-form')

                <!-- Scrollable Participant List -->
                <ul id="participant-list" 
                    class="space-y-4 max-h-[20em] overflow-y-scroll rounded-md p-4" 
                    aria-label="List of event participants">
                    

                    @if ($event->participators()->count() > 0)
                        @foreach ($event->participators()->orderBy('id')->get() as $participator)
                            @include('partials.participator')
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
</div>

@include('partials.poll-form', ['event' => $event])

<script src="https://js.pusher.com/7.0/pusher.min.js" defer></script>

@endsection
