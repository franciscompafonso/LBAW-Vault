@extends('layouts.app')

@section('Title', 'Profile')
@section('content')
    <div class="p-6 mx-auto mt-6 bg-white rounded-lg shadow-md max-w-10xl">
        @if ($user->isblocked)
            <div class="p-4 mb-6 text-white bg-red-500 rounded-md">
                <p class="text-lg font-semibold text-center">
                    This user is currently blocked. Some features may not be available.
                </p>
            </div>
        @endif
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
            @else
                <img id="profile-picture-preview" src="{{ asset('images/profile-pictures/'. ($user->picture->first()->path ?? App\Models\User::DEFAULT_PIC)) }}" alt="Profile Picture" class="w-32 h-32 border-2 rounded-full border-ultraViolet">
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
        @if((Auth::id() === $user->id && !$user->isdeleted && !$user->isblocked) || auth()->guard('admin')->check())
            <div class="flex flex-col items-center px-4 py-8 space-y-6">
                <a href="{{ auth()->guard('admin')->check() ? route('admin.edit.form', ['id' => $user->id]) : route('user.edit.form', ['id' => $user->id]) }}" 
                class="w-full max-w-md py-3 text-2xl font-bold text-center transition-all duration-200 rounded-lg shadow-md bg-ultraViolet hover:bg-cambridgeBlue text-whiteSmoke hover:text-darkSlateGrey font-montserrat">
                    Edit Profile
                </a>
            </div>
            <div class="flex flex-col items-center">
                <a href="{{ route('user.invites.sent', ['id' => $user->id]) }}" 
                class="w-full max-w-md py-3 text-2xl font-bold text-center transition-all duration-200 rounded-lg shadow-md bg-ultraViolet hover:bg-cambridgeBlue text-whiteSmoke hover:text-darkSlateGrey font-montserrat">
                    Invitations Sent
                </a>
            </div>            
        @endif

        <!-- My Events -->
        <div class="mt-8">
            @if (!$user->isdeleted)
                <h3 class="mb-4 text-2xl font-bold text-ultraViolet">My Events</h3>
                @if ($myEvents->isEmpty())
                    <p class="text-gray-500"> User hasn't created any events yet.</p>
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
                <p class="text-gray-500"> User doesn't have any ongoing events. </p>
            @elseif (Auth::id() == $user->id)
                <section id="events" class="grid grid-cols-1 gap-6 p-4 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($ongoingEvents as $event)
                        @include('partials.event', ['event' => $event])
                    @endforeach
                </section>
            @else
                <section id="events" class="grid grid-cols-1 gap-6 p-4 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($ongoingEvents->filter(fn($event) => $event->visibility !== 'private') as $event)
                        @include('partials.event', ['event' => $event])
                    @endforeach
                </section>
            @endif
        </div>


        <!-- Participated Events Section -->
        <div class="mt-8">
            <h3 class="mb-4 text-2xl font-bold text-ultraViolet"> Past Events </h3>
            @if ($participatedEvents->isEmpty())
                <p class="text-gray-500"> User hasn't participated in any events. </p>
            @elseif (Auth::id() == $user->id)
                <section id="events" class="grid grid-cols-1 gap-6 p-4 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($participatedEvents as $event)
                        @include('partials.event', ['event' => $event])
                    @endforeach
                </section>
            @else
                <section id="events" class="grid grid-cols-1 gap-6 p-4 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($participatedEvents->filter(fn($event) => $event->visibility !== 'private') as $event)
                        @include('partials.event', ['event' => $event])
                    @endforeach
                </section>
            @endif
        </div>

        <!-- Not Yet Participated Events Section -->
        <div class="mt-8">
            <h3 class="mb-4 text-2xl font-bold text-ultraViolet"> Upcoming Events </h3>
            @if ($notYetParticipatedEvents->isEmpty())
                <p class="text-gray-500"> User doesn't have any upcoming events. </p>
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