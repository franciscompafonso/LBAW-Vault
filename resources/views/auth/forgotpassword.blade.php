@extends('layouts.app')

@section('content')
    <div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-md">
        <h2 class="text-2xl font-semibold text-center mb-4">Forgot Password</h2>
        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email Input -->
            <div class="mb-4">
                <label for="email" class="block text-gray-700">Enter your email</label>
                <input type="email" id="email" name="email" required class="w-full p-2 mt-2 border border-gray-300 rounded-md" placeholder="Enter your email">
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full bg-wisteria text-white p-2 rounded-md hover:bg-wisteria transition duration-200">
                Send Reset Link
            </button>
        </form>

        @if(session('status'))
            <p class="text-green-500 mt-4 text-center">{{ session('status') }}</p>
        @endif

        @if(session('error'))
            <p class="text-red-500 mt-4 text-center">{{ session('error') }}</p>
        @endif
    </div>
@endsection
