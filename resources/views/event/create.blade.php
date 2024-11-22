@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto mt-6 p-6 bg-white rounded-lg shadow-md">
  <div class="text-center mb-6">
    <!-- Page Title -->
    <h2 class="text-3xl font-bold text-ultraViolet font-montserrat">Create Event</h2>
  </div>

  <!-- Form Container -->
  <form method="POST" action="{{ route('events.store') }}" class="space-y-8">
    @csrf

    <!-- Event Name -->
    <div class="mb-4">
      <label for="name" class="block text-lg font-semibold font-montserrat">Event Name</label>
      <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" class="w-full p-2 border border-gray-300 rounded-md font-montserrat">
      @if ($errors->has('name'))
        <span class="text-sm text-red-600 mt-1">{{ $errors->first('name') }}</span>
      @endif
    </div>

    <!-- Description -->
    <div class="mb-4">
      <label for="description" class="block text-lg font-semibold font-montserrat">Description</label>
      <textarea id="description" name="description" required class="w-full p-2 border border-gray-300 rounded-md font-montserrat">{{ old('description') }}</textarea>
      @if ($errors->has('description'))
        <span class="text-sm text-red-600 mt-1">{{ $errors->first('description') }}</span>
      @endif
    </div>

    <!-- Start Date and Time -->
    <div class="mb-4">
      <label for="start_date" class="block text-lg font-semibold font-montserrat">Start Date & Time</label>
      <input id="start_date" type="datetime-local" name="start_date" value="{{ old('start_date') }}" required class="w-full p-2 border border-gray-300 rounded-md font-montserrat">
      <style>
        input[type="datetime-local"]::-webkit-calendar-picker-indicator {
          filter: invert(1) grayscale(1) contrast(2);
        }
      </style>
      @if ($errors->has('start_date'))
        <span class="text-sm text-red-600 mt-1">{{ $errors->first('start_date') }}</span>
      @endif
    </div>

    <!-- End Date and Time -->
    <div class="mb-4">
      <label for="end_date" class="block text-lg font-semibold font-montserrat">End Date & Time</label>
      <input id="end_date" type="datetime-local" name="end_date" value="{{ old('end_date') }}" required class="w-full p-2 border border-gray-300 rounded-md font-montserrat">
      <style>
        input[type="datetime-local"]::-webkit-calendar-picker-indicator {
          filter: invert(1) grayscale(1) contrast(2);
        }
      </style>
      @if ($errors->has('end_date'))
        <span class="text-sm text-red-600 mt-1">{{ $errors->first('end_date') }}</span>
      @endif
    </div>

    <!-- Location Selector -->
    <div class="mb-4">
      <label for="location_id" class="block text-lg font-semibold font-montserrat">Location</label>
      <select id="location_id" name="location_id" required class="px-2 block text-lg font-semibold font-montserrat">
        <option value="">Select a Location</option>
        @foreach($locations as $location)
          <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>{{ $location->name }}</option>
        @endforeach
      </select>
      @if ($errors->has('location_id'))
        <span class="text-sm text-red-600 mt-1">{{ $errors->first('location_id') }}</span>
      @endif
    </div>

    <!-- Category Selector -->
    <div class="mb-4">
      <label for="category_id" class="block text-lg font-semibold font-montserrat">Category</label>
      <select id="category_id" name="category_id" required class="px-2 block text-lg font-semibold font-montserrat">
        <option value="">Select a Category</option>
        @foreach($categories as $category)
          <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
        @endforeach
      </select>
      @if ($errors->has('category_id'))
        <span class="text-sm text-red-600 mt-1">{{ $errors->first('category_id') }}</span>
      @endif
    </div>

    <!-- Visibility -->
    <div class="mb-4">
      <label for="visibility" class="block text-lg font-semibold font-montserrat">Visibility</label>
      <select id="visibility" name="visibility" required class="px-2 block text-lg font-semibold font-montserrat">
        <option value="public" {{ old('visibility') == 'public' ? 'selected' : '' }}>Public</option>
        <option value="private" {{ old('visibility') == 'private' ? 'selected' : '' }}>Private</option>
      </select>
      @if ($errors->has('visibility'))
        <span class="text-sm text-red-600 mt-1">{{ $errors->first('visibility') }}</span>
      @endif
    </div>

    <!-- Capacity -->
    <div class="mb-4">
      <label for="capacity" class="block text-lg font-semibold font-montserrat">Capacity</label>
      <input id="capacity" type="number" name="capacity" value="{{ old('capacity') }}" required class="block w-full rounded-md bg-eerieBlack text-white border-gray-300 shadow-sm focus:ring-2 focus:ring-keppel py-3 px-4 placeholder-charcoal sm:text-base">
      @if ($errors->has('capacity'))
        <span class="text-sm text-red-600 mt-1">{{ $errors->first('capacity') }}</span>
      @endif
    </div>

    <!-- Submit Button -->
    <div class="mt-6 text-center">
      <button type="submit" class="bg-ultraViolet hover:bg-cambridgeBlue text-white px-6 py-2 rounded-md hover:text-darkSlateGrey border-ultraViolet hover:border-cambridgeBlue font-montserrat font-bold transition-all duration-200">
        Create Event
      </button>
    </div>
  </form>
</div>
@endsection
