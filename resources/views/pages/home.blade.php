@extends('layouts.app')

@section('Title', 'Lend-A-Hand')
@section('content')
<!-- Wrapper to ensure sections stack vertically -->
<div class="w-full">
    <!-- Banner Section -->
    <section class="w-full py-16 shadow-md bg-whiteSmoke">
        <div class="flex flex-col items-center max-w-6xl px-6 mx-auto lg:flex-row">
            <!-- Text Section -->
            <div class="w-full mb-8 text-left lg:w-1/2 lg:mb-0">
                
                @if ( Auth::guard('admin')->check() )
                    <h2 class="mb-4 text-4xl font-semibold font-montserrat text-eerieBlack"> Shaping Communities, One Decision at a Time.</h2>
                    <p class="mb-6 text-2xl font-montserrat text-deepNavy">Welcome back, Admin!</p>
                @elseif (Auth::guard('web')->check())
                    <h2 class="mb-4 text-4xl font-semibold font-montserrat text-eerieBlack"> Empowering Communities, One Event at a Time.</h2>
                    <p class="mb-6 text-2xl font-montserrat text-deepNavy">Welcome back, {{Auth::user()->displayName}}!</p>
                @else
                    <h2 class="mb-4 text-4xl font-semibold font-montserrat text-eerieBlack"> Empowering Communities, One Event at a Time.</h2>
                    <p class="mb-6 text-2xl font-montserrat text-deepNavy">Join us now!</p>  
                @endif
            </div>

            <!-- Image Section -->
            <div class="flex justify-center w-full lg:w-1/2 lg:justify-end">
                <img src="{{ asset('images/others/main-pic.png') }}" alt="Main Picture" class="object-cover w-64 h-64 transition-transform duration-300 transform bg-white rounded-full shadow-lg hover:scale-105">
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="px-4 py-8">
        <div class="flex flex-col space-y-6">
        @foreach ($categories as $category)
            <div class="flex items-center space-x-4 group">
                <!-- Category Name with Link -->
                <h2 class="inline-block text-4xl font-semibold transition-all font-montserrat text-eerieBlack group-hover:font-bold">
                    <a href="{{ url('events/category=' . $category->id) }}" 
                    class="group-hover:text-eerieBlack group-hover:font-bold text-eerieBlack">
                        {{ $category->name }}
                    </a>
                </h2>

                <!-- "Explore all >" Link -->
                <a href="{{ url('events/category=' . $category->id) }}" 
                class="opacity-0 group-hover:opacity-100 translate-x-[-20px] group-hover:translate-x-0 my-auto text-cambridgeBlue text-xl font-bold hover:text-darkSlateGrey transition-all duration-300 ease-in-out font-montserrat transform">
                    Explore all >
                </a>
            </div>

            <!-- Events Grid -->
            <div class="grid gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
                @foreach ($category->events->take(5) as $event) <!-- Limite o número de eventos -->
                    @include('partials.home_event', ['event' => $event])
                @endforeach

                @if($category->events->isEmpty())
                    <p class="text-eerieBlack col-span-full font-montserrat">No events available in this category.</p>
                @endif
            </div>

            <!-- Separator between categories -->
            <hr class="my-6 border-t-2 border-lightGrey">
        @endforeach

        <div class="flex items-center space-x-4 group">
            <h2 class="inline-block text-4xl font-semibold transition-all font-montserrat text-eerieBlack group-hover:font-bold">
                <a href="{{ url('events/category=all') }}" 
                class="group-hover:text-eerieBlack group-hover:font-bold text-eerieBlack">
                    All Categories
                </a>
            </h2>

            <!-- "Explore all" Link -->
            <a href="{{ url('events/category=all') }}" 
            class="opacity-0 group-hover:opacity-100 translate-x-[-20px] group-hover:translate-x-0 my-auto text-cambridgeBlue text-xl font-bold hover:text-darkSlateGrey transition-all duration-300 ease-in-out font-montserrat transform">
                Explore all >
            </a>
        </div>

        <div class="grid gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
            @forelse ($category->events->take(5) as $event)
                @include('partials.home_event', ['event' => $event])
            @empty
                <p class="text-eerieBlack col-span-full font-montserrat">No events available at the moment.</p>
            @endforelse
        </div>

    </section>
</div>
@endsection
