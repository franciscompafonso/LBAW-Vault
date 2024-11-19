@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto mt-6 p-6 bg-white rounded-lg shadow-md">
        <div class="text-center mb-6">
            <h2 class="text-3xl font-semibold text-blue-400">User Information</h2>
        </div>
    
        <!-- Profile Picture Section -->
        <div class="flex justify-center mb-6">
            @if ($user->profilePicture)
                <img src="{{ asset('storage/' . $user->profilePicture) }}" alt="Profile Picture" class="w-32 h-32 rounded-full border-4 border-blue-400">
            @else
                <div class="w-32 h-32 bg-gray-300 rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-user text-white text-3xl"></i>
                </div>
            @endif
        </div>

        <!-- User Info Section -->
        <div class="text-center">
            <div class="flex justify-center items-center space-x-2">
                <p class="text-2xl font-semibold">Name:</p>
                <p class="text-2xl">{{ $user->name }}</p>
            </div>
            <div class="flex justify-center items-center space-x-2">
                <p class="text-2xl font-semibold">Username:</p>
                <p class="text-2xl">{{ $user->username }}</p>
            </div>
            <div class="flex justify-center items-center space-x-2">
                <p class="text-2xl font-semibold">Email:</p>
                <p class="text-2xl">{{ $user->email }}</p>
            </div>
        </div>

        <!-- Edit Profile Link -->
        <div class="mt-6 text-center">
            <a href="{{ route('user.edit.form', ['id' => $user->id]) }}" class="text-blue-600 hover:text-blue-800">Edit Profile</a>
        </div>
    </div>
@endsection

