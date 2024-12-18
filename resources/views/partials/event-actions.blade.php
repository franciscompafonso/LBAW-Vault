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
    <form id="requestEventForm" action="{{ route('events.request', $event->id) }}" method="POST">
        @csrf
        <button type="submit" class="px-6 py-2 font-bold text-white transition-all duration-200 rounded-md font-montserrat bg-ultraViolet hover:bg-wisteria hover:text-ultraViolet border-ultraViolet">Request to Join</button>
    </form>
@endcan

@can('requestSent', $event)
    <button disabled class="px-6 py-2 font-bold text-gray-700 bg-gray-400 rounded-md cursor-not-allowed font-montserrat">Request Sent!</button>
@endcan