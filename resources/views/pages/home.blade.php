@extends('layouts.app')

@section('content')
<!-- Banner Section -->
<section class="bg-white py-16 shadow-md">
    <div class="max-w-6xl mx-auto flex items-center flex-wrap lg:flex-nowrap px-6">
        <!-- Text Section -->
        <div class="w-full lg:w-1/2 text-left mb-8 lg:mb-0">
            <h2 class="text-4xl font-semibold mb-4 text-eerieBlack">Empowering Communities, One Event at a Time.</h2>
            <p class="text-2xl text-deepNavy mb-6">Join us now!</p>
        </div>

        <!-- Image Section -->
        <div class="w-full lg:w-1/2 flex justify-center lg:justify-end">
            <img src="{{ asset('images/others/main-pic.png') }}" alt="Main Picture" class="w-64 h-64 rounded-full object-cover shadow-lg transition-transform transform hover:scale-105 duration-300">
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="py-8 px-4">
    <div class="flex flex-col items-center space-y-6">
        @foreach ($categories as $category)
            <a 
                href="{{ url('events/category=' . $category->id) }}" 
                class="w-full max-w-md bg-eerieBlack text-white text-lg font-medium py-3 rounded-lg shadow-md hover:text-keppel text-center transition-all duration-200">
                {{ $category->name }}
            </a>
        @endforeach
    </div>
</section>
@endsection
