@extends('layouts.app')

@section('content')
<div class="flex min-h-screen flex-col justify-center bg-gray-50 px-6 py-12 lg:px-8">
  <div class="sm:mx-auto sm:w-full sm:max-w-md">
    <h2 class="mt-10 text-center text-3xl font-extrabold tracking-tight text-blue-500">Sign in to your account</h2>
  </div>

  <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-md bg-white p-8 rounded-lg shadow-xl">
    <form method="POST" action="{{ route('login') }}" class="space-y-6">
      {{ csrf_field() }}

      <!-- Email Field -->
      <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
        <div class="mt-2">
          <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 py-2 px-3 text-gray-900 placeholder-gray-500 sm:text-base">
          @if ($errors->has('email'))
            <span class="text-sm text-red-600 mt-1">
              {{ $errors->first('email') }}
            </span>
          @endif
        </div>
      </div>

      <!-- Password Field -->
      <div>
        <div class="flex items-center justify-between">
          <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
          <div class="text-sm">
            <a href="#" class="font-semibold text-blue-500 hover:text-blue-400">Forgot password?</a>
          </div>
        </div>
        <div class="mt-2">
          <input id="password" type="password" name="password" required autocomplete="current-password" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 py-2 px-3 text-gray-900 placeholder-gray-500 sm:text-base">
          @if ($errors->has('password'))
            <span class="text-sm text-red-600 mt-1">
              {{ $errors->first('password') }}
            </span>
          @endif
        </div>
      </div>

      <!-- Remember Me Checkbox -->
      <div class="flex items-center">
        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-blue-500 focus:ring-2 focus:ring-blue-500">
        <label for="remember" class="ml-2 block text-sm text-blue-500">Remember Me</label>
      </div>

      <!-- Sign In Button -->
      <div>
        <button type="submit" class="flex w-full justify-center rounded-md bg-blue-500 px-4 py-2 text-base font-semibold text-white shadow-sm hover:bg-blue-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-500">
          Sign in
        </button>
      </div>
    </form>

    <!-- Registration Link -->
    <p class="mt-10 text-center text-sm text-gray-500">
      Not a member?
      <a href="{{ route('register') }}" class="font-semibold text-blue-500 hover:text-blue-400">Register</a>
    </p>

    <!-- Success Message -->
    @if (session('success'))
      <p class="mt-4 text-center text-sm text-green-600">
        {{ session('success') }}
      </p>
    @endif
  </div>
</div>
@endsection
