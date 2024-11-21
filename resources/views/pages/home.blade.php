@extends('layouts.app')

@section('content')
<!-- Banner Section -->
    <section class="bg-whiteSmoke py-16 shadow-md">
        <div class="max-w-6xl mx-auto flex items-center flex-wrap lg:flex-nowrap px-6">
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
    <div class="flex flex-col items-center space-y-6">
        @foreach ($categories as $category)
            <a href="{{ url('events/category=' . $category->id) }}" 
               class="w-full max-w-md bg-wisteria text-whiteSmoke text-xl font-bold py-3 rounded-lg shadow-md hover:text-darkSlateGrey text-center transition-all duration-200 font-montserrat">
                {{ $category->name }}
            </a>
        @endforeach
    </div>
</section>
@endsection
