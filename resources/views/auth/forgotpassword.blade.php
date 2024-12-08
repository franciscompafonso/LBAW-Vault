@extends('layouts.app')

@section('Title', 'Forgot Password')

@section('content')
    <div class="max-w-lg mx-auto bg-white p-8 rounded-lg shadow-lg mt-12">
        <h2 class="text-3xl font-bold text-center mb-4 font-montserrat">Forgot Password</h2>
        <p class="text-center text-gray-600 mb-6">Enter your email address to receive a password reset link.</p>
        
        <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
            @csrf

            <!-- Email Input -->
            <div>
                <label for="email" class="block text-lg font-semibold font-montserrat text-gray-700">Email Address</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    required 
                    class="w-full p-3 mt-2 border border-gray-300 rounded-md font-montserrat focus:ring-ultraViolet focus:border-ultraViolet" 
                    placeholder="Enter your email"
                >
                @error('email')
                    <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="mt-6">
                <button type="submit" class="w-full px-6 py-3 text-white font-bold transition-all duration-200 bg-ultraViolet rounded-md hover:bg-cambridgeBlue hover:text-darkSlateGrey font-montserrat">
                    Send Reset Link
                </button>
            </div>
        </form>

        @if(session('status'))
            <div class="mt-4 text-sm text-center text-green-600 font-montserrat">
                {{ session('status') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mt-4 text-sm text-center text-red-600 font-montserrat">
                {{ session('error') }}
            </div>
        @endif
    </div>
@endsection
