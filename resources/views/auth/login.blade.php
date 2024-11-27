@extends('layouts.app')

@section('Title', 'Login')
@section('content')
<div class="w-full sm:my-auto">  
  <div class="sm:mx-auto sm:w-full sm:max-w-md">
    <h2 class="mt-6 text-3xl font-extrabold text-center font-montserrat text-ultraViolet">Sign in to your account</h2>
  </div>

  <div class="px-6 py-8 mt-8 rounded-lg shadow-md sm:mx-auto sm:w-full sm:max-w-md bg-babyPowder sm:px-10">
      <form method="POST" action="{{ route('login') }}" class="space-y-6">
        {{ csrf_field() }}

        <!-- Email Field -->
        <div>
          <label for="email" class="block text-lg font-medium font-montserrat text-darkSlateGrey">Email address</label>
          <div class="mt-1">
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email" 
              class="block w-full rounded-md shadow-sm font-montserrat border-darkSlateGrey focus:ring-ultraViolet focus:border-ultraViolet">
          </div>
          @if ($errors->has('email'))
            <p class="mt-1 text-lg text-red-500">{{ $errors->first('email') }}</p>
          @endif
        </div>

        <!-- Password Field -->
        <div>
          <div class="flex justify-between">
            <label for="password" class="block text-lg font-medium font-montserrat text-darkSlateGrey">Password</label>
            <a href="{{ route('password.request') }}" class="text-lg font-medium font-montserrat text-darkSlateGrey hover:text-cambrigdeBlue">Forgot password?</a>
          </div>
          <div class="mt-1">
            <input id="password" type="password" name="password" required autocomplete="current-password" 
              class="block w-full rounded-md shadow-sm font-montserrat border-darkSlateGrey focus:ring-ultraViolet focus:border-ultraViolet">
          </div>
          @if ($errors->has('password'))
            <p class="mt-1 text-lg text-red-500 font-montserrat">{{ $errors->first('password') }}</p>
          @endif
        </div>

        <!-- Remember Me Checkbox -->
        <div class="flex items-center">
          <input id="remember" name="remember" type="checkbox" 
            class="w-4 h-4 rounded text-ultraViolet font-montserrat border-darkSlateGrey focus:ring-ultraViolet">
          <label for="remember" class="block my-auto ml-2 text-lg font-montserrat text-ultraViolet">Remember Me</label>
        </div>

        <!-- Sign In Button -->
        <div class="mt-6 text-center">
            <button type="submit" class="px-6 py-2 font-bold text-white transition-all duration-200 rounded-md bg-ultraViolet hover:bg-cambridgeBlue hover:text-darkSlateGrey border-ultraViolet hover:border-cambridgeBlue font-montserrat">Sign in</button>
        </div>
      </form>

      <!-- Registration Link -->
      <p class="mt-6 text-lg text-center text-gray-600 font-montserrat">
        Not a member? 
        <a href="{{ route('register') }}" class="font-medium font-montserrat text-darkSlateGrey hover:text-cambrigdeBlue">Register</a>
      </p>
    </div>
</div>
@endsection
