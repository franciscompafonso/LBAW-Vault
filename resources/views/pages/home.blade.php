@extends('layouts.app')

@section('Title', 'Lend-A-Hand')
@section('content')
<!-- Wrapper to ensure sections stack vertically -->
<div class="w-full">
    <!-- Banner Section -->
    <section class="w-full py-16 shadow-md bg-whiteSmoke">
        <div class="flex flex-col items-center px-6 mx-auto lg:flex-row lg:justify-evenly lg:items-center">
            <!-- Text Section -->
            <div class="text-left flex flex-col justify-between h-full">
                @if (Auth::guard('admin')->check())
                    <h2 class="mb-8 text-6xl font-semibold font-montserrat text-eerieBlack">Shaping Communities,<br> One Decision at a Time.</h2>
                    <p class="text-4xl font-montserrat text-deepNavy">Welcome back, Admin!</p>
                @elseif (Auth::guard('web')->check())
                    <h2 class="mb-8 text-6xl font-semibold font-montserrat text-eerieBlack">Empowering Communities,<br> One Event at a Time.</h2>
                    <p class="text-4xl font-montserrat text-deepNavy">Welcome back, {{Auth::user()->name}}!</p>
                @else
                    <h2 class="mb-8 text-6xl font-semibold font-montserrat text-eerieBlack">Empowering Communities,<br> One Event at a Time.</h2>
                    <p class="text-4xl font-montserrat text-deepNavy">Join us now!</p>  
                @endif
            </div>


            <!-- Image Section -->
            <div class="flex justify-center lg:justify-center">
                <img src="{{ asset('images/others/main-pic.png') }}" alt="Main Picture" class="object-cover w-64 h-64 transition-transform duration-300 transform bg-white rounded-full shadow-lg hover:scale-105">
            </div>
        </div>
    </section>



   <!-- Categories Section -->
    <section class="px-4 py-8">
        <div class="flex flex-col space-y-6">
            @foreach ($categories as $category)
                @if ($category->events->count() >= 4)
                    <a href="{{ route('events.search', ['category_id' => $category->id]) }}" class="flex items-center space-x-4 group">
                        <!-- Category Name with Link -->
                        <h2 class="inline-block text-4xl font-semibold transition-all font-montserrat text-eerieBlack group-hover:font-bold">
                            {{ $category->name }}
                        </h2>

                        <!-- "Explore all >" Link -->
                        <p class="opacity-0 group-hover:opacity-100 translate-x-[-20px] group-hover:translate-x-0 my-auto text-cambridgeBlue text-xl font-bold hover:text-darkSlateGrey transition-all duration-300 ease-in-out font-montserrat transform">
                            Explore all
                        </p>
                    </a>

                    <!-- Events Grid with max-width possible for 4 items -->
                    <div class="grid gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4 w-full">
                        @foreach ($category->events->take(4) as $event)
                            <div class="w-full">
                                @include('partials.event', ['event' => $event])
                            </div>
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
                        <div class="w-full">
                            @include('partials.event', ['event' => $event])
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

</div>
@endsection
