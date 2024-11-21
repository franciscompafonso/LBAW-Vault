@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto mt-6 p-6 bg-white rounded-lg shadow-md">
        <div class="text-center mb-6">
            <h2 class="text-3xl font-bold text-ultraViolet font-montserrat">User Information</h2>
        </div>
    
        <!-- Profile Picture Section -->
        <div class="flex justify-center mb-6">
            @if ($user->profilepicture)
                <img src="{{ asset('images/profile-pictures/' . $user->profilepicture) }}" alt="Profile Picture" class="w-32 h-32 rounded-full border-2 border-darkSlateGrey">
            @else
                <div class="w-32 h-32 bg-gray-300 rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-user text-white text-3xl"></i>
                </div>
            @endif
        </div>

        <!-- User Info Section -->
        <div class="text-center">
            <div class="flex justify-center items-center space-x-2">
                <p class="text-2xl font-montserrat font-semibold">Name:</p>
                <p class="text-2xl font-montserrat">{{ $user->name }}</p>
            </div>
            <div class="flex justify-center items-center space-x-2">
                <p class="text-2xl font-montserrat font-semibold">Username:</p>
                <p class="text-2xl font-montserrat">{{ $user->username }}</p>
            </div>
            <div class="flex justify-center items-center space-x-2">
                <p class="text-2xl font-montserrat font-semibold">Email:</p>
                <p class="text-2xl font-montserrat">{{ $user->email }}</p>
            </div>
        </div>

        <!-- Edit Profile Link -->
        <div class="flex flex-col items-center space-y-6 py-8 px-4">
            <a href="{{ route('user.edit.form', ['id' => $user->id]) }}" 
                class="w-full max-w-md bg-ultraViolet hover:bg-cambridgeBlue text-whiteSmoke text-2xl font-bold py-3 rounded-lg shadow-md hover:text-darkSlateGrey text-center transition-all duration-200 font-montserrat">
                Edit Profile
            </a>
        </div>
    </div>
@endsection

