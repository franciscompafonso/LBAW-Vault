@extends('layouts.app')

@section('content')
<div class="flex min-h-screen flex-col justify-center bg-whiteSmoke px-6 py-12 lg:px-8">   
  <div class="sm:mx-auto sm:w-full sm:max-w-md">
    <h2 class="mt-10 text-center text-3xl font-extrabold tracking-tight text-eerieBlack">Create your account</h2>   
  </div>

  <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-md bg-eerieBlack p-8 rounded-lg shadow-xl">   
    <form method="POST" action="{{ route('register') }}" class="space-y-6">
      {{ csrf_field() }}

      <!-- Name Field -->
      <div>
        <label for="name" class="block text-sm font-medium text-whiteSmoke">Name</label>   
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
        <label for="username" class="block text-sm font-medium text-whiteSmoke">Username</label>   
        <div class="mt-2">
          <input id="username" type="text" name="username" value="{{ old('username') }}" required autocomplete="username" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-2 focus:ring-keppel focus:border-keppel py-2 px-3 text-white placeholder-charcoal sm:text-base">   
          @if ($errors->has('username'))
            <span class="text-sm text-red-600 mt-1">
              {{ $errors->first('username') }}
            </span>
          @endif
        </div>
      </div>

      <!-- Email Field -->
      <div>
        <label for="email" class="block text-sm font-medium text-whiteSmoke">E-Mail Address</label>   
        <div class="mt-2">
          <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-2 focus:ring-keppel focus:border-keppel py-2 px-3 text-white placeholder-charcoal sm:text-base">   
          @if ($errors->has('email'))
            <span class="text-sm text-red-600 mt-1">
              {{ $errors->first('email') }}
            </span>
          @endif
        </div>
      </div>

      <!-- Password Field -->
      <div>
        <label for="password" class="block text-sm font-medium text-whiteSmoke">Password</label>   
        <div class="mt-2">
          <input id="password" type="password" name="password" required autocomplete="new-password" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-2 focus:ring-keppel focus:border-keppel py-2 px-3 text-white placeholder-gray-500 sm:text-base">   
          @if ($errors->has('password'))
            <span class="text-sm text-red-600 mt-1">
              {{ $errors->first('password') }}
            </span>
          @endif
        </div>
      </div>

      <!-- Confirm Password Field -->
      <div>
        <label for="password-confirm" class="block text-sm font-medium text-whiteSmoke">Confirm Password</label>   
        <div class="mt-2">
          <input id="password-confirm" type="password" name="password_confirmation" required autocomplete="new-password" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-2 focus:ring-keppel focus:border-keppel py-2 px-3 text-white placeholder-gray-500 sm:text-base">   
        </div>
      </div>

      <!-- Register Button -->
      <div>
        <button type="submit" class="flex w-full justify-center rounded-md bg-charcoal px-4 py-2 text-base font-semibold text-white shadow-sm hover:bg-keppel focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-keppel">   
          Register
        </button>
      </div>
    </form>

    <p class="mt-10 text-center text-sm text-white">   
      Already have an account?
      <a href="{{ route('login') }}" class="font-semibold text-keppel hover:text-white">Login</a>   
    </p>
  </div>
</div>
@endsection
