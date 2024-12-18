@extends('layouts.app')

@section('Title', 'Recover Password')

@section('content')
<div class="flex items-center justify-center">
  <!-- Card Wrapper -->
  <div class="grid w-[70%] grid-cols-1 rounded-lg shadow-lg lg:grid-cols-2 bg-babyPowder overflow-hidden">
 
    <!-- Banner Section -->
    <section class="flex flex-col justify-center py-12 px-6">
      <div class="flex flex-col items-center max-w-md mx-auto lg:max-w-none lg:items-center"> <!-- Center everything and align items center on large screens -->

        <!-- Image Section -->
        <div class="flex justify-center mt-6">
          <img src="{{ asset('images/others/main-pic.png') }}" 
              alt="Main Picture" 
              class="object-cover w-64 h-64 transition-transform duration-300 transform bg-white rounded-full shadow-lg hover:scale-105">
        </div>

        <!-- Text Section with spacing between image and text -->
        <div class="mt-6 text-center"> <!-- Adding margin-top to separate image and text -->
          <h2 class="mb-4 text-4xl font-semibold font-montserrat text-eerieBlack">Empowering Communities, <br> One Event at a Time.</h2>
          <p class="text-2xl font-montserrat text-eerieBlack">Lets get you back on track!</p>
        </div>
        
      </div>
    </section>


    <!-- Recore password Section -->
    <section class="flex items-center justify-center">
      <div class="w-2/3">
        <!-- Title -->
        <h2 class="mb-6 text-7xl font-extrabold font-montserrat text-ultraViolet">Recover Password</h2>
        
        <!-- Form Container -->
          <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
            {{ csrf_field() }}

            <!-- Email Field -->
            <div>
              <label for="email" class="block text-2xl font-medium text-darkSlateGrey">Enter your email address to receive a password reset link.</label>
              <div class="mt-1">
                <input id="email" type="email" name="email" required autofocus autocomplete="email" 
                  class="block w-full rounded-md shadow-sm font-montserrat border-darkSlateGrey focus:ring-ultraViolet focus:border-ultraViolet"
                  aria-label="Email address">
              </div>
                @error('email')
                    <div role="alert" class="mt-2 text-sm text-red-600">{{ $message }}</div>
                @enderror
            </div>

            <!-- Rset Link Button -->
            <div class="mt-6 text-center">
                <button type="submit" 
                    class="w-full px-6 py-2 font-bold text-white transition-all duration-200 rounded-md bg-ultraViolet hover:bg-cambridgeBlue hover:text-darkSlateGrey border-ultraViolet hover:border-cambridgeBlue font-montserrat"
                    aria-label="Send password reset link">
                    Send Reset Link
                </button>
            </div>
          </form>

            @if(session('status'))
                <div role="alert" class="mt-4 text-sm text-center text-green-600 font-montserrat" aria-live="assertive">
                    {{ session('status') }}
                </div>
            @endif

            @if(session('error'))
                <div role="alert" class="mt-4 text-sm text-center text-red-600 font-montserrat" aria-live="assertive">
                    {{ session('error') }}
                </div>
            @endif
      </div>
    </section>
  </div>
</div>
@endsection
