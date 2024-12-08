<a href="{{ route('events.show', $event->id) }}" class="my-5 overflow-hidden transition-transform duration-300 bg-white shadow-md event hover:scale-105 relative group">
    <!-- Image with natural height -->
    <div class="relative w-full overflow-hidden rounded-lg">
        <img src="https://picsum.photos/300?random={{ $event->id }}" 
             alt="{{ $event->name }}" 
             class="aspect-[16/9] object-cover w-full h-auto transition-all duration-300 group-hover:brightness-50">

        <!-- Description overlaid on image -->
        <div class="absolute bottom-0 left-0 p-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            <p class="text-white text-3xl">{{ $event->description }}</p>
        </div>
    </div>

    <!-- Event title below the image -->
    <div class="p-3">
        <h3 class="text-4xl text-ultraViolet">{{ $event->name }}</h3>
    </div>
</a>
