@can('invite', $event)
    <form id="inviteForm" action="{{ route('events.invite', $event->id) }}" method="POST" class="flex flex-col space-y-4">
        @csrf

        <!-- Username Input -->
        <div class="w-full">
            <input 
                id="invite_user" 
                type="text" 
                name="username" 
                placeholder="Username" 
                value="{{ old('username') }}" 
                class="block w-full rounded-md shadow-sm font-montserrat border-darkSlateGrey focus:ring-ultraViolet focus:border-ultraViolet" 
                required 
            >
            @error('username')
                <span class="text-sm text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <!-- Message and Submit Button -->
        <div class="flex items-center space-x-4 w-full">
            <!-- Invite Description -->
            <div class="flex-1">
                <input 
                    id="invite_desc" 
                    type="text" 
                    name="description" 
                    placeholder="Invite message" 
                    value="{{ old('description') }}" 
                    class="block w-full rounded-md shadow-sm font-montserrat border-darkSlateGrey focus:ring-ultraViolet focus:border-ultraViolet" 
                >
            </div>

            <!-- Submit Button -->
            <button 
                type="submit" 
                class="text-4xl flex items-center justify-center font-bold transition-all duration-300 border-transparent focus:text-wisteria focus:border-transparent focus:bg-white hover:border-transparent text-ultraViolet hover:bg-white hover:text-wisteria"
            >
                <i class="fa-solid fa-paper-plane"></i>
            </button>
        </div>
    </form>
@endcan