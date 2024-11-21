<li data-id="{{$participator->id}}">
        <img 
                src="{{ asset('storage/' . $participator->profilepicture) }}" 
                alt="{{ $participator->username }}" 
                class="w-10 h-10 rounded-full object-cover"
        >
        <span class="text-gray-800 font-medium">{{ $participator->name }}</span>
</li>