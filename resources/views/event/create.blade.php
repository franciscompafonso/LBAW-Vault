@extends('layouts.app')

@section('Title', 'Create Event')
@section('content')
<div class="flex items-center justify-center p-6">
  <div class="w-1/2">
    <div class="mb-6 text-center">
      <!-- Page Title -->
      <h2 class="text-3xl font-bold text-ultraViolet font-montserrat">Create Event</h2>
    </div>

    @can('create', App\Models\Event::class)
    <!-- Form Container -->
    <form method="POST" action="{{ route('events.store') }}" class="space-y-8" enctype="multipart/form-data">
      @csrf
      <fieldset class="p-4 border border-gray-300 rounded-md">
        <legend class="text-lg font-semibold font-montserrat">Event Details</legend>
          <!-- Banner -->
          <div class="flex items-center mb-4 space-x-4">
            <label for="banner" class="block text-lg font-semibold font-montserrat">Upload Image</label>
            <input type="file" id="banner" name="file" class="w-full p-2 mt-2 border border-gray-300 rounded-md" aria-label="Upload an image to represent your event. Accepted formats: JPEG, PNG.">
            @error('file')
                <div class="mt-2 text-sm text-red-600" aria-live="polite">{{ $message }}</div>
            @enderror
          </div>

          <!-- Event Name -->
          <div class="mb-4">
            <label for="name" class="block text-lg font-semibold font-montserrat">Event Name <span class="text-red-600">*</span></label>
              <input id="name" type="text" maxlength="70" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" class="w-full p-2 border border-gray-300 rounded-md font-montserrat" aria-describedby="name-error">
                @if ($errors->has('name'))
                  <span id="name-error" class="mt-1 text-sm text-red-600" aria-live="polite">{{ $errors->first('name') }}</span>
                @endif
          </div>

          <!-- Description -->
          <div class="mb-4">
            <label for="description" class="block text-lg font-semibold font-montserrat">Description <span class="text-red-600">*</span></label>
              <textarea id="description" maxlength="450" name="description" required class="w-full p-2 border border-gray-300 rounded-md font-montserrat" aria-describedby="description-error">{{ old('description') }}</textarea>
                @if ($errors->has('description'))
                  <span id="description-error" class="mt-1 text-sm text-red-600" aria-live="polite">{{ $errors->first('description') }}</span>
                @endif
          </div>
      </fieldset>
      <fieldset class="p-4 border border-gray-300 rounded-md">
        <legend class="text-lg font-semibold font-montserrat">Date and Time</legend>
          <!-- Start Date and Time -->
          <div class="mb-4">
            <label for="start_date" class="block text-lg font-semibold font-montserrat">Start Date & Time <span class="text-red-600">*</span></label>
            <input id="start_date" type="datetime-local" name="start_date" value="{{ old('start_date') }}" required class="w-full p-2 border border-gray-300 rounded-md font-montserrat" aria-describedby="start_date-error">
            <style>
              input[type="datetime-local"]::-webkit-calendar-picker-indicator {
                filter: invert(1) grayscale(1) contrast(2);
              }
            </style>
            @if ($errors->has('start_date'))
              <span id="start_date-error" class="mt-1 text-sm text-red-600" aria-live="polite">{{ $errors->first('start_date') }}</span>
            @endif
          </div>

          <!-- End Date and Time -->
          <div class="mb-4">
            <label for="end_date" class="block text-lg font-semibold font-montserrat">End Date & Time <span class="text-red-600">*</span></label>
            <input id="end_date" type="datetime-local" name="end_date" value="{{ old('end_date') }}" required class="w-full p-2 border border-gray-300 rounded-md font-montserrat" aria-describedby="end-date-error">
            <style>
              input[type="datetime-local"]::-webkit-calendar-picker-indicator {
                filter: invert(1) grayscale(1) contrast(2);
              }
            </style>
            @if ($errors->has('end_date'))
              <span id="end-date-error" class="mt-1 text-sm text-red-600" aria-live="polite">{{ $errors->first('end_date') }}</span>
            @endif
          </div>
      </fieldset>          
        
      <fieldset class="p-4 border border-gray-300 rounded-md">
        <legend class="text-lg font-semibold font-montserrat">Location</legend>
          <!-- District Selector -->
          <div class="mb-4">
            <label for="district_id" class="block text-lg font-semibold font-montserrat">District <span class="text-red-600">*</span></label>
            <select id="district_id" name="district_id" required class="block px-2 text-lg font-semibold font-montserrat" aria-label="Select the district for your event" aria-describedby="district_id-error">
              <option value="">Select a District</option>
              @foreach($districts as $district)
                <option value="{{ $district->id }}" {{ old('district_id') == $district->id ? 'selected' : '' }}>{{ $district->name }}</option>
              @endforeach
            </select>
            @if ($errors->has('district_id'))
              <span id="district_id-error" class="mt-1 text-sm text-red-600" aria-live="polite">{{ $errors->first('district_id') }}</span>
            @endif
          </div>

          <!-- Location -->
          <div class="mb-4">
            <label for="location" class="block text-lg font-semibold font-montserrat">Location <span class="text-red-600">*</span></label>
            <textarea id="location" maxlength="100" name="location" class="w-full p-2 border border-gray-300 rounded-md font-montserrat">{{ old('location') }}</textarea>
            @if ($errors->has('location'))
              <span id="location-error" class="mt-1 text-sm text-red-600" aria-live="polite">{{ $errors->first('location') }}</span>
            @endif
          </div>
      </fieldset>

      <fieldset class="p-4 border border-gray-300 rounded-md">
        <legend class="text-lg font-semibold font-montserrat">Additional Information</legend>
          <!-- Category Selector -->
          <div class="mb-4">
            <label for="category_id" class="block text-lg font-semibold font-montserrat">Category <span class="text-red-600">*</span></label>
            <select id="category_id" name="category_id" required class="block px-2 text-lg font-semibold font-montserrat" aria-label="Select the category for your event" aria-describedby="category-error">
              <option value="">Select a Category</option>
              @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
              @endforeach
            </select>
            @if ($errors->has('category_id'))
              <span id="category-error" class="mt-1 text-sm text-red-600" aria-live="polite">{{ $errors->first('category_id') }}</span>
            @endif
          </div>

          <!-- Visibility -->
          <div class="mb-4">
            <label for="visibility" class="block text-lg font-semibold font-montserrat">Visibility <span class="text-red-600">*</span></label>
            <select id="visibility" name="visibility" required class="block px-2 text-lg font-semibold font-montserrat" aria-label="Select the visibility level for your event" aria-describedby="visibility-error">
              <option value="public" {{ old('visibility') == 'public' ? 'selected' : '' }}>Public</option>
              <option value="request_only" {{ old('visibility') == 'request_only' ? 'selected' : '' }}>Request Only</option>
              <option value="private" {{ old('visibility') == 'private' ? 'selected' : '' }}>Private</option>
            </select>
            @if ($errors->has('visibility'))
              <span id="visibility-error" class="mt-1 text-sm text-red-600" aria-live="polite">{{ $errors->first('visibility') }}</span>
            @endif
          </div>

          <!-- Capacity -->
          <div class="mb-4">
            <label for="capacity" class="block text-lg font-semibold font-montserrat">Capacity <span class="text-red-600">*</span></label>
            <input id="capacity" type="number" max="1000" name="capacity" value="{{ old('capacity') }}" required class="block text-lg font-semibold font-montserrat" aria-describedby="capacity-error">
            @if ($errors->has('capacity'))
              <span id="capacity-error" class="mt-1 text-sm text-red-600" aria-live="polite">{{ $errors->first('capacity') }}</span>
            @endif
          </div>
      </fieldset>

      <!-- Submit Button -->
      <div class="mt-6 text-center">
        <button type="submit" class="px-6 py-2 font-bold text-white transition-all duration-200 rounded-md bg-ultraViolet hover:bg-cambridgeBlue hover:text-darkSlateGrey border-ultraViolet hover:border-cambridgeBlue font-montserrat" aria-label="Create Event">
          Create Event
        </button>
      </div>
    </form>
    @endcan
  </div>
</div>
@endsection
