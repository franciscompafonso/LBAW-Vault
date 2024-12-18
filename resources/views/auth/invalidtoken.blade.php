@extends('layouts.app')

@section('Title', 'Reset Token Expired')

@section('content')
    <div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-md mt-10">
        <div class="text-center">
            <h1 class="text-3xl font-semibold text-red-500" role="alert">Password Reset Token Expired</h1>
            <p class="mt-3 text-gray-700">Unfortunately, your password reset link has expired.</p>
            <p class="mt-4 text-gray-700">To resolve this, check your email for the most recent password reset link. If the issue persists, you can generate a new request using the button below.</p>
            <a href="{{ route('password.request') }}" class="inline-block mt-4 bg-wisteria text-white px-6 py-2 rounded-md hover:bg-ultraViolet transition duration-200" aria-label="Request a new password reset link">
                <span class="loading-icon hidden" id="loading-reset">⏳</span> 
                Request New Password Reset Link
            </a>
        </div>
    </div>
    <script>
        const requestButton = document.getElementById('request-reset-btn');
        requestButton.addEventListener('click', function(event) {
            const loadingIcon = document.getElementById('loading-reset');
            if (loadingIcon) {
                loadingIcon.classList.remove('hidden');
            }
        });
    </script>
@endsection
