<div class="grid grid-cols-5 gap-4 p-4 border-b-2 border-gray-200">
    <div class="flex font-montserrat justify-center items-center">
        <a href="{{ route('events.show', ['id' => $invite->event->id]) }}" class="text-ultraViolet hover:text-darkSlateGrey">
            {{ $invite->event->name }}
        </a>
    </div>
    <div class="flex font-montserrat justify-center items-center gap-4">
        <img 
            src="{{ asset('images/profile-pictures/' . ($invite->receiver->picture->path ?? App\Models\User::DEFAULT_PIC)) }}" 
            alt="{{ $invite->receiver->username ? $invite->receiver->username . ' profile picture' : 'Default profile picture' }}" 
            class="object-cover w-16 h-16 rounded-full"
        >
        <a href="{{ route('user.profile', ['username' => $invite->receiver->username]) }}" class="text-ultraViolet hover:text-darkSlateGrey">
            {{ $invite->receiver->name }}
        </a>
    </div>
    <div class="flex font-montserrat justify-center items-center">{{ $invite->description !== '' ? $invite->description : '-' }}</div>
    <div class="flex font-montserrat justify-center items-center">{{ $invite->date->format('F j, Y') }}</div>
    <div class="flex items-center justify-center">
        <form action="{{ route('invite.delete', ['id' => $invite->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this invite?');" class="m-0">
            @csrf
            @method('DELETE')
            <button type="submit"                 class="px-6 py-2 font-bold text-white transition-all duration-200 rounded-md font-montserrat bg-burgundy hover:bg-[#6B0010] hover:text-gray-100 border-[#800020] hover:border-[#6B0010]"
            >
                Cancel Invite
            </button>
        </form>
    </div>
</div>