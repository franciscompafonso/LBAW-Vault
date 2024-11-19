@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto mt-6 p-6 bg-white rounded-lg shadow-md">
        <h2 class="text-3xl font-semibold text-blue-400 text-center">Edit Profile</h2>

        <!-- Edit Profile Form -->
        <form action="{{ route('user.edit.action', ['id' => $user->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST')

            <!-- Profile Picture Input -->
            <div class="mb-4 flex items-center space-x-4">
                <div class="flex-shrink-0">
                    @if ($user->profilePicture)
                        <img id="profile-picture-preview" src="{{ asset('storage/' . $user->profilePicture) }}" alt="Current Profile Picture" class="w-32 h-32 rounded-full border-4 border-blue-400">
                    @else
                        <div id="profile-picture-preview" class="w-32 h-32 bg-gray-300 rounded-full flex items-center justify-center">
                            <i class="fa-solid fa-user text-white text-3xl"></i>
                        </div>
                    @endif
                </div>
                <div>
                    <label for="profilePicture" class="block text-lg font-semibold">Upload Image</label>
                    <input type="file" id="profilePicture" name="profilePicture" class="w-full p-2 border border-gray-300 rounded-md mt-2" onchange="previewImage(event)">
                    @error('profilePicture')
                        <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Name -->
            <div class="mb-4">
                <label for="name" class="block text-lg font-semibold">Name</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="w-full p-2 border border-gray-300 rounded-md" required>
                @error('name')
                    <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Username -->
            <div class="mb-4">
                <label for="username" class="block text-lg font-semibold">Username</label>
                <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}" class="w-full p-2 border border-gray-300 rounded-md" required>
                @error('username')
                    <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
                @enderror
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block text-lg font-semibold">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="w-full p-2 border border-gray-300 rounded-md" required>
                @error('email')
                    <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label for="password" class="block text-lg font-semibold">New Password</label>
                <input type="password" id="password" name="password" class="w-full p-2 border border-gray-300 rounded-md">
                @error('password')
                    <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="mb-4">
                <label for="password_confirmation" class="block text-lg font-semibold">Confirm New Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="w-full p-2 border border-gray-300 rounded-md">
                @error('password_confirmation')
                    <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="mt-6 text-center">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-800">Save Changes</button>
            </div>
        </form>
    </div>

    <!-- Image Preview -->
    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('profile-picture-preview');
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(file); 
            }
        }
    </script>
@endsection
