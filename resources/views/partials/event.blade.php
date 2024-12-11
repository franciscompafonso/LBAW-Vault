<div class="news-card relative m-4 overflow-hidden rounded-lg shadow-lg w-full aspect-[16/9] transition-transform duration-300 group">
    <!-- Background Image -->
    <img src="https://picsum.photos/300?random={{ $event->id }}" alt="{{ $event->name }}"
         class="absolute inset-0 object-cover w-full h-full transition-transform duration-700 transform group-hover:scale-110">

    <!-- Gradient Overlay for Default (Non-hovered state) -->
    <div class="absolute inset-0 bg-gradient-to-b from-transparent to-black/50 group-hover:to-black/70 transition-all duration-700 ease-in-out pointer-events-none"></div>
  
    <!-- Text Wrapper -->
    <div class="absolute bottom-0 left-0 right-0 p-4 text-white transition-all duration-700 group-hover:translate-y-[-10%] group-hover:transition-transform ease-in-out">
        <!-- Title -->
        <h2 class="text-[2.5rem] font-semibold transition-colors duration-700 group-hover:text-wisteria">
            {{ $event->name }}
        </h2>
        <!-- Date -->
        <div class="text-2xl">
            {{ $event->start_date->format('d/m/Y') . ' - ' . $event->end_date->format('d/m/Y') }}
        </div>
        
        <!-- Description (Hidden by default, appears with sliding effect on hover) -->
        <div class="text-3xl description max-h-0 overflow-hidden transform transition-all duration-700 group-hover:max-h-40">
            {{ $event->description }}
        </div>
    </div>
  
    <!-- Clickable Link -->
    <a href="{{ route('events.show', $event->id) }}" class="absolute inset-0 z-10"></a>
</div>
