@extends('layouts.app')

@section('content')
<section id="create-event" class="max-w-4xl mx-auto p-8 bg-white shadow-xl rounded-lg mt-10">
  <div class="flex flex-col justify-center items-center">
    <!-- Page Title -->
    <h2 class="text-4xl font-bold text-blue-600 mb-10">Create Event</h2>
  </div>

  <!-- Form Container -->
  <div class="bg-gray-50 p-8 rounded-lg shadow-lg">
    <form method="POST" action="{{ route('events.store') }}" class="space-y-8">
      @csrf

      <!-- Event Name -->
      <div>
        <label for="name" class="block text-lg font-semibold text-gray-800">Event Name</label>
        <div class="mt-2">
          <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 py-3 px-4 text-gray-900 placeholder-gray-500 sm:text-base">
          @if ($errors->has('name'))
            <span class="text-sm text-red-600 mt-1">{{ $errors->first('name') }}</span>
          @endif
        </div>
      </div>

      <!-- Description -->
      <div>
        <label for="description" class="block text-lg font-semibold text-gray-800">Description</label>
        <div class="mt-2">
          <textarea id="description" name="description" required class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 py-3 px-4 text-gray-900 placeholder-gray-500 sm:text-base">{{ old('description') }}</textarea>
          @if ($errors->has('description'))
            <span class="text-sm text-red-600 mt-1">{{ $errors->first('description') }}</span>
          @endif
        </div>
      </div>

      <!-- Start Date and Time -->
      <div>
        <label for="start_date" class="block text-lg font-semibold text-gray-800">Start Date & Time</label>
        <div class="mt-2">
          <input id="start_date" type="datetime-local" name="start_date" value="{{ old('start_date') }}" required class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 py-3 px-4 text-gray-900 placeholder-gray-500 sm:text-base">
          @if ($errors->has('start_date'))
            <span class="text-sm text-red-600 mt-1">{{ $errors->first('start_date') }}</span>
          @endif
        </div>
      </div>

      <!-- End Date and Time -->
      <div>
        <label for="end_date" class="block text-lg font-semibold text-gray-800">End Date & Time</label>
        <div class="mt-2">
          <input id="end_date" type="datetime-local" name="end_date" value="{{ old('end_date') }}" required class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 py-3 px-4 text-gray-900 placeholder-gray-500 sm:text-base">
          @if ($errors->has('end_date'))
            <span class="text-sm text-red-600 mt-1">{{ $errors->first('end_date') }}</span>
          @endif
        </div>
      </div>

      <!-- Location Selector -->
      <div>
        <label for="location_id" class="block text-lg font-semibold text-gray-800">Location</label>
        <div class="mt-2">
          <select id="location_id" name="location_id" required class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 py-3 px-4 text-gray-900 sm:text-base">
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
        <label for="category_id" class="block text-lg font-semibold text-gray-800">Category</label>
        <div class="mt-2">
          <select id="category_id" name="category_id" required class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 py-3 px-4 text-gray-900 sm:text-base">
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
        <label for="visibility" class="block text-lg font-semibold text-gray-800">Visibility</label>
        <div class="mt-2">
          <select id="visibility" name="visibility" required class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 py-3 px-4 text-gray-900 sm:text-base">
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
        <label for="capacity" class="block text-lg font-semibold text-gray-800">Capacity</label>
        <div class="mt-2">
          <input id="capacity" type="number" name="capacity" value="{{ old('capacity') }}" required class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 py-3 px-4 text-gray-900 placeholder-gray-500 sm:text-base">
          @if ($errors->has('capacity'))
            <span class="text-sm text-red-600 mt-1">{{ $errors->first('capacity') }}</span>
          @endif
        </div>
      </div>

      <!-- Submit Button -->
      <div class="pt-4">
        <button type="submit" class="w-full flex justify-center rounded-md bg-blue-500 text-white px-8 py-3 rounded-md font-semibold shadow-lg hover:bg-blue-600 transition-colors duration-200">
          Create Event
        </button>
      </div>
    </form>
  </div>
</section>
@endsection
