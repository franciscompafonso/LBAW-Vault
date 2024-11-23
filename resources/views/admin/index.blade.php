<!-- resources/views/admin/users/index.blade.php -->

@extends('layouts.app')

@section('content')
    <h1>Users List</h1>

    @if($users->count())
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>isDeleted</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->displayName }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->isdeleted }}</td>
                        <td>
                            <!-- Add your action buttons (view, edit, delete) here -->
                            <form action="{{ route('admin.delete.user', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination links -->
        
        <p>No users found.</p>
    @endif
@endsection
