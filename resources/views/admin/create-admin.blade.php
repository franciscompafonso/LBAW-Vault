@extends('layouts.app')

@section('Title', 'Create Admin')
@section('content')
    <script src="{{ url('js/admin/create.js') }}" defer></script>
    <div class="container mx-auto mt-8">
        
        @if (session('message'))
            <div class="px-4 py-2 mb-6 text-white bg-green-500 rounded-md">
                {{ session('message') }}
            </div>
        @endif

        <!-- Flex Container to Hold Form and Admin List Side by Side -->
        <div class="flex space-x-8">
            
            <!-- Admin Creation Form -->
            <div class="w-1/2 p-6 bg-white rounded-lg shadow-md">
                <h2 class="py-5 mb-4 text-3xl text-ultraViolet font-montserrat">Create New Admin</h2>

                <form action="{{ route('admin.admins.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="email" class="block mb-1 text-ultraViolet font-montserrat">Email <span class="text-red-600">*</span></label>
                        <input type="email" maxlength="255" id="email" name="email" value="{{ old('email') }}" required 
                               class="w-full p-2 border border-gray-300 rounded-lg">
                        @error('email')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password" class="block mb-1 text-ultraViolet font-montserrat">Password <span class="text-red-600">*</span></label>
                        <input type="password" maxlength="255" id="password" name="password" required 
                               class="w-full p-2 border border-gray-300 rounded-lg">
                        @error('password')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="block mb-1 text-ultraViolet font-montserrat">Confirm Password <span class="text-red-600">*</span></label>
                        <input type="password" maxlength="255" id="password_confirmation" name="password_confirmation" required 
                               class="w-full p-2 border border-gray-300 rounded-lg">
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-2 font-bold text-white transition-all duration-200 rounded-md font-montserrat bg-ultraViolet hover:bg-cambridgeBlue hover:text-darkSlateGrey border-ultraViolet hover:border-cambridgeBlue active:border-cambridgeBlue" aria-label="Create New Admin">
                            <span id="loading-icon" class="hidden">⏳</span> Create Admin
                        </button>
                    </div>
                </form>
            </div>

            <!-- Admin List -->
            <div class="w-1/2 p-6 bg-white rounded-lg shadow-md">
                <h2 class="py-5 mb-4 text-3xl text-ultraViolet font-montserrat">Admin List</h2>
                
                @if($admins->count())
                    <table class="w-full border-collapse">
                        <thead>
                            <tr>
                                <th class="p-4 text-left border-b-2 text-ultraViolet font-montserrat">Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($admins as $admin)
                                <tr>
                                    <td class="p-4 border-b font-montserrat">{{ $admin->email }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Pagination links -->
                    <div class="mt-4 pagination" role="navigation" aria-label="Pagination">
                        {{ $admins->links() }}
                    </div>
                @else
                    <p class="text-gray-600">No admins found.</p>
                @endif
            </div>
        </div>
    </div>
@endsection