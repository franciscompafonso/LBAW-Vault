@extends('layouts.app')

@section('content')
<!-- Wrapper to ensure sections stack vertically -->
<div class="w-full">
    <!-- Banner Section -->
    <section class="bg-whiteSmoke py-16 shadow-md w-full">
        <div class="max-w-6xl mx-auto flex items-center flex-col lg:flex-row px-6">
            <!-- Text Section -->
            <div class="w-full lg:w-1/2 text-left mb-8 lg:mb-0">
                <h2 class="text-4xl font-montserrat font-semibold mb-4 text-eerieBlack">Empowering Communities, One Event at a Time.</h2>
                @if (!Auth::check())
                    <p class="text-2xl font-montserrat text-deepNavy mb-6">Join us now!</p>
                @else
                    <p class="text-2xl font-montserrat text-deepNavy mb-6">Welcome back, {{Auth::user()->name}}!</p>
                @endif
            </div>

            <!-- Image Section -->
            <div class="w-full lg:w-1/2 flex justify-center lg:justify-end">
                <img src="{{ asset('images/others/main-pic.png') }}" alt="Main Picture" class="bg-white w-64 h-64 rounded-full object-cover shadow-lg transition-transform transform hover:scale-105 duration-300">
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-8 px-4">
        <div class="flex flex-col space-y-6">
        @foreach ($categories as $category)
            <div class="group flex items-center space-x-4">
                <!-- Category Name with Link -->
                <h2 class="text-4xl font-montserrat font-semibold text-eerieBlack group-hover:font-bold transition-all inline-block">
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
            <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
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

        <div class="group flex items-center space-x-4">
            <h2 class="text-4xl font-montserrat font-semibold text-eerieBlack group-hover:font-bold transition-all inline-block">
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

        <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
            @forelse ($category->events->take(5) as $event)
                @include('partials.home_event', ['event' => $event])
            @empty
                <p class="text-eerieBlack col-span-full font-montserrat">No events available at the moment.</p>
            @endforelse
        </div>

    </section>
</div>
@endsection
