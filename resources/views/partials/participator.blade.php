<li data-id="{{$participator->id}}">
        <img 
                src="{{ asset('images/profile-pictures/' . $participator->profilepicture) }}" 
                alt="{{ $participator->username }}" 
                class="w-10 h-10 rounded-full object-cover"
        >
        <span class="text-gray-800 font-medium font-montserrat">{{ $participator->name }}</span>
        @if ($creatorId === Auth::id())
                <form action="{{ route('events.participators.kick', ['event' => $event->id, 'user' => $participator->id]) }}" method="POST">
                        @csrf
                        <button type="submit" class="font-montserrat bg-ultraViolet hover:bg-cambridgeBlue text-white px-6 py-2 rounded-md hover:text-darkSlateGrey border-ultraViolet hover:border-cambridgeBlue font-montserrat font-bold transition-all duration-200">
                                X
                        </button>
                </form>
        @endif
</li>