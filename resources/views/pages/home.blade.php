@extends('layouts.app')

@section('Title', 'Lend-A-Hand')
@section('content')
<!-- Wrapper to ensure sections stack vertically -->
<div class="w-full">
    <!-- Banner Section -->
    <div class="w-full py-16 shadow-md bg-gradient-to-r from-whiteSmoke {{ Auth::guard('admin')->check() ? 'to-ultraViolet' : 'to-wisteria' }} bg-opacity-75 text-center text-white rounded-lg">
    <div class="flex flex-col items-center px-6 mx-auto lg:flex-row lg:justify-evenly lg:items-center">
        <!-- Text Section -->
        <div class="text-left flex flex-col justify-between h-full">
            <h2 id="quote" class="mb-8 text-6xl font-semibold font-montserrat text-eerieBlack">
                Empowering Communities, One Event at a Time.
            </h2>
            @if (Auth::guard('admin')->check())
                <p class="text-4xl font-montserrat text-eerieBlack">Welcome back, Admin!</p>
            @elseif (Auth::guard('web')->check())
                <p class="text-4xl font-montserrat text-eerieBlack">Welcome back, {{ Auth::user()->name }}!</p>
            @else
                <p class="text-4xl font-montserrat text-eerieBlack">Join us now!</p>
            @endif
        </div>

        <!-- Image Section -->
        <div class="flex justify-center lg:justify-center">
            <img src="{{ asset('images/others/main-pic.png') }}" alt="Main Picture" class="object-cover w-64 h-64 transition-transform duration-300 transform bg-white rounded-full shadow-lg hover:scale-105">
        </div>
    </div>
</div>

<script>
    // Quotes Array
    const quotes = [
    "Empowering Communities, One Event at a Time.",
    "Building Stronger Bonds, One Step at a Time.",
    "Transforming Lives,  One Kind Act at a Time.",
    "Inspiring Change, One Small Action at a Time.",
    "Spreading Warmth, One Smile Given at a Time.",
    "Uplifting Spirits, One Act of Care at a Time."

    ];


    const quoteElement = document.getElementById('quote');
    let currentIndex = 0;

    // Function to update the quote with a transition
    function updateQuote() {
        // Add fade-out class
        quoteElement.classList.add('fade-out');
        setTimeout(() => {
            // Change the quote text after fade-out
            currentIndex = (currentIndex + 1) % quotes.length;
            quoteElement.textContent = quotes[currentIndex];

            // Add fade-in class after changing text
            quoteElement.classList.remove('fade-out');
            quoteElement.classList.add('fade-in');
        }, 500); // Match fade-out duration

        // Remove fade-in class after animation completes
        setTimeout(() => {
            quoteElement.classList.remove('fade-in');
        }, 500); // Total animation duration
    }

    // Change quote every 5 seconds
    setInterval(updateQuote, 5000);
</script>

<style>
    /* Transition Styles */
    .fade-out {
        opacity: 0;
        transition: opacity 0.5s ease-out;
    }

    .fade-in {
        opacity: 1;
        transition: opacity 0.5s ease-in;
    }
</style>



   <!-- Categories Section -->
    <section class="py-8">
        <div class="flex flex-col space-y-6">
            @foreach ($categories as $category)
                @if ($category->events->count() >= 4)
                    <a href="{{ route('events.search', ['category_id' => $category->id]) }}" class="flex items-center space-x-4 group">
                        <!-- Category Name with Link -->
                        <h2 class="text-4xl font-semibold transition-transform duration-300 font-montserrat text-eerieBlack group-hover:scale-105">
                            {{ $category->name }}
                        </h2>

                        <!-- "Explore all >" Link -->
                        <p class="text-xl font-bold text-cambridgeBlue opacity-0 group-hover:opacity-100 translate-x-[-20px] group-hover:translate-x-0 transition-all duration-300 ease-in-out font-montserrat">
                            <i class="ml-2 text-3xl fa-solid fa-angle-right"></i>
                        </p>
                    </a>

                    <!-- Events Grid with max-width possible for 4 items -->
                    <div class="grid gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4 w-full">
                        @foreach ($category->events->take(4) as $event)
                                @include('partials.event', ['event' => $event])
                        @endforeach
                    </div>
                @endif
            @endforeach

            <!-- "All Categories" Section -->
            @if ($categories->pluck('events')->flatten()->count() >= 4)
                <div class="flex items-center space-x-4 group">
                    <h2 class="inline-block text-4xl font-semibold transition-all font-montserrat text-eerieBlack group-hover:font-bold">
                        <a href="{{ url('events/search') }}" 
                        class="group-hover:text-eerieBlack group-hover:font-bold text-eerieBlack">
                            All Categories
                        </a>
                    </h2>

                    <!-- "Explore all" Link -->
                    <a href="{{ url('events/search') }}" 
                    class="opacity-0 group-hover:opacity-100 translate-x-[-20px] group-hover:translate-x-0 my-auto text-cambridgeBlue text-xl font-bold hover:text-darkSlateGrey transition-all duration-300 ease-in-out font-montserrat transform">
                        Explore all >
                    </a>
                </div>

                <div class="grid gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4 w-full">
                    @foreach ($categories->pluck('events')->flatten()->take(4) as $event)
                            @include('partials.event', ['event' => $event])
                    @endforeach
                </div>
            @endif
        </div>
    </section>

</div>
@endsection
