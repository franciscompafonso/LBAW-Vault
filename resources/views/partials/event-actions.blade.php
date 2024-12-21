@if ($event->visibility === 'canceled')

    <button 
        disabled
        title="Event is already canceled."
        class="block w-full px-6 py-4 text-2xl font-bold text-left bg-transparent border-0 font-montserrat text-darkSlateGrey hover:text-darkSlateGrey hover:bg-transparent">
        Event is Canceled
    </button>
    
@else

    @can('leave', $event)
        <button disabled class="px-6 py-2 font-bold text-gray-700 bg-gray-400 rounded-md cursor-not-allowed font-montserrat">Joined!</button>
        <form id="leaveEventForm" action="{{ route('events.leave', $event->id) }}" method="POST" class="block m-0">
            @csrf
            <button type="submit" class="px-6 py-2 font-bold text-white transition-all duration-200 rounded-md font-montserrat bg-ultraViolet hover:bg-wisteria hover:text-ultraViolet border-ultraViolet">Leave Event</button>
        </form>
    @endcan

    @can('join', $event)
        <form id="joinEventForm" action="{{ route('events.join', $event->id) }}" method="POST" class="block m-0">
            @csrf
            <button type="submit" class="px-6 py-2 font-bold text-white transition-all duration-200 rounded-md font-montserrat bg-ultraViolet hover:bg-wisteria hover:text-ultraViolet border-ultraViolet">Join Event</button>
        </form>
    @endcan

    @can ('requestJoin', $event)
        <form id="requestEventForm" action="{{ route('events.request', $event->id) }}" method="POST" class="block m-0">
            @csrf
            <button type="submit" class="px-6 py-2 font-bold text-white transition-all duration-200 rounded-md font-montserrat bg-ultraViolet hover:bg-wisteria hover:text-ultraViolet border-ultraViolet">Request to Join</button>
        </form>
    @endcan

    @can('requestSent', $event)
        <button disabled class="px-6 py-2 font-bold text-gray-700 bg-gray-400 rounded-md cursor-not-allowed font-montserrat">Request Sent!</button>
    @endcan

@endif