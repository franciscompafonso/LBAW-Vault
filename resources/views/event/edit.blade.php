@extends('layouts.app')

@section('Title', 'Edit Event')
@section('content')
<section id="edit-event" class="max-w-4xl p-8 mx-auto mt-10 rounded-lg shadow-xl bg-whiteSmoke">
  <div class="flex flex-col items-center justify-center">
    <!-- Page Title -->
    <h2 class="mb-10 text-4xl font-bold text-eerieBlack">Edit Event</h2>
  </div>

  <!-- Form Container -->
  <div class="p-8 rounded-lg shadow-lg bg-eerieBlack">
    <form method="POST" action="{{ route('events.update', $event->id) }}" class="space-y-8">
      @csrf
      @method('PUT') <!-- Add this to indicate the update method -->

      <!-- Event Name -->
      <div>
        <label for="name" class="block text-lg font-semibold text-whiteSmoke">Event Name</label>
        <div class="mt-2">
          <input id="name" type="text" name="name" value="{{ old('name', $event->name) }}" required autofocus autocomplete="name" class="block w-full px-4 py-3 text-white border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-keppel focus:border-keppel placeholder-charcoal sm:text-base">
          @if ($errors->has('name'))
            <span class="mt-1 text-sm text-red-600">{{ $errors->first('name') }}</span>
          @endif
        </div>
      </div>

      <!-- Description -->
      <div>
        <label for="description" class="block text-lg font-semibold text-whiteSmoke">Description</label>
        <div class="mt-2">
          <textarea id="description" name="description" required class="block w-full px-4 py-3 text-white border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-keppel focus:border-keppel placeholder-charcoal sm:text-base">{{ old('description', $event->description) }}</textarea>
          @if ($errors->has('description'))
            <span class="mt-1 text-sm text-red-600">{{ $errors->first('description') }}</span>
          @endif
        </div>
      </div>

      <!-- Start Date and Time -->
      <div>
        <label for="start_date" class="block text-lg font-semibold text-whiteSmoke">Start Date & Time</label>
        <div class="relative mt-2">
          <input id="start_date" type="datetime-local" name="start_date" value="{{ old('start_date', $event->start_date) }}" required class="block w-full px-4 py-3 text-white border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-keppel focus:border-keppel placeholder-charcoal sm:text-base">
          <style>
            input[type="datetime-local"]::-webkit-calendar-picker-indicator {
              filter: invert(1) grayscale(1) contrast(2);
            }
          </style>
        </div>
        @if ($errors->has('start_date'))
          <span class="mt-1 text-sm text-red-600">{{ $errors->first('start_date') }}</span>
        @endif
      </div>

      <!-- End Date and Time -->
      <div>
        <label for="end_date" class="block text-lg font-semibold text-whiteSmoke">End Date & Time</label>
        <div class="relative mt-2">
          <input id="end_date" type="datetime-local" name="end_date" value="{{ old('end_date', $event->end_date) }}" required class="block w-full px-4 py-3 text-white border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-keppel focus:border-keppel placeholder-charcoal sm:text-base">
          <style>
            input[type="datetime-local"]::-webkit-calendar-picker-indicator {
              filter: invert(1) grayscale(1) contrast(2);
            }
          </style>
        </div>
        @if ($errors->has('end_date'))
          <span class="mt-1 text-sm text-red-600">{{ $errors->first('end_date') }}</span>
        @endif
      </div>

      <!-- Location Selector -->
      <div>
        <label for="location_id" class="block text-lg font-semibold text-whiteSmoke">Location</label>
        <div class="mt-2">
          <select id="location_id" name="location_id" required class="block w-full px-4 py-3 text-white border-gray-300 rounded-md shadow-sm bg-eerieBlack focus:ring-2 focus:ring-keppel sm:text-base">
            <option value="">Select a Location</option>
            @foreach($locations as $location)
              <option value="{{ $location->id }}" {{ old('location_id', $event->location_id) == $location->id ? 'selected' : '' }}>{{ $location->name }}</option>
            @endforeach
          </select>
          @if ($errors->has('location_id'))
            <span class="mt-1 text-sm text-red-600">{{ $errors->first('location_id') }}</span>
          @endif
        </div>
      </div>

      <!-- Category Selector -->
      <div>
        <label for="category_id" class="block text-lg font-semibold text-whiteSmoke">Category</label>
        <div class="mt-2">
          <select id="category_id" name="category_id" required class="block w-full px-4 py-3 text-white border-gray-300 rounded-md shadow-sm bg-eerieBlack focus:ring-2 focus:ring-keppel sm:text-base">
            <option value="">Select a Category</option>
            @foreach($categories as $category)
              <option value="{{ $category->id }}" {{ old('category_id', $event->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
            @endforeach
          </select>
          @if ($errors->has('category_id'))
            <span class="mt-1 text-sm text-red-600">{{ $errors->first('category_id') }}</span>
          @endif
        </div>
      </div>

      <!-- Visibility -->
      <div>
        <label for="visibility" class="block text-lg font-semibold text-whiteSmoke">Visibility</label>
        <div class="mt-2">
          <select id="visibility" name="visibility" required class="block w-full px-4 py-3 text-white border-gray-300 rounded-md shadow-sm bg-eerieBlack focus:ring-2 focus:ring-keppel sm:text-base">
            <option value="public" {{ old('visibility', $event->visibility) == 'public' ? 'selected' : '' }}>Public</option>
            <option value="request_only" {{ old('visibility', $event->visibility) == 'request_only' ? 'selected' : '' }}>Request Only</option>
            <option value="private" {{ old('visibility', $event->visibility) == 'private' ? 'selected' : '' }}>Private</option>
          </select>
          @if ($errors->has('visibility'))
            <span class="mt-1 text-sm text-red-600">{{ $errors->first('visibility') }}</span>
          @endif
        </div>
      </div>

      <!-- Capacity -->
      <div>
        <label for="capacity" class="block text-lg font-semibold text-whiteSmoke">Capacity</label>
        <div class="mt-2">
          <input id="capacity" type="number" name="capacity" value="{{ old('capacity', $event->capacity) }}" required class="block w-full px-4 py-3 text-white border-gray-300 rounded-md shadow-sm bg-eerieBlack focus:ring-2 focus:ring-keppel placeholder-charcoal sm:text-base">
          @if ($errors->has('capacity'))
            <span class="mt-1 text-sm text-red-600">{{ $errors->first('capacity') }}</span>
          @endif
        </div>
      </div>

      <!-- Submit Button -->
      <div class="pt-4">
        <button type="submit" class="flex justify-center w-full px-8 py-3 font-semibold text-white transition-colors duration-200 rounded-md shadow-lg bg-charcoal hover:bg-keppel">
          Update Event
        </button>
      </div>
    </form>
  </div>
</section>
@endsection
