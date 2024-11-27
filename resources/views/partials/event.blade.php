<article class="mx-5 my-5 overflow-hidden transition-transform duration-300 bg-white rounded-lg shadow-md event hover:scale-105">
    <img src="{{ asset('images/banner/' . $event->banner) }}" alt="{{ $event->name }}" class="object-cover w-full h-40">

    <div class="p-3">
        <h3 class="text-2xl font-semibold font-montserrat text-ultraViolet">{{ $event->name }}</h3>
        <p class="mt-2 text-darkSlateGrey font-montserrat">{{ $event->description }}</p>
        <p class="mt-4 text-sm text-darkSlateGrey font-montserrat">
            Date: {{ $event->start_date ? \Carbon\Carbon::parse($event->date)->format('d/m/Y') : 'Not available' }}
        </p>
        <p class="mt-4 text-sm text-darkSlateGrey font-montserrat">
            Start Time: {{ \Carbon\Carbon::parse($event->start_date)->format('H:i') }} 
        </p>

        <div class="flex flex-col items-center px-1 py-2 mt-auto">
            <a href="{{ route('events.show', $event->id) }}"
                class="w-full max-w-md py-3 text-2xl font-bold text-center transition-all duration-200 rounded-lg text-cambridgeBlue hover:text-darkSlateGrey font-montserrat">
                View Details
            </a>
        </div>
        @if ($event->creator_id === Auth::id() || auth()->guard('admin')->check()) 
            <div class="flex flex-col items-center px-1 py-2 mt-auto">
                <a href="{{ route('events.edit', $event->id) }}"
                    class="w-full max-w-md py-3 text-2xl font-bold text-center transition-all duration-200 rounded-lg text-cambridgeBlue hover:text-darkSlateGrey font-montserrat">
                    Edit Event
                </a>
            </div>
        @endif
    </div>
</article>