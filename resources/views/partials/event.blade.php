<a href="{{ route('events.show', $event->id) }}" class="mx-5 my-5 overflow-hidden transition-transform duration-300 bg-white rounded-lg shadow-md event hover:scale-105 relative group">
    <!-- Image with 4:3 aspect ratio -->
    <div class="relative w-full aspect-[16/9] overflow-hidden">
        <img src="https://picsum.photos/300?random={{ $event->id }}" 
             alt="{{ $event->name }}" 
             class="absolute inset-0 object-cover w-full h-full transition-all duration-300 group-hover:brightness-50">

        <!-- Description overlaid on image, aligned to the bottom left corner -->
        <div class="absolute bottom-0 left-0 p-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-black bg-opacity-30">
            <p class="text-white font-montserrat">{{ $event->description }}</p>
        </div>
    </div>

    <!-- Event title below the image, unbolded and bigger -->
    <div class="p-3">
        <h3 class="text-3xl font-montserrat text-ultraViolet">{{ $event->name }}</h3>
    </div>
</a>
