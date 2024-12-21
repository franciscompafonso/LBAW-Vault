@extends('layouts.app')

@section('Title', 'Lend-A-Hand')
@section('content')
<script src="{{ url('js/pages/home.js') }}" defer></script>
<!-- Wrapper to ensure sections stack vertically -->
<div class="w-full">
    <!-- Banner Section -->
    <div class="w-full py-16 shadow-md bg-gradient-to-r from-whiteSmoke {{ Auth::guard('admin')->check() ? 'to-ultraViolet' : 'to-wisteria' }} bg-opacity-75 text-center text-white rounded-lg">
        <div class="flex flex-col items-center px-6 mx-auto lg:flex-row lg:justify-between lg:items-center">
            <!-- Text Section -->
            <div class="flex flex-col justify-between h-full text-left lg:ml-[6em]">
                <h2 id="quote" class="mb-8 text-6xl font-semibold font-montserrat text-eerieBlack">
                    Empowering Communities, One Event at a Time.
                </h2>
                @auth('admin')
                    <p class="text-4xl font-montserrat text-eerieBlack" aria-label="Welcome back message for admin">Welcome back, Admin!</p>
                @elseauth('web')
                    <p class="text-4xl font-montserrat text-eerieBlack" aria-label="Welcome back message for user">Welcome back, {{ Auth::user()->name }}!</p>
                @else
                    <p class="text-4xl font-montserrat text-eerieBlack" aria-label="Invitation to join the platform">Join us now!</p>
                @endauth
            </div>


            <!-- Image Section -->
            <div class="flex justify-center lg:justify-center lg:mr-[6em]">
                <div class="relative w-32 h-32 sm:w-40 sm:h-40 md:w-48 md:h-48 lg:w-64 lg:h-64">
                    <img 
                        src="{{ asset('images/others/main-pic.png') }}" 
                        alt="Main Picture" 
                        class="object-cover w-full h-full transition-transform duration-300 transform bg-white rounded-full shadow-lg hover:scale-105"
                    >

                </div>

            </div>
            
        </div>
    </div>

   <!-- Categories Section -->
    <section class="py-8" aria-label="Categories section">
        <div class="flex flex-col space-y-6">
            @foreach ($categories as $category)
                @if ($category->events->count() >= 4)
                    <a href="{{ route('events.search', ['category[]' => $category->id, 'source' => 'category']) }}" 
                        class="flex items-center space-x-4 group" 
                        aria-label="Explore events in {{ $category->name }}">
                        <!-- Category Name with Link -->
                        <h2 class="py-6 text-4xl font-semibold transition-transform duration-300 font-montserrat text-eerieBlack group-hover:scale-105">
                            {{ $category->name }}
                        </h2>

                        <!-- "Explore all >" Link -->
                        <p class="text-xl font-bold text-cambridgeBlue opacity-0 group-hover:opacity-100 translate-x-[-20px] group-hover:translate-x-0 transition-all duration-300 ease-in-out font-montserrat">
                            <i class="ml-2 text-3xl fa-solid fa-angle-right mt-[5px]"></i>
                        </p>
                    </a>

                    <!-- Events Grid with max-width possible for 4 items -->
                    <div class="grid w-full gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4" aria-label="Events list for {{ $category->name }}">
                        @foreach ($category->events->take(4) as $event)
                                @include('partials.event', ['event' => $event])
                        @endforeach
                    </div>
                @endif
            @endforeach

            <!-- "All Categories" Section -->
            @if ($categories->pluck('events')->flatten()->count() >= 4)
                <a href="{{ route('events.search') }}" class="flex items-center space-x-4 group" aria-label="Explore all categories of events">
                    <!-- Category Name with Link -->
                    <h2 class="py-6 text-4xl font-semibold transition-transform duration-300 font-montserrat text-eerieBlack group-hover:scale-105">
                        All categories
                    </h2>

                    <!-- "Explore all >" Link -->
                    <p class="text-xl font-bold text-cambridgeBlue opacity-0 group-hover:opacity-100 translate-x-[-20px] group-hover:translate-x-0 transition-all duration-300 ease-in-out font-montserrat">
                        <i class="ml-2 text-3xl fa-solid fa-angle-right mt-[5px]"></i>
                    </p>
                </a>

                <div class="grid w-full gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4" aria-label="Events list for all categories">
                    @foreach ($categories->pluck('events')->flatten()->take(4) as $event)
                        @include('partials.event', ['event' => $event])
                    @endforeach
                </div>
            @endif
        </div>
    </section>
</div>

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
@endsection
