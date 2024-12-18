<div class="news-card relative overflow-hidden rounded-lg shadow-lg w-full aspect-[16/9] transition-transform duration-300 group focus:outline-none focus:ring-4 focus:ring-ultraViolet focus:ring-opacity-50" tabindex="0">
    <!-- Background Image -->
    <img src="{{ asset('images/banner/'. ($event->banner->path ?? App\Models\Event::DEFAULT_BANNER)) }}" alt="{{ $event->name ? $event->name . ' banner image' : 'Default event banner image' }}"
         class="absolute inset-0 object-cover w-full h-full transition-transform duration-700 transform group-hover:scale-110">

    <!-- Gradient Overlay for Default (Non-hovered state) -->
    <div class="absolute inset-0 transition-all duration-700 ease-in-out pointer-events-none bg-gradient-to-b from-transparent to-black/50 group-hover:to-black/70"></div>
  
    <!-- Text Wrapper -->
    <div class="absolute bottom-0 left-0 right-0 p-4 text-white transition-all">
        <!-- Title -->
        <h2 class="text-[2.3rem] font-montserrat font-semibold transition-colors duration-700 group-hover:text-wisteria truncate overflow-hidden whitespace-nowrap">
            {{ $event->name }}
        </h2>
        <!-- Date -->
        <div class="text-xl font-montserrat">
            {{ $event->start_date->format('d/m/Y') . ' - ' . $event->end_date->format('d/m/Y') }}
        </div>
        
        <!-- Description (Hidden by default, appears with sliding effect on hover) -->
        <div class="overflow-hidden font-montserrat text-3xl transition-all duration-700 transform description max-h-0 group-hover:max-h-40 truncate overflow-hidden whitespace-nowrap">
            {{ $event->description }}
        </div>
    </div>
  
    <!-- Clickable Link (Focusable) -->
    <a href="{{ route('events.show', $event->id) }}" 
       class="absolute inset-0 z-10" 
       aria-label="View details for {{ $event->name }} event"></a>
</div>
