@extends('layouts.app')

@section('Title', 'Register')
@section('content')
<div class="w-full sm:my-auto">   
  <div class="sm:mx-auto sm:w-full sm:max-w-md">
    <h2 class="mt-6 text-3xl font-extrabold text-center font-montserrat text-ultraViolet">Create your account</h2>   
  </div>

  <div class="px-6 py-8 mt-8 rounded-lg shadow-md sm:mx-auto sm:w-full sm:max-w-md bg-babyPowder sm:px-10">   
    <form method="POST" action="{{ route('register') }}" class="space-y-6">
      {{ csrf_field() }}

      <!-- Name Field -->
      <div>
        <label for="name" class="block text-lg font-medium font-montserrat text-darkSlateGrey">Name</label>   
        <div class="mt-2">
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
        <label for="username" class="block text-lg font-medium font-montserrat text-darkSlateGrey">Username</label>   
        <div class="mt-2">
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
        <label for="email" class="block text-lg font-medium font-montserrat text-darkSlateGrey">E-Mail Address</label>   
        <div class="mt-2">
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
        <label for="password" class="block text-lg font-medium font-montserrat text-darkSlateGrey">Password</label>   
        <div class="mt-2">
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
        <label for="password-confirm" class="block text-lg font-medium font-montserrat text-darkSlateGrey">Confirm Password</label>   
        <div class="mt-2">
          <input id="password-confirm" type="password" name="password_confirmation" required autocomplete="new-password" class="block w-full rounded-md shadow-sm font-montserrat border-darkSlateGrey focus:ring-ultraViolet focus:border-ultraViolet">   
        </div>
      </div>

      <!-- Register Button -->
      <div class="mt-6 text-center">
            <button type="submit" class="px-6 py-2 font-bold text-white transition-all duration-200 rounded-md bg-ultraViolet hover:bg-cambridgeBlue hover:text-darkSlateGrey border-ultraViolet hover:border-cambridgeBlue font-montserrat">Register</button>
        </div>
    </form>

    <p class="mt-6 text-lg text-center text-gray-600 font-montserrat">   
      Already have an account?
      <a href="{{ route('login') }}" class="font-medium font-montserrat text-darkSlateGrey hover:text-cambrigdeBlue">Login</a>   
    </p>
  </div>
</div>
@endsection
