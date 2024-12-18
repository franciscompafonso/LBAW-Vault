@extends('layouts.app')

@section('Title', 'User Management')
@section('content')
    <div class="container p-8 mx-auto">
        <h1 class="mb-6 text-5xl text-ultraViolet font-bold font-montserrat">User Management</h1>

        <!-- Search Form -->
        <form action="{{ route('admin.search.user') }}" method="GET" class="mb-6">
            <div class="flex">
                <input 
                    type="text" 
                    name="username" 
                    class="w-full p-3 border font-montserrat rounded-l-lg focus:outline-none"
                    placeholder="Search by the exact username" 
                    value="{{ old('username', $search ?? '') }}"
                    aria-label="Search by username">
                <button type="submit" class="px-6 py-2 font-bold text-white transition-all duration-200 rounded-md font-montserrat bg-ultraViolet hover:bg-cambridgeBlue hover:text-darkSlateGrey border-ultraViolet hover:border-cambridgeBlue">
                    Search
                </button>
            </div>
            @error('username')
                <p class="mt-2 text-red-500">{{ $message }}</p>
            @enderror
        </form>

        @if($users->count())
            <table aria-label="User Table">
                <thead>
                    <tr>
                        <th class="font-montserrat font-bold">Username</th>
                        <th class="font-montserrat font-bold">Email</th>
                        <th class="font-montserrat font-bold">Status</th>
                        <th class="font-montserrat font-bold">Block</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>
                                <a href="{{ route('user.profile', ['id' => $user->id]) }}" 
                                class="font-semibold"
                                aria-label="View profile of {{ $user->username }}">
                                    {{ $user->username }}
                                </a>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->isdeleted)
                                    <!-- Disabled DELETED Button -->
                                    <button type="button" class="px-6 py-2 font-bold text-white transition-all duration-200 rounded-md font-montserrat bg-red-600 cursor-not-allowed" disabled>
                                        DELETED
                                    </button>
                                @else
                                    @can('manageUsers', App\Models\Admin::class)
                                    <!-- Delete Button -->
                                        <form action="{{ route('admin.delete.user', $user->id) }}" method="POST" aria-label="Delete user {{ $user->username }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-6 py-2 font-bold text-white transition-all duration-200 rounded-md delete-btn font-montserrat bg-ultraViolet hover:bg-red-600 hover:text-white border-ultraViolet hover:border-red-600">
                                                <span class="loading-icon hidden" id="loading-{{ $user->id }}">⏳</span> 
                                                Delete
                                            </button>
                                        </form>
                                    @endcan
                                @endif
                            </td>
                            <td>
                                @if($user->isblocked)
                                    @can('manageUsers', App\Models\Admin::class)
                                        <form action="{{ route('admin.unblock.user', $user->id) }}" method="POST" aria-label="Unblock user {{ $user->username }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="px-6 py-2 font-bold text-white transition-all duration-200 rounded-md unblock-btn font-montserrat bg-ultraViolet hover:bg-cambridgeBlue hover:text-darkSlateGrey border-ultraViolet hover:border-cambridgeBlue">
                                                <span class="loading-icon hidden" id="unblock-loading-{{ $user->id }}">⏳</span>
                                                Unblock
                                            </button>
                                        </form>
                                    @endcan
                                @else
                                    @can('manageUsers', App\Models\Admin::class)
                                        <form action="{{ route('admin.block.user', $user->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-6 py-2 font-bold text-white transition-all duration-200 rounded-md block-btn font-montserrat bg-ultraViolet hover:bg-cambridgeBlue hover:text-darkSlateGrey border-ultraViolet hover:border-cambridgeBlue" aria-label="Block user {{ $user->username }}">
                                                <span class="loading-icon hidden" id="block-loading-{{ $user->id }}">⏳</span>
                                                Block
                                            </button>
                                        </form>
                                    @endcan
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination links -->
            <div class="mt-4 pagination" role="navigation" aria-label="Pagination">
                {{ $users->links() }}
            </div>
        @else
            <p>No users found.</p>
        @endif
    </div>

    <script>
        document.querySelectorAll('.delete-btn, .unblock-btn, .block-btn').forEach(function(button) {
            button.addEventListener('click', function() {
                let loadingIcon = button.querySelector('.loading-icon');
                if (loadingIcon) {
                    loadingIcon.classList.remove('hidden');
                }
            });
        });
    </script>
@endsection
