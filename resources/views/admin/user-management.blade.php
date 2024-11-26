@extends('layouts.app')

@section('Title', 'Contact us')
@section('content')
    <div class="container p-8 mx-auto">
        <h1 class="mb-6 text-5xl font-bold font-montserrat">User Management</h1>

        <!-- Search Form -->
        <form action="{{ route('admin.search.user') }}" method="GET" class="mb-6">
            <div class="flex">
                <input 
                    type="text" 
                    name="username" 
                    class="w-full p-3 border rounded-l-lg focus:outline-none"
                    placeholder="Search by username" 
                    value="{{ old('username', $search ?? '') }}">
                <button type="submit" class="px-6 py-2 font-bold text-white transition-all duration-200 rounded-md font-montserrat bg-ultraViolet hover:bg-cambridgeBlue hover:text-darkSlateGrey border-ultraViolet hover:border-cambridgeBlue">
                    Search
                </button>
            </div>
            @error('username')
                <p class="mt-2 text-red-500">{{ $message }}</p>
            @enderror
        </form>

        @if($users->count())
            <table>
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Delete</th>
                        <th>Block</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>
                                <a href="{{ route('user.profile', ['id' => $user->id]) }}" 
                                class="font-bold">
                                    {{ $user->username }}
                                </a>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->isdeleted)
                                    <!-- Undelete Button -->
                                    <form action="{{ route('admin.undelete.user', $user->id) }}" method="POST" id="undelete-form-{{ $user->id }}" class="toggle-form">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="px-6 py-2 font-bold text-white transition-all duration-200 rounded-md undelete-btn font-montserrat bg-ultraViolet hover:bg-cambridgeBlue hover:text-darkSlateGrey border-ultraViolet hover:border-cambridgeBlue" data-user-id="{{ $user->id }}">Undelete</button>
                                    </form>
                                @else
                                    <!-- Delete Button -->
                                    <form action="{{ route('admin.delete.user', $user->id) }}" method="POST" id="delete-form-{{ $user->id }}" class="toggle-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-6 py-2 font-bold text-white transition-all duration-200 rounded-md delete-btn font-montserrat bg-ultraViolet hover:bg-cambridgeBlue hover:text-darkSlateGrey border-ultraViolet hover:border-cambridgeBlue" data-user-id="{{ $user->id }}">Delete</button>
                                    </form>
                                @endif
                            </td>
                            <td>
                                @if($user->isblocked)
                                    <form action="{{ route('admin.unblock.user', $user->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="px-6 py-2 font-bold text-white transition-all duration-200 rounded-md unblock-btn font-montserrat bg-ultraViolet hover:bg-cambridgeBlue hover:text-darkSlateGrey border-ultraViolet hover:border-cambridgeBlue">
                                            Unblock
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.block.user', $user->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-6 py-2 font-bold text-white transition-all duration-200 rounded-md block-btn font-montserrat bg-ultraViolet hover:bg-cambridgeBlue hover:text-darkSlateGrey border-ultraViolet hover:border-cambridgeBlue">Block</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination links -->
            <div class="mt-4 pagination">
                {{ $users->links() }}
            </div>
        @else
            <p>No users found.</p>
        @endif
    </div>
@endsection

@section('scripts')
    <script>
        // JavaScript to toggle the button states between Delete and Undelete
        document.querySelectorAll('.toggle-form').forEach(form => {
            form.addEventListener('submit', function(event) {
                event.preventDefault(); // Prevent form submission

                const userId = form.querySelector('button').getAttribute('data-user-id');
                
                // Toggle between Delete and Undelete button
                if (form.querySelector('button').textContent === 'Delete') {
                    // Show 'Undelete' button and handle form submission
                    document.querySelector(`#undelete-form-${userId}`).submit();
                } else {
                    // Show 'Delete' button and handle form submission
                    document.querySelector(`#delete-form-${userId}`).submit();
                }
            });
        });
    </script>
@endsection
