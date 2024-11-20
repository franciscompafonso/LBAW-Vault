@extends('layouts.app')

@section('content')
<div class="flex min-h-screen flex-col justify-center bg-softCream px-6 py-12 lg:px-8">   
  <div class="sm:mx-auto sm:w-full sm:max-w-md">
    <h2 class="mt-10 text-center text-3xl font-extrabold tracking-tight text-deepNavy">Create your account</h2>   
  </div>

  <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-md bg-warmTaupe p-8 rounded-lg shadow-xl">   
    <form method="POST" action="{{ route('register') }}" class="space-y-6">
      {{ csrf_field() }}

      <!-- Name Field -->
      <div>
        <label for="name" class="block text-sm font-medium text-softCream">Name</label>   
        <div class="mt-2">
          <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-2 focus:ring-skyBlue focus:border-skyBlue py-2 px-3 text-gray-900 placeholder-gray-500 sm:text-base">   
          @if ($errors->has('name'))
            <span class="text-sm text-red-600 mt-1">
              {{ $errors->first('name') }}
            </span>
          @endif
        </div>
      </div>

      <!-- Username Field -->
      <div>
        <label for="username" class="block text-sm font-medium text-softCream">Username</label>   
        <div class="mt-2">
          <input id="username" type="text" name="username" value="{{ old('username') }}" required autocomplete="username" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-2 focus:ring-skyBlue focus:border-skyBlue py-2 px-3 text-gray-900 placeholder-gray-500 sm:text-base">   
          @if ($errors->has('username'))
            <span class="text-sm text-red-600 mt-1">
              {{ $errors->first('username') }}
            </span>
          @endif
        </div>
      </div>

      <!-- Email Field -->
      <div>
        <label for="email" class="block text-sm font-medium text-softCream">E-Mail Address</label>   
        <div class="mt-2">
          <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-2 focus:ring-skyBlue focus:border-skyBlue py-2 px-3 text-gray-900 placeholder-gray-500 sm:text-base">   
          @if ($errors->has('email'))
            <span class="text-sm text-red-600 mt-1">
              {{ $errors->first('email') }}
            </span>
          @endif
        </div>
      </div>

      <!-- Password Field -->
      <div>
        <label for="password" class="block text-sm font-medium text-softCream">Password</label>   
        <div class="mt-2">
          <input id="password" type="password" name="password" required autocomplete="new-password" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-2 focus:ring-skyBlue focus:border-skyBlue py-2 px-3 text-gray-900 placeholder-gray-500 sm:text-base">   
          @if ($errors->has('password'))
            <span class="text-sm text-red-600 mt-1">
              {{ $errors->first('password') }}
            </span>
          @endif
        </div>
      </div>

      <!-- Confirm Password Field -->
      <div>
        <label for="password-confirm" class="block text-sm font-medium text-softCream">Confirm Password</label>   
        <div class="mt-2">
          <input id="password-confirm" type="password" name="password_confirmation" required autocomplete="new-password" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-2 focus:ring-skyBlue focus:border-skyBlue py-2 px-3 text-gray-900 placeholder-gray-500 sm:text-base">   
        </div>
      </div>

      <!-- Register Button -->
      <div>
        <button type="submit" class="flex w-full justify-center rounded-md bg-skyBlue px-4 py-2 text-base font-semibold text-white shadow-sm hover:bg-deepNavy focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-skyBlue">   
          Register
        </button>
      </div>
    </form>

    <p class="mt-10 text-center text-sm text-deepNavy">   
      Already have an account?
      <a href="{{ route('login') }}" class="font-semibold text-softCream hover:text-skyBlue">Login</a>   
    </p>
  </div>
</div>
@endsection
