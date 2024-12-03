<li data-id="{{ $participator->id }}">
    <div class="grid grid-cols-3 items-center gap-4">
        <div class="flex items-center space-x-2 col-span-2">
            <img 
                src="{{ asset('images/profile-pictures/' . $participator->profilepicture) }}" 
                alt="{{ $participator->username }}" 
                class="w-12 h-12 rounded-full object-cover"
            >
            @if(!$participator->isdeleted)
                <a href="{{ route('user.profile', ['id' => $participator->id]) }}" 
                class="text-lg font-semibold text-gray-900 font-montserrat">
                {{ $participator->displayName }}
                </a>
            @else
                <span class="text-lg font-semibold text-gray-500 font-montserrat">
                    {{ $participator->displayName }}
                </span>
            @endif

            <!-- Display "(Creator)" next to the name if the participator is the creator -->
            @if($participator->id === $creatorId)
                <span class="text-green-600 text-sm font-semibold">(Creator)</span>
            @endif
        </div>

        @if ($creatorId === Auth::id() || auth()->guard('admin')->check())
            <div class="flex items-center justify-end">
                <form action="{{ route('events.participators.kick', ['event' => $event->id, 'user' => $participator->id]) }}" class="m-0" method="POST">
                    @csrf
                    <button type="submit" class="text-red-200 font-montserrat hover:text-red-600 transition-colors duration-200 border-none hover:bg-transparent focus:outline-none">
                        X
                    </button>
                </form>
            </div>
        @endif
    </div>
</li>
