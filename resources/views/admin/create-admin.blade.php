@extends('layouts.app')

@section('content')
    <div class="container mx-auto mt-8">
        
        @if (session('message'))
            <div class="bg-green-500 text-white px-4 py-2 rounded-md mb-6">
                {{ session('message') }}
            </div>
        @endif

        <!-- Flex Container to Hold Form and Admin List Side by Side -->
        <div class="flex space-x-8">
            
            <!-- Admin Creation Form -->
            <div class="w-1/2 bg-white shadow-md rounded-lg p-6">
                <h2 class="text-3xl font-montserrat mb-4">Create New Admin</h2>

                <form action="{{ route('admin.admins.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="email" class="block font-montserrat mb-1">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required 
                               class="w-full p-2 border border-gray-300 rounded-lg">
                        @error('email')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password" class="block font-montserrat mb-1">Password</label>
                        <input type="password" id="password" name="password" required 
                               class="w-full p-2 border border-gray-300 rounded-lg">
                        @error('password')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="block font-montserrat mb-1">Confirm Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required 
                               class="w-full p-2 border border-gray-300 rounded-lg">
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="font-montserrat bg-ultraViolet hover:bg-cambridgeBlue text-white px-6 py-2 rounded-md hover:text-darkSlateGrey border-ultraViolet hover:border-cambridgeBlue font-montserrat font-bold transition-all duration-200">
                            Create Admin
                        </button>
                    </div>
                </form>
            </div>

            <!-- Admin List -->
            <div class="w-1/2 bg-white shadow-md rounded-lg p-6">
                <h2 class="text-3xl font-montserrat mb-4">Admin List</h2>
                
                @if($admins->count())
                    <table class="w-full border-collapse">
                        <thead>
                            <tr>
                                <th class="border-b-2 p-4 font-montserrat text-left">Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($admins as $admin)
                                <tr>
                                    <td class="border-b p-4 font-montserrat">{{ $admin->email }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Pagination links -->
                    <div class="pagination mt-4">
                        {{ $admins->links() }}
                    </div>
                @else
                    <p class="text-gray-600">No admins found.</p>
                @endif
            </div>
        </div>
    </div>
@endsection
