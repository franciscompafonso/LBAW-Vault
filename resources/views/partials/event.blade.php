<article class="event bg-white shadow-md rounded-lg overflow-hidden hover:scale-105 transition-transform duration-300">
    <img src="{{ $event->image_url ?? 'default-image.jpg' }}" alt="{{ $event->name }}" class="w-full h-40 object-cover">

    <div class="p-4">
        <h3 class="text-lg font-bold text-charcoal">{{ $event->name }}</h3>

        <p class="text-sm text-charcoal mt-2 line-clamp-2">{{ $event->description }}</p>

        <a href="{{ route('events.show', $event->id) }}" class="mt-4 inline-block text-keppel-400 hover:underline">
            View Details
        </a>
    </div>
</article>