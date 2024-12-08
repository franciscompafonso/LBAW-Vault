@extends('layouts.app')

@section('Title', 'Forgot Password')

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
          <p class="text-2xl font-montserrat text-deepNavy">Lets get you back on track!</p>
        </div>
        
      </div>
    </section>


    <!-- Sign-In Section -->
    <section class="flex items-center justify-center">
      <div class="w-2/3">
        <!-- Title -->
        <h2 class="mb-6 text-7xl font-extrabold font-montserrat text-ultraViolet">Recover Password</h2>
        
        <!-- Form Container -->
          <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
            {{ csrf_field() }}

            <!-- Email Field -->
            <div>
              <label for="email" class="block text-3xl font-medium text-darkSlateGrey">Email address</label>
              <div class="mt-1">
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email" 
                  class="block w-full rounded-md shadow-sm font-montserrat border-darkSlateGrey focus:ring-ultraViolet focus:border-ultraViolet">
              </div>
              @if ($errors->has('email'))
                <p class="mt-1 text-lg text-red-500">{{ $errors->first('email') }}</p>
              @endif
            </div>

            <!-- Sign In Button -->
            <div class="mt-6 text-center">
                <button type="submit" 
                    class="w-full px-6 py-2 font-bold text-white transition-all duration-200 rounded-md bg-ultraViolet hover:bg-cambridgeBlue hover:text-darkSlateGrey border-ultraViolet hover:border-cambridgeBlue font-montserrat">
                    Send Reset Link
                </button>
            </div>
          </form>

            @if(session('status'))
                <p class="text-green-500 mt-4 text-center">{{ session('status') }}</p>
            @endif

            @if(session('error'))
                <p class="text-red-500 mt-4 text-center">{{ session('error') }}</p>
            @endif
      </div>
    </section>
  </div>
</div>
@endsection
