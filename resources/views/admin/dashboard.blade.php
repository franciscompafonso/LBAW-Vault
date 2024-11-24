@extends('layouts.app')

@section('title', 'Admin Dashboard')  <!-- Optionally set a custom title -->

@section('content')
<section class="bg-whiteSmoke py-16 shadow-md w-full">
        <div class="max-w-6xl mx-auto flex items-center flex-col lg:flex-row px-6">
            <!-- Text Section -->
            <div class="w-full lg:w-1/2 text-left mb-8 lg:mb-0">
                <h2 class="text-4xl font-montserrat font-semibold mb-4 text-eerieBlack">Shaping Communities, One Decision at a Time.</h2>
                @if (!Auth::check())
                    <p class="text-2xl font-montserrat text-deepNavy mb-6">Join us now!</p>
                @else
                    <p class="text-2xl font-montserrat text-deepNavy mb-6">Welcome back, Admin!</p>
                @endif
            </div>

            <!-- Image Section -->
            <div class="w-full lg:w-1/2 flex justify-center lg:justify-end">
                <img src="{{ asset('images/others/main-pic.png') }}" alt="Main Picture" class="bg-white w-64 h-64 rounded-full object-cover shadow-lg transition-transform transform hover:scale-105 duration-300">
            </div>
        </div>
    </section>
@endsection
