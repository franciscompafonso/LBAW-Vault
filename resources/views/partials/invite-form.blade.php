@can('invite', $event)
        <form action="{{ route('events.invite', $event->id) }}" method="POST" class="flex items-center space-x-2">
            @csrf

            <div class="w-full">
                <input 
                    id="invite_user" 
                    type="text" 
                    name="username" 
                    placeholder="username" 
                    value="{{ old('username') }}" 
                    class="block w-full rounded-md shadow-sm font-montserrat border-darkSlateGrey focus:ring-ultraViolet focus:border-ultraViolet" 
                    required 
                >
                @error('username')
                    <span class="text-sm text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <div class="w-full">
                <input 
                    id="invite_desc" 
                    type="text" 
                    name="description" 
                    placeholder="Invite message" 
                    value="{{ old('description') }}" 
                    class="block w-full rounded-md shadow-sm font-montserrat border-darkSlateGrey focus:ring-ultraViolet focus:border-ultraViolet" 
                >
            </div>

            <button 
                type="submit" 
                class="text-4xl transition-colors duration-200 bg-transparent border-none text-ultraViolet font-montserrat hover:text-cambridgeBlue hover:bg-transparent focus:outline-none">
                <i class="fa-solid fa-check"></i>
            </button>
        </form>
@endcan