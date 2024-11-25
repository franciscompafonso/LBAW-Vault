<article class="event bg-white shadow-md rounded-lg overflow-hidden hover:scale-105 transition-transform duration-300 w-full max-w-sm mx-auto">
    <img src="{{ asset('images/banner/' . $event->banner) }}" alt="{{ $event->name }}" class="w-full h-50 object-cover">

    <div class="p-3">
        <h3 class="text-2xl font-semibold font-montserrat text-ultraViolet">{{ $event->name }}</h3>
        <p class="text-darkSlateGrey font-montserrat mt-2">{{ $event->description }}</p>
        <p class="text-darkSlateGrey text-sm font-montserrat mt-4">
            Date: {{ $event->start_date ? \Carbon\Carbon::parse($event->date)->format('d/m/Y') : 'Not available' }}
        </p>
        <p class="text-darkSlateGrey text-sm font-montserrat mt-4">
            Start Time: {{ \Carbon\Carbon::parse($event->start_date)->format('H:i') }} 
        </p>

        <div class="flex flex-col items-center py-2 px-1">
            @if (Auth::guard('admin')->check())
            <a href="{{ route('admin.events.show', $event->id) }}"
               class="w-full text-cambridgeBlue text-2xl font-bold py-2 rounded-lg hover:text-darkSlateGrey text-center transition-all duration-200 font-montserrat">
                View Details
            </a>
            @else
            <a href="{{ route('events.show', $event->id) }}"
               class="w-full text-cambridgeBlue text-2xl font-bold py-2 rounded-lg hover:text-darkSlateGrey text-center transition-all duration-200 font-montserrat">
                View Details
            </a>
            @endif
        </div>       
    </div>
</article>
