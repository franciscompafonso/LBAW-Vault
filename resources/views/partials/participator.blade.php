<li data-id="{{ $participator->id }}">
    <div class="grid items-center grid-cols-3 gap-4">
        <div class="flex items-center col-span-2 space-x-2">
            <img 
                src="{{ asset('images/profile-pictures/' . ($participator->profilepicture ?? App\Models\User::DEFAULT_PIC)) }}" 
                alt="{{ $participator->username }}" 
                class="object-cover w-12 h-12 rounded-full"
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
                <span class="text-sm font-semibold text-green-600">(Creator)</span>
            @endif
        </div>

        @if ($creatorId === Auth::id() || auth()->guard('admin')->check())
            <div class="flex items-center justify-end">
                <form action="{{ route('events.participators.kick', ['event' => $event->id, 'user' => $participator->id]) }}" class="m-0" method="POST">
                    @csrf
                    <button type="submit" class="text-red-200 transition-colors duration-200 border-none font-montserrat hover:text-red-600 hover:bg-transparent focus:outline-none">
                        X
                    </button>
                </form>
            </div>
        @endif
    </div>
</li>
