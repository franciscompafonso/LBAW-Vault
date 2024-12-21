<div class="grid grid-cols-4 gap-4 p-4 border-b-2 border-gray-200">
    <div class="flex font-montserrat justify-center items-center">
        <a href="{{ route('events.show', ['id' => $request->event->id]) }}" class="text-ultraViolet hover:text-darkSlateGrey">
            {{ $request->event->name }}
        </a>
    </div>
    <div class="flex font-montserrat justify-center items-center">
        {{ $request->date->format('F j, Y') }}
    </div>
    <div class="flex font-montserrat justify-center items-center">
        <span class="font-semibold text-yellow-500">Pending</span>
    </div>
    <div class="flex items-center justify-center">
        <form action="{{ route('requests.decline', ['requestId' => $request->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this request?');" class="m-0">
            @csrf
            @method('DELETE')
            <button type="submit"                 
                    class="px-6 py-2 font-bold text-white transition-all duration-200 rounded-md font-montserrat bg-burgundy hover:bg-[#6B0010] hover:text-gray-100 border-[#800020] hover:border-[#6B0010]"
            >
                Cancel request
            </button>
        </form>
    </div>
</div>