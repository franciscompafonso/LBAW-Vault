@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto mt-6 p-6 bg-white rounded-lg shadow-md">
        <div class="text-center mb-6">
            <h2 class="text-3xl font-bold text-ultraViolet font-montserrat">Edit Profile</h2>
        </div>

        <!-- Edit Profile Form -->
        <form action="{{ route('user.edit.action', ['id' => $user->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST')

            <!-- Profile Picture Input -->
            <!-- Same as your original profile picture input -->
            <div class="mb-4 flex items-center space-x-4">
                <div class="flex-shrink-0">
                @if ($user->profilepicture)
                    <img id="profile-picture-preview" src="{{ asset('images/profile-pictures/' . $user->profilepicture) }}" alt="Profile Picture" class="w-32 h-32 rounded-full border-2 border-ultraViolet">
                @else
                    <div id="profile-picture-preview" class="w-32 h-32 bg-gray-300 rounded-full flex items-center justify-center">
                        <i class="fa-solid fa-user text-white text-3xl"></i>
                    </div>
                @endif
                </div>
                <div>
                    <label for="profilepicture" class="block text-lg font-semibold font-montserrat">Upload Image</label>
                    <input type="file" id="profilepicture" name="profilepicture" class="w-full p-2 border border-gray-300 rounded-md mt-2" onchange="previewImage(event)">
                    @error('profilepicture')
                        <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Form fields for name, username, email, password (same as before) -->

            <div class="mt-6 text-center">
                <button type="submit" class="bg-ultraViolet hover:bg-cambridgeBlue text-white px-6 py-2 rounded-md hover:text-darkSlateGrey border-ultraViolet hover:border-cambridgeBlue font-montserrat font-bold transition-all duration-200">Save Changes</button>
            </div>
        </form>

        <!-- Delete Account Form -->
        <div class="mt-6 text-center">
        <form action="{{ route('user.delete', ['id' => $user->id]) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-md font-montserrat font-bold transition-all duration-200">
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
