@extends('layouts.app')

@section('Title', 'Sign up')
@section('content')
<div class="flex items-center justify-center">
  <!-- Card Wrapper -->
  <div class="grid w-[70%] grid-cols-1 rounded-lg shadow-lg lg:grid-cols-2 bg-babyPowder overflow-hidden">

    <!-- Banner Section -->
    <section class="flex flex-col justify-center py-12 px-6">
      <div class="flex flex-col items-center max-w-md mx-auto lg:max-w-none lg:items-center">
        <!-- Image Section -->
        <div class="flex justify-center mt-6">
          <img src="{{ asset('images/others/main-pic.png') }}" 
               alt="Main Picture" 
               class="object-cover w-64 h-64 transition-transform duration-300 transform bg-white rounded-full shadow-lg hover:scale-105">
        </div>

        <!-- Text Section with spacing between image and text -->
        <div class="mt-6 text-center">
          <h2 class="mb-4 text-4xl font-semibold font-montserrat text-eerieBlack">Empowering Communities, <br> One Event at a Time.</h2>
          <p class="text-2xl font-montserrat text-eerieBlack">Welcome!</p>
        </div>
        
      </div>
    </section>

    <!-- Sign-Up Section -->
    <section class="flex items-center justify-center px-8 py-12 bg-babyPowder">
      <div class="w-2/3">
        <!-- Title -->
        <h2 class="mb-6 text-7xl font-extrabold font-montserrat text-ultraViolet">Sign up</h2>
        
        <!-- Form Container -->
        <form method="POST" action="{{ route('register') }}" class="space-y-6">
          {{ csrf_field() }}

          <!-- Name Field -->
          <div>
            <label for="name" class="block text-3xl font-medium text-darkSlateGrey">Name</label>   
            <div class="mt-1">
              <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" class="block w-full rounded-md shadow-sm font-montserrat border-darkSlateGrey focus:ring-ultraViolet focus:border-ultraViolet">   
              @if ($errors->has('name'))
                <span class="mt-1 text-sm text-red-600">
                  {{ $errors->first('name') }}
                </span>
              @endif
            </div>
          </div>

          <!-- Username Field -->
          <div>
            <label for="username" class="block text-3xl font-medium text-darkSlateGrey">Username</label>   
            <div class="mt-1">
              <input id="username" type="text" name="username" value="{{ old('username') }}" required autocomplete="username" class="block w-full rounded-md shadow-sm font-montserrat border-darkSlateGrey focus:ring-ultraViolet focus:border-ultraViolet">   
              @if ($errors->has('username'))
                <span class="mt-1 text-sm text-red-600">
                  {{ $errors->first('username') }}
                </span>
              @endif
            </div>
          </div>

          <!-- Email Field -->
          <div>
            <label for="email" class="block text-3xl font-medium text-darkSlateGrey">E-Mail Address</label>   
            <div class="mt-1">
              <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" class="block w-full rounded-md shadow-sm font-montserrat border-darkSlateGrey focus:ring-ultraViolet focus:border-ultraViolet">   
              @if ($errors->has('email'))
                <span class="mt-1 text-sm text-red-600">
                  {{ $errors->first('email') }}
                </span>
              @endif
            </div>
          </div>

          <!-- Password Field -->
          <div>
            <label for="password" class="block text-3xl font-medium text-darkSlateGrey">Password</label>   
            <div class="mt-1">
              <input id="password" type="password" name="password" required autocomplete="new-password" class="block w-full rounded-md shadow-sm font-montserrat border-darkSlateGrey focus:ring-ultraViolet focus:border-ultraViolet">   
              @if ($errors->has('password'))
                <span class="mt-1 text-sm text-red-600">
                  {{ $errors->first('password') }}
                </span>
              @endif
            </div>
          </div>

          <!-- Confirm Password Field -->
          <div>
            <label for="password-confirm" class="block text-3xl font-medium text-darkSlateGrey">Confirm Password</label>   
            <div class="mt-1">
              <input id="password-confirm" type="password" name="password_confirmation" required autocomplete="new-password" class="block w-full rounded-md shadow-sm font-montserrat border-darkSlateGrey focus:ring-ultraViolet focus:border-ultraViolet">   
            </div>
          </div>

          <!-- Register Button -->
          <div class="mt-6 text-center">
            <button type="submit" class="w-full px-6 py-2 font-bold text-white transition-all duration-200 rounded-md bg-ultraViolet hover:bg-cambridgeBlue hover:text-darkSlateGrey border-ultraViolet hover:border-cambridgeBlue font-montserrat">Sign up</button>
          </div>
        </form>

        <!-- Registration Link -->
        <p class="mt-6 text-3xl text-center text-gray-600 font-montserrat">   
          Have an account?
          <a href="{{ route('login') }}" class="font-medium font-montserrat text-darkSlateGrey hover:text-cambrigdeBlue">Sign in</a>   
        </p>
      </div>
    </section>
  </div>
</div>
@endsection
