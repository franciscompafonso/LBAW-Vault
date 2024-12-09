@extends('layouts.app')

@section('Title', 'Unblock Requests')
@section('content')
    <div class="container p-8 mx-auto">
        <h1 class="mb-6 text-5xl font-bold font-montserrat">Unblock Requests</h1>
        @if($requests->count())
            <table>
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Requested At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($requests as $request)
                        <tr>
                            <td>
                                <a href="{{ route('user.profile', ['id' => $request->requested_by_id]) }}" 
                                class="font-bold">
                                    {{ $request->requestedBy->username }}
                                </a>
                            </td>
                            <td>{{ $request->decision }}</td>
                            <td>
                                <span class="px-4 py-2 font-bold rounded-md text-white 
                                    {{ $request->status === 'pending' ? 'bg-yellow-500' : ($request->status === 'approved' ? 'bg-green-500' : 'bg-red-500') }}">
                                    {{ ucfirst($request->status) }}
                                </span>
                            </td>
                            <td>{{ $request->requested_at->format('d-m-Y H:i') }}</td>
                            <td class="flex space-x-2">
                                @if($request->status === 'pending')
                                    <!-- Approve Button -->
                                    <form action="{{ route('admin.unblock.requests.approve', $request->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-6 py-2 font-bold text-white transition-all duration-200 rounded-md font-montserrat bg-green-600 hover:bg-green-700">
                                            Approve
                                        </button>
                                    </form>
                                    
                                    <!-- Reject Button -->
                                    <form action="{{ route('admin.unblock.requests.reject', $request->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-6 py-2 font-bold text-white transition-all duration-200 rounded-md font-montserrat bg-red-600 hover:bg-red-700">
                                            Reject
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-500 italic">No actions available</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination links -->
            <div class="mt-4 pagination">
                {{ $requests->links() }}
            </div>
        @else
            <p>No unblock requests found.</p>
        @endif
    </div>
@endsection
