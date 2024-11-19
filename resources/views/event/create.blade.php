
@extends('layouts.app')

@section('content')
<div class="flex min-h-screen flex-col justify-center bg-gray-50 px-6 py-12 lg:px-8">
  <div class="sm:mx-auto sm:w-full sm:max-w-md">
    <h2 class="mt-10 text-center text-3xl font-extrabold tracking-tight text-blue-500">Create Event</h2>
  </div>

  <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-md bg-white p-8 rounded-lg shadow-xl">
    <form method="POST" action="{{ route('events.store') }}" class="space-y-6">
      @csrf

      <!-- Event Name -->
      <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Event Name</label>
        <div class="mt-2">
          <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 py-2 px-3 text-gray-900 placeholder-gray-500 sm:text-base">
          @if ($errors->has('name'))
            <span class="text-sm text-red-600 mt-1">{{ $errors->first('name') }}</span>
          @endif
        </div>
      </div>

      <!-- Description -->
      <div>
        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
        <div class="mt-2">
          <textarea id="description" name="description" required class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 py-2 px-3 text-gray-900 placeholder-gray-500 sm:text-base">{{ old('description') }}</textarea>
          @if ($errors->has('description'))
            <span class="text-sm text-red-600 mt-1">{{ $errors->first('description') }}</span>
          @endif
        </div>
      </div>

      <!-- Start Date -->
      <div>
        <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
        <div class="mt-2">
          <input id="start_date" type="date" name="start_date" value="{{ old('start_date') }}" required class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 py-2 px-3 text-gray-900 placeholder-gray-500 sm:text-base">
          @if ($errors->has('start_date'))
            <span class="text-sm text-red-600 mt-1">{{ $errors->first('start_date') }}</span>
          @endif
        </div>
      </div>

      <!-- End Date -->
      <div>
        <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
        <div class="mt-2">
          <input id="end_date" type="date" name="end_date" value="{{ old('end_date') }}" required class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 py-2 px-3 text-gray-900 placeholder-gray-500 sm:text-base">
          @if ($errors->has('end_date'))
            <span class="text-sm text-red-600 mt-1">{{ $errors->first('end_date') }}</span>
          @endif
        </div>
      </div>

      <!-- Location Selector -->
      <div>
        <label for="location_id" class="block text-sm font-medium text-gray-700">Location</label>
        <div class="mt-2">
          <select id="location_id" name="location_id" required class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 py-2 px-3 text-gray-900 sm:text-base">
            <option value="">Select a Location</option>
            @foreach($locations as $location)
              <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>{{ $location->name }}</option>
            @endforeach
          </select>
          @if ($errors->has('location_id'))
            <span class="text-sm text-red-600 mt-1">{{ $errors->first('location_id') }}</span>
          @endif
        </div>
      </div>

      <!-- Category Selector -->
      <div>
        <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
        <div class="mt-2">
          <select id="category_id" name="category_id" required class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 py-2 px-3 text-gray-900 sm:text-base">
            <option value="">Select a Category</option>
            @foreach($categories as $category)
              <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
            @endforeach
          </select>
          @if ($errors->has('category_id'))
            <span class="text-sm text-red-600 mt-1">{{ $errors->first('category_id') }}</span>
          @endif
        </div>
      </div>

      <!-- Visibility -->
      <div>
        <label for="visibility" class="block text-sm font-medium text-gray-700">Visibility</label>
        <div class="mt-2">
          <select id="visibility" name="visibility" required class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 py-2 px-3 text-gray-900 sm:text-base">
            <option value="public" {{ old('visibility') == 'public' ? 'selected' : '' }}>Public</option>
            <option value="private" {{ old('visibility') == 'private' ? 'selected' : '' }}>Private</option>
          </select>
          @if ($errors->has('visibility'))
            <span class="text-sm text-red-600 mt-1">{{ $errors->first('visibility') }}</span>
          @endif
        </div>
      </div>

      <!-- Capacity -->
      <div>
        <label for="capacity" class="block text-sm font-medium text-gray-700">Capacity</label>
        <div class="mt-2">
          <input id="capacity" type="number" name="capacity" value="{{ old('capacity') }}" required class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 py-2 px-3 text-gray-900 placeholder-gray-500 sm:text-base">
          @if ($errors->has('capacity'))
            <span class="text-sm text-red-600 mt-1">{{ $errors->first('capacity') }}</span>
          @endif
        </div>
      </div>

      <!-- Submit Button -->
      <div>
        <button type="submit" class="w-full flex justify-center rounded-md bg-blue-500 px-4 py-2 text-base font-semibold text-white shadow-sm hover:bg-blue-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-500">
          Create Event
        </button>
        
      </div>
    </form>
  </div>
</div>
@endsection