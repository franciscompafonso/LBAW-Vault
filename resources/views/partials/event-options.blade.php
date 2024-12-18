<div class="space-y-2">
    @php
        $eventName = urlencode($event->name); // Event name
        $startDate = urlencode($event->start_date->format('Ymd\THis')); // Start time in UTC
        $endDate = urlencode($event->end_date->format('Ymd\THis')); // End time in UTC
        $description = urlencode($event->description); // Event description
        $location = urlencode($event->location); // Event location

        $googleCalendarUrl = "https://www.google.com/calendar/render?action=TEMPLATE&text={$eventName}&dates={$startDate}/{$endDate}&details={$description}&location={$location}";
    @endphp

    <a target="_blank" href="{{ $googleCalendarUrl }}" class="block px-6 py-4 text-xl hover:text-darkSlateGrey text-darkSlateGrey hover:bg-gray-100">Add to Google calendar</a>

    @if (auth()->guard('admin')->check() || (auth()->check() && auth()->user()->can('update', $event)))
        <a href="{{ route('events.edit', $event->id) }}" class="block px-6 py-4 text-xl hover:text-darkSlateGrey text-darkSlateGrey hover:bg-gray-100">Edit Event</a>
    @endif

    @if (auth()->guard('admin')->check() || (auth()->check() && auth()->user()->can('delete', $event)))
        <form action="{{ route('events.delete', $event->id) }}" method="POST" class="block">
            @csrf
            @method('DELETE')
            <button type="submit" class="block w-full px-6 py-4 text-xl text-left border-0 hover:text-darkSlateGrey text-darkSlateGrey hover:bg-gray-100">Delete Event</button>
        </form>
    @endif
    
    @can('createPoll', $event)
        <button 
            class="block w-full px-6 py-4 text-xl text-left border-0 text-darkSlateGrey hover:text-darkSlateGrey hover:bg-gray-100"
            onclick="togglePollForm()">
            Create Poll
        </button>
    @endcan

    @if (auth()->guard('admin')->check() || (auth()->check() && auth()->user()->can('viewSurveyResults', $event)))
        @if($event->end_date < now())
            <a href="{{ route('events.surveyStats', $event->id) }}" class="block px-6 py-4 text-xl hover:text-darkSlateGrey text-darkSlateGrey hover:bg-gray-100">View Survey Results</a>
        @else
            <button 
                title="Survey results are available after the event ends."
                class="block w-full px-6 py-4 text-xl text-left border-0 text-darkSlateGrey hover:text-darkSlateGrey hover:bg-gray-100">
                Survey Results Not Available
            </button>
        @endif
    @endif

    @can('leave', $event)
        <form action="{{ route('events.leave', $event->id) }}" method="POST" class="block">
            @csrf
            <button type="submit" class="block w-full px-6 py-4 text-xl text-left border-0 hover:text-darkSlateGrey text-darkSlateGrey hover:bg-gray-100">Leave Event</button>
        </form>
    @endcan
</div>