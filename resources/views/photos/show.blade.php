@extends('layouts.app')

@section('content')
<div class="flex justify-center items-center min-h-screen bg-gray-100">
    <div class="bg-white p-4 rounded-lg shadow-lg">
        <div class="flex justify-end">
            <a href="{{ url()->previous() }}" class="text-red-500 hover:text-red-600">&times;</a>
        </div>
        <div class="max-w-full max-h-screen overflow-auto">
            <img src="{{ asset('images/others/' . $photo->path) }}" alt="Photo" class="w-full h-auto object-contain">
        </div>
    </div>
</div>
@endsection