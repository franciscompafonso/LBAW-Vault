@extends('layouts.app')

@section('title', 'User Management')

@section('content')
    <div class="container mx-auto p-8">
        <h1 class="text-3xl font-bold mb-6">User Management</h1>

        <!-- Search Form -->
        <form action="{{ route('admin.search.user') }}" method="GET" class="mb-6">
            @csrf
            <div class="flex">
                <input 
                    type="text" 
                    name="email" 
                    class="w-full p-3 border rounded-l-lg focus:outline-none"
                    placeholder="Search by user email" 
                    value="{{ old('email') }}">
                <button type="submit" class="bg-blue-500 text-white px-4 rounded-r-lg hover:bg-blue-600">
                    Search
                </button>
            </div>
            @error('email')
                <p class="text-red-500 mt-2">{{ $message }}</p>
            @enderror
        </form>

        <!-- Search Results -->
        @if(isset($user))
            @if($user)
                <div class="bg-white p-4 rounded-lg shadow-md">
                    <h2 class="text-xl font-semibold">{{ $user->displayName }}</h2>
                    <p>Email: {{ $user->email }}</p>

                    <!-- Delete User Button -->
                    <form action="{{ route('admin.delete.user', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
    @csrf
    @method('DELETE')
    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">
        Delete User
    </button>
</form>

                </div>
            @else
                <p class="text-red-500">No user found with the email: {{ $search }}</p>
            @endif
        @endif

        @if(session('status'))
            <p class="text-green-500 mt-4">{{ session('status') }}</p>
        @endif
    </div>
@endsection
