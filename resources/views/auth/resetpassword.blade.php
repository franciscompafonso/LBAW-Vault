@extends('layouts.app')

@section('Title', 'Reset Password')

@section('content')
    <div class="max-w-lg mx-auto bg-white p-8 rounded-lg shadow-lg mt-12">
        <h2 class="text-3xl font-bold text-center mb-4 font-montserrat">Reset Password</h2>
        <p class="text-center text-gray-600 mb-6">Enter a new password to secure your account.</p>
        
        <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <!-- New Password Input -->
            <div>
                <label for="password" class="block text-lg font-semibold font-montserrat text-gray-700">New Password</label>
                <input 
                    id="password" 
                    type="password" 
                    maxlength="255" 
                    name="password" 
                    required 
                    class="w-full p-3 mt-2 border border-gray-300 rounded-md font-montserrat focus:ring-ultraViolet focus:border-ultraViolet" 
                    minlength="8" 
                    placeholder="Enter new password"
                    aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}"
                >
                @error('password')
                    <div id="password" class="mt-2 text-sm text-red-600" aria-live="polite">{{ $message }}</div>
                @enderror
            </div>

            <!-- Confirm Password Input -->
            <div>
                <label for="password_confirmation" class="block text-lg font-semibold font-montserrat text-gray-700">Confirm Password</label>
                <input 
                    id="password_confirmation" 
                    type="password" 
                    maxlength="255" 
                    name="password_confirmation" 
                    required 
                    class="w-full p-3 mt-2 border border-gray-300 rounded-md font-montserrat focus:ring-ultraViolet focus:border-ultraViolet" 
                    placeholder="Confirm your password"
                >
                @error('password_confirmation')
                    <div id="confirmPassword" class="mt-2 text-sm text-red-600" aria-live="polite">{{ $message }}</div>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="mt-6">
                <button type="submit" class="w-full px-6 py-3 text-white font-bold transition-all duration-200 bg-ultraViolet rounded-md hover:bg-cambridgeBlue hover:text-darkSlateGrey font-montserrat" aria-label="Submit new password to reset your account">
                    Reset Password
                </button>
            </div>
        </form>
    </div>
@endsection

