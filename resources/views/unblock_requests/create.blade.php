@extends('layouts.app')

@section('title', 'Unblock Request')

@section('content')
@can('sendUnblockRequest', auth()->user())
<div class="container mx-auto px-4 py-8">
    <h1 class="text-4xl font-bold text-center text-gray-800 mb-6 font-montserrat">
        Request Unblock
    </h1>
    
    <div class="max-w-3xl mx-auto bg-white shadow-md rounded-lg p-6">
        @if (session('success'))
            <div class="mb-4 text-green-600 font-medium font-montserrat" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4">
                <ul class="list-disc list-inside text-red-600 font-montserrat" role="alert">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('unblock.request.store') }}" method="POST">
            @csrf

            <!-- Motivo -->
            <div class="mb-6">
                <label for="reason" class="block text-lg font-medium text-gray-700 mb-2 font-montserrat">
                    Reason for Unblock
                </label>
                <textarea name="reason" id="reason" rows="4" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-ultraViolet focus:outline-none font-montserrat"
                    placeholder="Please provide a detailed reason why you should be unblocked."
                    required>{{ old('reason') }}</textarea>
            </div>

            <!-- Botão de Envio -->
            <div class="text-center">
                <button type="submit" aria-label="Submit Unblock Request"
                    class="px-6 py-2 bg-ultraViolet text-white font-bold text-lg rounded-md hover:bg-ultraViolet-dark transition duration-200 font-montserrat">
                    Submit Request
                </button>
            </div>
        </form>
    </div>
</div>
@endcan
@endsection
