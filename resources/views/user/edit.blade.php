@extends('layouts.app')

@section('Title', 'Edit Profile')
@section('content')
<script src="{{ url('js/user/edit.js') }}" defer></script>
    <div class="max-w-2xl p-6 mx-auto mt-6 bg-white rounded-lg shadow-md">
        <div class="mb-6 text-center">
            <h2 class="text-3xl font-bold text-ultraViolet font-montserrat">Edit Profile</h2>
        </div>

        <!-- Edit Profile Form -->
        <form action="{{route('user.edit.action', ['id' => $user->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST')

            <!-- Profile Picture Input -->
            <fieldset class="p-4 mb-6 border border-gray-300 rounded-md">
                <legend class="px-2 text-lg font-bold text-ultraViolet font-montserrat">Profile Picture</legend>
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <img id="profile-picture-preview" src="{{ asset('images/profile-pictures/'. ($user->picture->path ?? App\Models\User::DEFAULT_PIC)) }}" alt="Profile Picture" class="w-32 h-32 border-2 rounded-full border-ultraViolet">
                    </div>
                    <div>
                        <label for="profilepicture" class="block text-lg font-semibold font-montserrat">Upload Image</label>
                        <input type="file" id="profilepicture" name="file" class="w-full p-2 mt-2 border border-gray-300 rounded-md" onchange="previewImage(event)" aria-label="Upload a new profile picture">
                        @error('file')
                            <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </fieldset>

            <fieldset class="p-4 mb-6 border border-gray-300 rounded-md">
                <legend class="px-2 text-lg font-bold text-ultraViolet font-montserrat">Personal Information</legend>
                <!-- Name -->
                <div class="mb-4">
                    <label for="name" class="block text-lg font-semibold font-montserrat">Name</label>
                    <input type="text" maxlength="70" id="name" name="name" value="{{ old('name', $user->name) }}" class="w-full p-2 border border-gray-300 rounded-md font-montserrat" required aria-label="Enter your new name">
                    @error('name')
                        <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Username -->
                <div class="mb-4">
                    <label for="username" class="block text-lg font-semibold font-montserrat">Username</label>
                    <input type="text" maxlength="25" id="username" name="username" value="{{ old('username', $user->username) }}" class="w-full p-2 border border-gray-300 rounded-md font-montserrat" required aria-label="Enter your new username">
                    @error('username')
                        <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="block text-lg font-semibold font-montserrat">Email</label>
                    <input type="email" maxlength="255" id="email" name="email" value="{{ old('email', $user->email) }}" class="w-full p-2 border border-gray-300 rounded-md font-montserrat" required aria-label="Enter your new email address">
                    @error('email')
                        <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                    @enderror
                </div>
            </fieldset>

            <!-- Password Group -->
            <fieldset class="p-4 mb-6 border border-gray-300 rounded-md">
                <legend class="px-2 text-lg font-bold text-ultraViolet font-montserrat">Change Password</legend>
                <!-- Current Password -->
                <div class="mb-4">
                    <label for="current_password" class="block text-lg font-semibold font-montserrat">Current Password</label>
                    <input 
                        type="password" 
                        maxlength="255" 
                        id="current_password" 
                        name="current_password" 
                        class="w-full p-2 border border-gray-300 rounded-md font-montserrat" 
                        placeholder="Enter current password"
                        aria-label="Enter current password"
                    >
                    @error('current_password')
                        <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                    @enderror
                </div>

                <!-- New Password -->
                <div class="mb-4">
                    <label for="new_password" class="block text-lg font-semibold font-montserrat">New Password</label>
                    <input 
                        type="password" 
                        maxlength="255" 
                        id="new_password" 
                        name="new_password" 
                        class="w-full p-2 border border-gray-300 rounded-md font-montserrat" 
                        placeholder="Enter new password"
                        aria-label="Enter new password"
                    >
                    <span class="text-sm text-gray-500">Must be at least 8 characters</span>
                    @error('new_password')
                        <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Confirm New Password -->
                <div class="mb-4">
                    <label for="new_password_confirmation" class="block text-lg font-semibold font-montserrat">Confirm New Password</label>
                    <input 
                        type="password" 
                        maxlength="255" 
                        id="new_password_confirmation" 
                        name="new_password_confirmation" 
                        class="w-full p-2 border border-gray-300 rounded-md font-montserrat" 
                        placeholder="Confirm new password"
                        aria-label="Confirm new password"
                    >
                    <span class="text-sm text-gray-500">Must be at least 8 characters</span>
                    @error('new_password_confirmation')
                        <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                    @enderror
                </div>
            </fieldset>

            <div class="mt-6 text-center">
                <button type="submit" class="px-8 py-2 font-bold text-white transition-all duration-200 rounded-md bg-ultraViolet hover:bg-cambridgeBlue hover:text-darkSlateGrey border-ultraViolet hover:border-cambridgeBlue font-montserrat" aria-label="Save profile changes">
                    <span id="timeEmoji" class="hidden">⏳</span>
                    Save Changes</button>
            </div>

        </form>

        <!-- Delete Account Form -->
        <div class="mt-6 text-center">
            <form action="{{ auth()->guard('admin')->check()  ? route('admin.delete.user', ['user' => $user->id]) : route('user.delete', ['id' => $user->id]) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-8 py-2 font-bold text-white transition-all duration-200 rounded-md bg-ultraViolet border-ultraViolet font-montserrat hover:bg-red-500 hover:text-darkSlateGrey hover:border-red-500" aria-label="Delete account">
                    Delete Account
                </button>
            </form>
        </div>

        @if(auth()->guard('admin')->check())
            <div class="mt-6 text-center">
                @if ($user->isblocked)
                    <form action="{{ route('admin.unblock.user', ['user' => $user->id]) }}" method="POST" class="inline-block">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="px-8 py-2 font-bold text-white transition-all duration-200 rounded-md bg-ultraViolet border-ultraViolet font-montserrat hover:bg-green-500 hover:text-darkSlateGrey hover:border-green-500" aria-label="Unblock User">
                            Unblock User
                        </button>
                    </form>
                @else
                    <form action="{{ route('admin.block.user', ['user' => $user->id]) }}" method="POST" class="inline-block">
                        @csrf
                        <button type="submit" class="px-8 py-2 font-bold text-white transition-all duration-200 rounded-md bg-ultraViolet border-ultraViolet font-montserrat hover:bg-red-700 hover:text-white hover:border-red-700" aria-label="Block User">
                            Block User
                        </button>
                    </form>
                @endif
            </div>
        @endif
        
    </div>
@endsection
