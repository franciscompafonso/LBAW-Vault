@extends('layouts.app')

@section('Title', 'Edit Event')
@section('content')
<div class="flex items-center justify-center p-6">
  <div class="w-1/2">
      <div class="mb-6 text-center">
      <!-- Page Title -->
      <h2 class="text-3xl font-bold text-ultraViolet font-montserrat">Edit Event</h2>
    </div>
    @can('update', $event)
      <!-- Form Container -->
      <form method="POST" action="{{ route('events.update', $event->id) }}" class="space-y-8" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <fieldset class="p-4 border border-gray-300 rounded-md">
          <legend class="text-lg font-semibold font-montserrat">Event Details</legend>
            <!-- Banner Input -->
            <div class="flex items-center mb-4 space-x-4">
              <label for="banner" class="block text-lg font-semibold font-montserrat">Upload Image</label>
              <input type="file" id="banner" name="file" class="w-full p-2 mt-2 border border-gray-300 rounded-md" >
              @error('file')
                  <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
              @enderror
            </div>

              <!-- Event Name -->
              <div class="mb-4">
                <label for="name" class="block text-lg font-semibold font-montserrat">Event Name</label>
                <input id="name" type="text" maxlength="70" name="name" value="{{ old('name', $event->name) }}" required autofocus autocomplete="name" class="w-full p-2 border border-gray-300 rounded-md font-montserrat" aria-describedby="name-error">
                @if ($errors->has('name'))
                  <span id="name-error" class="mt-1 text-sm text-red-600">{{ $errors->first('name') }}</span>
                @endif
              </div>

              <!-- Description -->
              <div class="mb-4">
                <label for="description" class="block text-lg font-semibold font-montserrat" aria-describedby="description-error">Description</label>
                <textarea id="description" maxlength="450" name="description" required class="w-full p-2 border border-gray-300 rounded-md font-montserrat">{{ old('description', $event->description) }}</textarea>
                @if ($errors->has('description'))
                  <span id="description-error" class="mt-1 text-sm text-red-600">{{ $errors->first('description') }}</span>
                @endif
              </div>
        </fieldset>

        <fieldset class="p-4 border border-gray-300 rounded-md">
          <legend class="text-lg font-semibold font-montserrat">Date and Time</legend>
            <!-- Start Date and Time -->
            <div class="mb-4">
              <label for="start_date" class="block text-lg font-semibold font-montserrat">Start Date & Time</label>
              <input id="start_date" type="datetime-local" name="start_date" value="{{ old('start_date', $event->start_date) }}" required class="w-full p-2 border border-gray-300 rounded-md font-montserrat" aria-describedby="start_date-error">
              <style>
                input[type="datetime-local"]::-webkit-calendar-picker-indicator {
                  filter: invert(1) grayscale(1) contrast(2);
                }
              </style>
              @if ($errors->has('start_date'))
                <span id="start_date-error" class="mt-1 text-sm text-red-600">{{ $errors->first('start_date') }}</span>
              @endif
            </div>

            <!-- End Date and Time -->
            <div class="mb-4">
              <label for="end_date" class="block text-lg font-semibold font-montserrat">End Date & Time</label>
              <input id="end_date" type="datetime-local" name="end_date" value="{{ old('end_date', $event->end_date) }}" required class="w-full p-2 border border-gray-300 rounded-md font-montserrat" aria-describedby="end_date-error">
              <style>
                input[type="datetime-local"]::-webkit-calendar-picker-indicator {
                  filter: invert(1) grayscale(1) contrast(2);
                }
              </style>
              @if ($errors->has('end_date'))
                <span id="end_date-error" class="mt-1 text-sm text-red-600">{{ $errors->first('end_date') }}</span>
              @endif
            </div>
        </fieldset>

        <fieldset class="p-4 border border-gray-300 rounded-md">
          <legend class="text-lg font-semibold font-montserrat">Location</legend>
            <!-- District Selector -->
            <div class="mb-4">
              <label for="district_id" class="block text-lg font-semibold font-montserrat">District</label>
              <select id="district_id" name="district_id" required class="block px-2 text-lg font-semibold font-montserrat" aria-describedby="district_id-error">
                <option value="">Select a District</option>
                @foreach($districts as $district)
                  <option value="{{ $district->id }}" {{ old('district_id', $event->district_id) == $district->id ? 'selected' : '' }}>{{ $district->name }}</option>
                @endforeach
              </select>
              @if ($errors->has('district_id'))
                <span id="district_id-error" class="mt-1 text-sm text-red-600">{{ $errors->first('district_id') }}</span>
              @endif
            </div>

            <!-- Location -->
            <div class="mb-4">
              <label for="location" class="block text-lg font-semibold font-montserrat">Location</label>
              <textarea id="location" maxlength="100" name="location" class="w-full p-2 border border-gray-300 rounded-md font-montserrat" aria-describedby="location_id-error">{{ old('location', $event->location) }}</textarea>
              @if ($errors->has('location'))
                <span id="location_id-error" class="mt-1 text-sm text-red-600">{{ $errors->first('location') }}</span>
              @endif
            </div>
        </fieldset>

        <fieldset class="p-4 border border-gray-300 rounded-md">
          <legend class="text-lg font-semibold font-montserrat">Additional Information</legend>
            <!-- Category Selector -->
            <div class="mb-4">
              <label for="category_id" class="block text-lg font-semibold font-montserrat">Category</label>
              <select id="category_id" name="category_id" required class="block px-2 text-lg font-semibold font-montserrat" aria-describedby="category_id-error">
                <option value="">Select a Category</option>
                @foreach($categories as $category)
                  <option value="{{ $category->id }}" {{ old('category_id', $event->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
              </select>
              @if ($errors->has('category_id'))
                <span id="category_id-error" class="mt-1 text-sm text-red-600">{{ $errors->first('category_id') }}</span>
              @endif
            </div>

            <!-- Visibility -->
            <div class="mb-4">
              <label for="visibility" class="block text-lg font-semibold font-montserrat">Visibility</label>
              <select id="visibility" name="visibility" required class="block px-2 text-lg font-semibold font-montserrat" aria-describedby="visibility-error">
                <option value="public" {{ old('visibility', $event->visibility) == 'public' ? 'selected' : '' }}>Public</option>
                <option value="request_only" {{ old('visibility', $event->visibility) == 'request_only' ? 'selected' : '' }}>Request Only</option>
                <option value="private" {{ old('visibility', $event->visibility) == 'private' ? 'selected' : '' }}>Private</option>
              </select>
              @if ($errors->has('visibility'))
                <span id="visibility-error" class="mt-1 text-sm text-red-600">{{ $errors->first('visibility') }}</span>
              @endif
            </div>

            <!-- Capacity -->
            <div class="mb-4">
              <label for="capacity" class="block text-lg font-semibold font-montserrat">Capacity</label>
              <input id="capacity" type="number" max="1000" name="capacity" value="{{ old('capacity', $event->capacity) }}" required class="w-full p-2 border border-gray-300 rounded-md font-montserrat" aria-describedby="capacity-error">
              @if ($errors->has('capacity'))
                <span id="capacity-error" class="mt-1 text-sm text-red-600">{{ $errors->first('capacity') }}</span>
              @endif
            </div>
        </fieldset>
        
        <!-- Submit Button -->
        <div class="mt-6 text-center">
          <button type="submit" aria-label="Update Event" class="px-6 py-2 font-bold text-white transition-all duration-200 rounded-md bg-ultraViolet hover:bg-cambridgeBlue hover:text-darkSlateGrey border-ultraViolet hover:border-cambridgeBlue font-montserrat">
            Update Event
          </button>
        </div>
      </form>
    @endcan
  </div>
</div>
@endsection
