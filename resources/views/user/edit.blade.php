@extends('layouts.app')

@section('Title', 'Edit Profile')
@section('content')
    <div class="max-w-2xl p-6 mx-auto mt-6 bg-white rounded-lg shadow-md">
        <div class="mb-6 text-center">
            <h2 class="text-3xl font-bold text-ultraViolet font-montserrat">Edit Profile</h2>
        </div>

        <!-- Edit Profile Form -->
        <form action="{{ auth()->guard('admin')->check() ? route('admin.edit.action', ['id' => $user->id]) : route('user.edit.action', ['id' => $user->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST')

            <!-- Profile Picture Input -->
            <div class="flex items-center mb-4 space-x-4">
                <div class="flex-shrink-0">
                @if ($user->profilepicture)
                    <img id="profile-picture-preview" src="{{ asset('images/profile-pictures/' . $user->profilepicture) }}" alt="Profile Picture" class="w-32 h-32 border-2 rounded-full border-ultraViolet">
                @else
                    <div id="profile-picture-preview" class="flex items-center justify-center w-32 h-32 bg-gray-300 rounded-full">
                        <i class="text-3xl text-white fa-solid fa-user"></i>
                    </div>
                @endif
                </div>
                <div>
                    <label for="profilepicture" class="block text-lg font-semibold font-montserrat">Upload Image</label>
                    <input type="file" id="profilepicture" name="profilepicture" class="w-full p-2 mt-2 border border-gray-300 rounded-md" onchange="previewImage(event)">
                    @error('profilepicture')
                        <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Name -->
            <div class="mb-4">
                <label for="name" class="block text-lg font-semibold font-montserrat">Name</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="w-full p-2 border border-gray-300 rounded-md font-montserrat" required>
                @error('name')
                    <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Username -->
            <div class="mb-4">
                <label for="username" class="block text-lg font-semibold font-montserrat">Username</label>
                <input type="text" id="username" name="username" value="{{ old('username', $user->displayName) }}" class="w-full p-2 border border-gray-300 rounded-md font-montserrat" required>
                @error('username')
                    <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                @enderror
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block text-lg font-semibold font-montserrat">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="w-full p-2 border border-gray-300 rounded-md font-montserrat" required>
                @error('email')
                    <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label for="password" class="block text-lg font-semibold font-montserrat">New Password</label>
                <input type="password" id="password" name="password" class="w-full p-2 border border-gray-300 rounded-md font-montserrat">
                @error('password')
                    <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="mb-4">
                <label for="password_confirmation" class="block text-lg font-semibold font-montserrat">Confirm New Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="w-full p-2 border border-gray-300 rounded-md font-montserrat">
                @error('password_confirmation')
                    <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                @enderror
            </div>
            <div class="mt-6 text-center">
                <button type="submit" class="px-6 py-2 font-bold text-white transition-all duration-200 rounded-md bg-ultraViolet hover:bg-cambridgeBlue hover:text-darkSlateGrey border-ultraViolet hover:border-cambridgeBlue font-montserrat">Save Changes</button>
            </div>
        </form>

        <!-- Delete Account Form -->
        <div class="mt-6 text-center">
            <form action="{{ route('user.delete', ['id' => $user->id]) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-6 py-2 font-bold text-white transition-all duration-200 bg-red-600 rounded-md hover:bg-red-700 font-montserrat">
                    Delete Account
                </button>
            </form>
        </div>
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
