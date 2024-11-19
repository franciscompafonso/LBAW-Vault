@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto mt-6 p-6 bg-white rounded-lg shadow-md">
        <h2 class="text-3xl font-semibold text-purple-900">Profile of {{ $user->name }}</h2>
        
        <div class="mt-6">
            <p><strong>Name:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>

            <!-- Display profile picture if exists -->
            @if ($user->profile_picture)
                <div class="mt-4">
                    <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile Picture" class="w-32 h-32 rounded-full">
                </div>
            @else
                <p class="mt-4 text-gray-500">No profile picture uploaded.</p>
            @endif

            <!-- Optionally, show other user details -->
            <div class="mt-4">
                <a href="{{ route('user.edit.form', ['id' => $user->id]) }}" class="text-blue-600 hover:text-blue-800">Edit Profile</a>
            </div>
        </div>
    </div>
@endsection
