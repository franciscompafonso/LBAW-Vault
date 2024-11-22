@extends('layouts.app')

@section('content')
<div class="sm:my-auto w-full">   
  <div class="sm:mx-auto sm:w-full sm:max-w-md">
    <h2 class="mt-6 text-center text-3xl font-montserrat font-extrabold text-ultraViolet">Create your account</h2>   
  </div>

  <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md bg-babyPowder py-8 px-6 shadow-md rounded-lg sm:px-10">   
    <form method="POST" action="{{ route('register') }}" class="space-y-6">
      {{ csrf_field() }}

      <!-- Name Field -->
      <div>
        <label for="name" class="block text-lg font-montserrat font-medium text-darkSlateGrey">Name</label>   
        <div class="mt-2">
          <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-2 focus:ring-keppel focus:border-keppel py-2 px-3 text-white placeholder-charcoal sm:text-base">   
          @if ($errors->has('name'))
            <span class="text-sm text-red-600 mt-1">
              {{ $errors->first('name') }}
            </span>
          @endif
        </div>
      </div>

      <!-- Username Field -->
      <div>
        <label for="username" class="block text-lg font-montserrat font-medium text-darkSlateGrey">Username</label>   
        <div class="mt-2">
          <input id="username" type="text" name="username" value="{{ old('username') }}" required autocomplete="username" class="block w-full rounded-md font-montserrat border-darkSlateGrey shadow-sm focus:ring-ultraViolet focus:border-ultraViolet">   
          @if ($errors->has('username'))
            <span class="text-sm text-red-600 mt-1">
              {{ $errors->first('username') }}
            </span>
          @endif
        </div>
      </div>

      <!-- Email Field -->
      <div>
        <label for="email" class="block text-lg font-montserrat font-medium text-darkSlateGrey">E-Mail Address</label>   
        <div class="mt-2">
          <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" class="block w-full rounded-md font-montserrat border-darkSlateGrey shadow-sm focus:ring-ultraViolet focus:border-ultraViolet">   
          @if ($errors->has('email'))
            <span class="text-sm text-red-600 mt-1">
              {{ $errors->first('email') }}
            </span>
          @endif
        </div>
      </div>

      <!-- Password Field -->
      <div>
        <label for="password" class="block text-lg font-montserrat font-medium text-darkSlateGrey">Password</label>   
        <div class="mt-2">
          <input id="password" type="password" name="password" required autocomplete="new-password" class="block w-full rounded-md font-montserrat border-darkSlateGrey shadow-sm focus:ring-ultraViolet focus:border-ultraViolet">   
          @if ($errors->has('password'))
            <span class="text-sm text-red-600 mt-1">
              {{ $errors->first('password') }}
            </span>
          @endif
        </div>
      </div>

      <!-- Confirm Password Field -->
      <div>
        <label for="password-confirm" class="block text-lg font-montserrat font-medium text-darkSlateGrey">Confirm Password</label>   
        <div class="mt-2">
          <input id="password-confirm" type="password" name="password_confirmation" required autocomplete="new-password" class="block w-full rounded-md font-montserrat border-darkSlateGrey shadow-sm focus:ring-ultraViolet focus:border-ultraViolet">   
        </div>
      </div>

      <!-- Register Button -->
      <div class="mt-6 text-center">
            <button type="submit" class="bg-ultraViolet hover:bg-cambridgeBlue text-white px-6 py-2 rounded-md hover:text-darkSlateGrey border-ultraViolet hover:border-cambridgeBlue font-montserrat font-bold transition-all duration-200">Register</button>
        </div>
    </form>

    <p class="mt-6 text-center font-montserrat text-lg text-gray-600">   
      Already have an account?
      <a href="{{ route('login') }}" class="font-medium font-montserrat text-darkSlateGrey hover:text-cambrigdeBlue">Login</a>   
    </p>
  </div>
</div>
@endsection
