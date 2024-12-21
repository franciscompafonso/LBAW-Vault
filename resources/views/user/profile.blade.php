@extends('layouts.app')

@section('Title', $user->name . "'s Profile")
@section('content')
<script src="{{ url('js/user/profile.js') }}" defer></script>
@if ($user->isblocked)
    <div class="p-4 mb-6 text-white bg-red-500 rounded-md">
        <p class="text-lg font-semibold text-center">
            This user is currently blocked. Some features may not be available.
        </p>
    </div>
@endif
<!-- Profile Section Container -->
<div class="relative flex flex-col lg:flex-row items-center lg:justify-between p-6 lg:p-16 rounded-lg shadow-lg bg-gradient-to-r from-whiteSmoke to-wisteria">
    <!-- Left: Profile Picture and Info -->
    <div class="flex flex-col lg:flex-row items-center space-y-6 lg:space-y-0 lg:space-x-6">
        <!-- Profile Picture -->
        <div class="w-24 h-24 lg:w-32 lg:h-32 overflow-hidden border-4 border-white rounded-full">
            <img src="{{ asset('images/profile-pictures/' . ($user->picture->path ?? App\Models\User::DEFAULT_PIC)) }}" 
                alt="{{ $user->username }}" 
                class="object-cover w-full h-full">
        </div>

        <!-- User Info -->
        <div class="text-center lg:text-left">
            <h1 class="text-3xl lg:text-6xl font-bold text-eerieBlack font-montserrat">{{ $user->name }}</h1>
            <p class="text-xl lg:text-3xl text-eerieBlack">{{ "@" . $user->username }}</p>
        </div>
    </div>

    <!-- Right: Action Buttons -->
    <div class="flex flex-col gap-4 mt-6 lg:mt-0 lg:justify-end">
        @if((Auth::id() === $user->id && !$user->isdeleted && !$user->isblocked) || auth()->guard('admin')->check())
            <!-- Edit Profile Button -->
            <a href="{{ route('user.edit.form', ['username' => $user->username]) }}" 
                aria-label="Edit profile of {{ $user->name }}"
                class="flex items-center justify-center px-8 py-4 w-full lg:w-auto text-2xl font-bold text-white transition-all duration-200 rounded-md font-montserrat bg-ultraViolet hover:bg-cambridgeBlue hover:text-ultraViolet">
                <i class="mr-2 text-lg lg:text-xl fa-solid fa-user-edit"></i> Edit Profile
            </a>
            
            <!-- Invitations Sent Button -->
            <a href="{{ route('user.invites', ['username' => $user->username]) }}" 
                aria-label="View invites sent by {{ $user->name }}"
                class="flex items-center justify-center px-8 py-4 w-full lg:w-auto text-2xl font-bold text-white transition-all duration-200 rounded-md font-montserrat bg-ultraViolet hover:bg-cambridgeBlue hover:text-ultraViolet">
                <i class="mr-2 text-lg lg:text-xl fa-solid fa-envelope"></i> Invites and Requests
            </a>
        @endif
    </div>
</div>


<!-- My Events -->
<div class="mt-8" id="my_events-container">
    <h3 class="py-6 mb-4 text-4xl font-semibold font-montserrat text-eerieBlack">My Events</h3>
    @include('partials.event_section', [
        'events' => $myEvents,
        'sectionId' => 'my_events',
    ])
</div>


<!-- ongoingEvents Section -->
<div class="mt-8" id="ongoing_events-container">
    <h3 class="py-6 mb-4 text-4xl font-semibold font-montserrat text-eerieBlack">Ongoing Events</h3>
    @include('partials.event_section', [
        'events' => $ongoingEvents,
        'sectionId' => 'ongoing_events',
    ])
</div>


<!-- participatedEvents Section -->
<div class="mt-8" id="participated_events-container">
    <h3 class="py-6 mb-4 text-4xl font-semibold font-montserrat text-eerieBlack">Past Events</h3>
    @include('partials.event_section', [
        'events' => $participatedEvents,
        'sectionId' => 'participated_events',
    ])
</div>

<!-- Not Yet Participated Events Section -->
<div class="mt-8" id="not_yet_participated_events-container">
    <h3 class="py-6 mb-4 text-4xl font-semibold font-montserrat text-eerieBlack">Future Events</h3>
    @include('partials.event_section', [
        'events' => $notYetParticipatedEvents,
        'sectionId' => 'not_yet_participated_events',
    ])
</div>

@endsection






