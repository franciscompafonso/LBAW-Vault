@extends('layouts.app')

@section('Title', 'Profile')
@section('content')
    <div class="p-6 mx-auto mt-6 bg-white rounded-lg shadow-md max-w-10xl">
        <div class="mb-6 text-center">
            <h2 class="text-3xl font-bold text-ultraViolet font-montserrat">User Information</h2>
        </div>
    
        <!-- Profile Picture Section -->
        <div class="flex justify-center mb-6">
            @if ($user->isdeleted)
                <!-- Show a "Deleted" placeholder picture if the user is deleted -->
                <div class="flex items-center justify-center w-32 h-32 bg-gray-300 rounded-full">
                    <p class="text-xl font-bold text-white">Deleted</p>
                </div>
            @elseif ($user->profilepicture)
                <img src="{{ asset('images/profile-pictures/' . $user->profilepicture) }}" alt="Profile Picture" class="w-32 h-32 border-2 rounded-full border-darkSlateGrey">
            @else
                <div class="flex items-center justify-center w-32 h-32 bg-gray-300 rounded-full">
                    <i class="text-3xl text-white fa-solid fa-user"></i>
                </div>
            @endif
        </div>

        <!-- User Info Section -->
        <div class="text-center">
            @if ($user->isdeleted)
                <!-- Display only a placeholder username if the user is deleted -->
                <div class="flex items-center justify-center space-x-2">
                    <p class="text-2xl font-montserrat">{{ $user->displayName }}</p>
                </div>
            @else
                <!-- Display user info if not deleted -->
                <div class="flex items-center justify-center space-x-2">
                    <p class="text-2xl font-semibold font-montserrat">Name:</p>
                    <p class="text-2xl font-montserrat">{{ $user->name }}</p>
                </div>
                <div class="flex items-center justify-center space-x-2">
                    <p class="text-2xl font-semibold font-montserrat">Username:</p>
                    <p class="text-2xl font-montserrat">{{ $user->displayName }}</p>
                </div>
                <div class="flex items-center justify-center space-x-2">
                    <p class="text-2xl font-semibold font-montserrat">Email:</p>
                    <p class="text-2xl font-montserrat">{{ $user->email }}</p>
                </div>
            @endif
        </div>

        <!-- Edit Profile Link -->
        @if((Auth::id() === $user->id && !$user->isdeleted) || auth()->guard('admin')->check())
            <div class="flex flex-col items-center px-4 py-8 space-y-6">
                <a href="{{ auth()->guard('admin')->check() ? route('admin.edit.form', ['id' => $user->id]) : route('user.edit.form', ['id' => $user->id]) }}" 
                class="w-full max-w-md py-3 text-2xl font-bold text-center transition-all duration-200 rounded-lg shadow-md bg-ultraViolet hover:bg-cambridgeBlue text-whiteSmoke hover:text-darkSlateGrey font-montserrat">
                    Edit Profile
                </a>
            </div>
        @endif

        <!-- My Events -->
        <div class="mt-8">
            @if (!$user->isdeleted)
                <h3 class="mb-4 text-2xl font-bold text-ultraViolet">My Events</h3>
                @if (Auth::id() == $user->id && $participatedEvents->isEmpty())
                    <p class="text-gray-500">You have not created any events yet.</p>
                @elseif (Auth::id() != $user->id && $participatedEvents->isEmpty())
                    <p class="text-gray-500">The user has not created any events yet.</p>
                @elseif (Auth::id() == $user->id)
                    <section id="events" class="grid grid-cols-1 gap-10 p-4 md:grid-cols-2 lg:grid-cols-3">
                        @foreach ($myEvents as $event)
                            @include('partials.event', ['event' => $event])
                        @endforeach
                    </section>
                @else
                    <section id="events" class="grid grid-cols-1 gap-10 p-4 md:grid-cols-2 lg:grid-cols-3">
                        @foreach ($myEvents->filter(fn($event) => $event->visibility !== 'private') as $event)
                            @include('partials.event', ['event' => $event])
                        @endforeach
                    </section>
                @endif
            @endif
        </div>

        <!-- Ongoing Events Section -->
        <div class="mt-8">
            <h3 class="mb-4 text-2xl font-bold text-ultraViolet"> Ongoing Events </h3>
            @if ($ongoingEvents->isEmpty())
                <p class="text-gray-500">You have not shown interest in any events yet.</p>
            @else
                <section id="not-yet-participated-events" class="grid grid-cols-1 gap-6 p-4 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($ongoingEvents as $event)
                        @include('partials.event', ['event' => $event])
                    @endforeach
                </section>
            @endif
        </div>


        <!-- Participated Events Section -->
        <div class="mt-8">
            <h3 class="mb-4 text-2xl font-bold text-ultraViolet"> Past Events </h3>
            @if (Auth::id() == $user->id && $participatedEvents->isEmpty())
                <p class="text-gray-500">You have not participated in any events yet.</p>
            @elseif (Auth::id() != $user->id && $participatedEvents->isEmpty())
                <p class="text-gray-500">This user has not participated in any events yet.</p>
            @elseif (Auth::id() == $user->id)
                <section id="participated-events" class="grid grid-cols-1 gap-6 p-4 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($participatedEvents as $event)
                        @include('partials.event', ['event' => $event])
                    @endforeach
                </section>
            @else
                <section id="participated-events" class="grid grid-cols-1 gap-6 p-4 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($participatedEvents->filter(fn($event) => $event->visibility !== 'private') as $event)
                        @include('partials.event', ['event' => $event])
                    @endforeach
                </section>
            @endif
        </div>

        <!-- Not Yet Participated Events Section -->
        <div class="mt-8">
            <h3 class="mb-4 text-2xl font-bold text-ultraViolet"> Future Events </h3>
            @if (Auth::id() == $user->id && $participatedEvents->isEmpty())
                <p class="text-gray-500">You have not shown interest in any events yet.</p>
            @elseif (Auth::id() != $user->id && $participatedEvents->isEmpty())
                <p class="text-gray-500">This user has not shown interest in any events yet.</p>
            @elseif (Auth::id() == $user->id)
                <section id="not-yet-participated-events" class="grid grid-cols-1 gap-6 p-4 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($notYetParticipatedEvents as $event)
                        @include('partials.event', ['event' => $event])
                    @endforeach
                </section>
            @else
                <section id="not-yet-participated-events" class="grid grid-cols-1 gap-6 p-4 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($notYetParticipatedEvents->filter(fn($event) => $event->visibility !== 'private') as $event)
                        @include('partials.event', ['event' => $event])
                    @endforeach
                </section>
            @endif
        </div>


    </div>
@endsection