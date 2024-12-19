<li data-id="{{ $participator->id }}">
    <div class="flex items-center col-span-2 space-x-2 justify-between">
        <!-- Left-aligned Content -->
        <div class="flex items-center space-x-2">
            <img 
                src="{{ asset('images/profile-pictures/' . ($participator->picture->path ?? App\Models\User::DEFAULT_PIC)) }}" 
                alt="{{ $participator->username ? $participator->username . ' profile picture' : 'Default profile picture' }}" 
                class="object-cover w-16 h-16 rounded-full"
            >
            @if($participator->isdeleted)
                <span class="text-3xl text-darkSlateGrey font-montserrat">
                    {{ $participator->name }}
                </span>
            @else
                <a href="{{ route('user.profile', ['username' => $participator->username]) }}" 
                    class="text-3xl text-darkSlateGrey font-montserrat">
                    {{ $participator->name }}
                </a>
            @endif

            @if($participator->id === $event->creator_id)
                <span class="text-sm font-semibold text-green-600">(Creator)</span>
            @elseif($participator->id === Auth::id())
                <span class="text-sm font-semibold text-wisteria">(Me)</span>
            @endif
        </div>

        <!-- Right-aligned "X" Button -->
        @if (auth()->guard('admin')->check() || (auth()->check() && auth()->user()->can('kickParticipator', [$event, $participator])))
            <div class="flex justify-end">
                <form action="{{ route('events.participators.kick', ['event' => $event->id, 'user' => $participator->id]) }}" class="m-0" method="POST">
                    @csrf
                    <button type="submit" class="text-red-200 transition-colors duration-200 border-none font-montserrat hover:text-red-600 hover:bg-transparent focus:outline-none" aria-label="Remove {{ $participator->name }} from the event">
                        X
                    </button>
                </form>
            </div>
        @endif
    </div>
</li>
