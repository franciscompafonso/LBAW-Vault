@extends('layouts.app')

@section('Title', 'Picture')
@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="p-4 bg-white rounded-lg shadow-lg">
        <div class="flex justify-end">
            <a href="{{ url()->previous() }}" class="text-red-500 hover:text-red-600" aria-label="Close image view">&times;</a>
        </div>
        <div class="max-w-full max-h-screen overflow-auto">
            <img src="{{ asset('images/others/' . $photo->path) }}" alt="Photo" class="object-contain w-full h-auto">
        </div>
    </div>
</div>
@endsection