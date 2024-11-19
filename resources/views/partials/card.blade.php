<article class="card bg-white shadow-md rounded-lg overflow-hidden hover:scale-105 transition-transform duration-300">
    <img src="{{ $card->image_url ?? 'default-image.jpg' }}" alt="{{ $card->name }}" class="w-full h-40 object-cover">

    <div class="p-4">
        <h3 class="text-lg font-bold text-gray-800">{{ $card->name }}</h3>

        <p class="text-sm text-gray-600 mt-2 line-clamp-2">{{ $card->description }}</p>

        <a href="{{ route('cards.show', $card->id) }}" class="mt-4 inline-block text-blue-400 hover:underline">
            View Details
        </a>
    </div>
</article>