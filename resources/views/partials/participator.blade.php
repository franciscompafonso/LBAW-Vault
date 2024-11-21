<li data-id="{{$participator->id}}">
        <img 
                src="{{ asset('storage/' . $participator->profilepicture) }}" 
                alt="{{ $participator->username }}" 
                class="w-10 h-10 rounded-full object-cover"
        >
        <span class="text-gray-800 font-medium">{{ $participator->name }}</span>
        @if ($creatorId === Auth::id())
                <form action="{{ route('events.participators.kick', ['event' => $event->id, 'user' => $participator->id]) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-charcoal text-white px-6 py-3 rounded-md font-semibold shadow-lg hover:bg-keppel transition-colors duration-200">
                                X
                        </button>
                </form>
        @endif
</li>