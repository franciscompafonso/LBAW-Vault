@extends('layouts.app')

@section('Title', 'User Management')
@section('content')
    <script src="{{ url('js/admin/user-management.js') }}" defer></script>
    <div class="container p-8 mx-auto">
        <a href="/admin/user-management"><h1 class="mb-6 text-5xl font-bold text-ultraViolet font-montserrat">User Management</h1></a>

        <!-- Search Form -->
        <form action="{{ route('admin.search.user') }}" method="GET" class="mb-6">
            <div class="flex">
                <input 
                    type="text" 
                    name="username" 
                    maxlength="25"
                    class="w-full p-3 border rounded-l-lg font-montserrat focus:outline-none"
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
                        <th class="font-bold font-montserrat">Username</th>
                        <th class="font-bold font-montserrat">Email</th>
                        <th class="font-bold font-montserrat">Status</th>
                        <th class="font-bold font-montserrat">Block</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>
                                <a href="{{ route('user.profile', ['username' => $user->username]) }}" 
                                class="font-semibold"
                                aria-label="View profile of {{ $user->username }}">
                                    {{ $user->username }}
                                </a>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->isdeleted)
                                    <!-- Disabled DELETED Button -->
                                    <button type="button" class="px-6 py-2 font-bold text-white transition-all duration-200 bg-red-600 rounded-md cursor-not-allowed font-montserrat" disabled>
                                        DELETED
                                    </button>
                                @else
                                    @can('manageUsers', App\Models\Admin::class)
                                    <!-- Delete Button -->
                                        <form action="{{ route('admin.delete.user', $user->username) }}" method="POST" aria-label="Delete user {{ $user->username }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-6 py-2 font-bold text-white transition-all duration-200 rounded-md delete-btn font-montserrat bg-ultraViolet hover:bg-red-600 hover:text-white border-ultraViolet hover:border-red-600">
                                                <span class="hidden loading-icon" id="loading-{{ $user->id }}">⏳</span> 
                                                Delete
                                            </button>
                                        </form>
                                    @endcan
                                @endif
                            </td>
                            <td>
                                @if($user->isblocked)
                                    @can('manageUsers', App\Models\Admin::class)
                                        <form action="{{ route('admin.unblock.user', $user->username) }}" method="POST" aria-label="Unblock user {{ $user->username }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="px-6 py-2 font-bold text-white transition-all duration-200 rounded-md bg-ultraViolet border-ultraViolet font-montserrat hover:bg-green-500 hover:text-darkSlateGrey hover:border-green-500" aria-label="Unblock User">
                                                <span class="hidden loading-icon" id="unblock-loading-{{ $user->id }}">⏳</span>
                                                Unblock
                                            </button>
                                        </form>
                                    @endcan
                                @else
                                    @can('manageUsers', App\Models\Admin::class)
                                        <form action="{{ route('admin.block.user', $user->username) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-6 py-2 font-bold text-white transition-all duration-200 rounded-md bg-ultraViolet border-ultraViolet font-montserrat hover:bg-red-700 hover:text-white hover:border-red-700" aria-label="Block User">
                                                <span class="hidden loading-icon" id="block-loading-{{ $user->id }}">⏳</span>
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
@endsection
