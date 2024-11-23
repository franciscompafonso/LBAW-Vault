<li data-id="{{$participator->id}}">
        <div class="grid grid-cols-2 items-center gap-4">
                <div class="flex items-center space-x-2">
                        <img 
                        src="{{ asset('images/profile-pictures/' . $participator->profilepicture) }}" 
                        alt="{{ $participator->username }}" 
                        class="w-12 h-12 rounded-full object-cover"
                        >
                        <span class="font-semibold text-gray-900 text-lg">{{ $participator->name }}</span>
                </div>
        
                @if ($creatorId === Auth::id())
                        <div class="flex items-center justify-end">
                                <form action="{{ route('events.participators.kick', ['event' => $event->id, 'user' => $participator->id]) }}" class="m-0" method="POST">
                                        @csrf
                                        <a type="submit" class="text-red-200 hover:text-red-600 transition-colors duration-200">
                                                X
                                        </a>
                                </form>
                        </div>
                @endif
        </div>
</li>