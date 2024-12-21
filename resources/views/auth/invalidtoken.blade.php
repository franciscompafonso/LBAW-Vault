@extends('layouts.app')

@section('Title', 'Reset Token Expired')

@section('content')
<script src="{{ url('js/auth/invalidtoken.js') }}" defer></script>
    <div class="max-w-md p-8 mx-auto mt-10 bg-white rounded-lg shadow-md">
        <div class="text-center">
            <h1 class="text-3xl font-semibold text-red-500" role="alert">Password Reset Token Expired</h1>
            <p class="mt-3 text-gray-700">Unfortunately, your password reset link has expired.</p>
            <p class="mt-4 text-gray-700">To resolve this, check your email for the most recent password reset link. If the issue persists, you can generate a new request using the button below.</p>
            <a href="{{ route('password.request') }}" class="inline-block px-6 py-2 mt-4 text-white transition duration-200 rounded-md bg-wisteria hover:bg-ultraViolet" aria-label="Request a new password reset link">
                <span class="hidden loading-icon" id="loading-reset">⏳</span> 
                Request New Password Reset Link
            </a>
        </div>
    </div>
@endsection
