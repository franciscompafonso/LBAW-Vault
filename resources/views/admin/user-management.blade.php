@extends('layouts.app')

@section('title', 'User Management')
    
@section('content')
    <div class="container mx-auto p-8">
        <h1 class="text-5xl font-montserrat font-bold mb-6">User Management</h1>

        <!-- Search Form -->
        <form action="{{ route('admin.search.user') }}" method="GET" class="mb-6">
            <div class="flex">
                <input 
                    type="text" 
                    name="email" 
                    class="w-full p-3 border rounded-l-lg focus:outline-none"
                    placeholder="Search by user email" 
                    value="{{ old('email', $search ?? '') }}">
                <button type="submit" class="font-montserrat bg-ultraViolet hover:bg-cambridgeBlue text-white px-6 py-2 rounded-md hover:text-darkSlateGrey border-ultraViolet hover:border-cambridgeBlue font-montserrat font-bold transition-all duration-200">
                    Search
                </button>
            </div>
            @error('email')
                <p class="text-red-500 mt-2">{{ $message }}</p>
            @enderror
        </form>

        @if($users->count())
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Delete</th>
                        <th>Block</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->displayName }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->isdeleted)
                                    <!-- Undelete Button -->
                                    <form action="{{ route('admin.undelete.user', $user->id) }}" method="POST" id="undelete-form-{{ $user->id }}" class="toggle-form">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="undelete-btn font-montserrat bg-ultraViolet hover:bg-cambridgeBlue text-white px-6 py-2 rounded-md hover:text-darkSlateGrey border-ultraViolet hover:border-cambridgeBlue font-montserrat font-bold transition-all duration-200" data-user-id="{{ $user->id }}">Undelete</button>
                                    </form>
                                @else
                                    <!-- Delete Button -->
                                    <form action="{{ route('admin.delete.user', $user->id) }}" method="POST" id="delete-form-{{ $user->id }}" class="toggle-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="delete-btn font-montserrat bg-ultraViolet hover:bg-cambridgeBlue text-white px-6 py-2 rounded-md hover:text-darkSlateGrey border-ultraViolet hover:border-cambridgeBlue font-montserrat font-bold transition-all duration-200" data-user-id="{{ $user->id }}">Delete</button>
                                    </form>
                                @endif
                            </td>
                            <td>
                                @if($user->isblocked)
                                    <form action="{{ route('admin.unblock.user', $user->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="unblock-btn font-montserrat bg-ultraViolet hover:bg-cambridgeBlue text-white px-6 py-2 rounded-md hover:text-darkSlateGrey border-ultraViolet hover:border-cambridgeBlue font-montserrat font-bold transition-all duration-200">
                                            Unblock
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.block.user', $user->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="block-btn font-montserrat bg-ultraViolet hover:bg-cambridgeBlue text-white px-6 py-2 rounded-md hover:text-darkSlateGrey border-ultraViolet hover:border-cambridgeBlue font-montserrat font-bold transition-all duration-200">Block</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination links -->
            <div class="pagination mt-4">
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
