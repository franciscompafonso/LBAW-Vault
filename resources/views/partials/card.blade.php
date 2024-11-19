<article class="card bg-white shadow-xl rounded-lg overflow-hidden w-full max-w-lg mx-auto md:m-4 hover:scale-105 transition-transform duration-300">
    <!-- Card Image -->
    <img src="{{ $card->image_url ?? 'default-image.jpg' }}" alt="{{ $card->name }}" class="w-full h-60 object-cover">

    <!-- Card Content -->
    <div class="p-6">
        <!-- Event Title -->
        <h2 class="text-2xl font-bold text-gray-800">{{ $card->name }}</h2>

        <!-- Date and Time -->
        <p class="text-sm text-gray-500 mt-2">{{ $card->date }}</p>

        <!-- Event Description -->
        <p class="text-gray-700 mt-4 line-clamp-3">{{ $card->description }}</p>

        <!-- Location and Icons -->
        <div class="mt-6 flex items-center justify-between">
            <p class="text-sm font-semibold text-gray-600">{{ $card->location }}</p>
            <div class="flex items-center space-x-4">
                <button class="text-purple-600 hover:text-purple-800">
                    <i class="fa-regular fa-heart"></i>
                </button>
                <button class="text-purple-600 hover:text-purple-800">
                    <i class="fa-solid fa-share"></i>
                </button>
            </div>
        </div>

        <!-- Followers Section -->
        <div class="mt-4 text-gray-600 text-sm flex items-center">
            <i class="fa-solid fa-users mr-2"></i>
            {{ $card->followers ?? '0' }} seguidores
        </div>
    </div>
</article>
