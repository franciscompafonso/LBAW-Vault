@extends('layouts.app')

@section('content')
    <div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-md mt-10">
        <h2 class="text-2xl font-semibold text-center mb-4">Reset Password</h2>
        <p class="text-center text-gray-700 mb-6">You can now enter a new password for your account.</p>
        
        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <!-- New Password Input -->
            <div>
                <label for="password" class="block text-gray-700">New Password</label>
                <input id="password" type="password" name="password" required class="w-full p-2 mt-2 border border-gray-300 rounded-md" minlength="8" placeholder="Enter new password">
                <span id="password-error" class="text-red-500"></span>
            </div>

            <!-- Confirm Password Input -->
            <div>
                <label for="password-confirm" class="block text-gray-700">Confirm Password</label>
                <input id="password-confirm" type="password" name="password_confirmation" required class="w-full p-2 mt-2 border border-gray-300 rounded-md" placeholder="Confirm your password">
                <span id="password-confirm-error" class="text-red-500"></span>
            </div>

            <!-- Submit Button -->
            <button type="submit" id="submit-button" class="w-full bg-wisteria text-white p-2 rounded-md hover:bg-ultraViolet transition duration-200">
                Reset Password
            </button>
        </form>
    </div>
@endsection
