@extends('layouts.app')

@section('Title', 'Profile')
@section('content')
    @if ($user->isblocked)
        <div class="p-4 mb-6 text-white bg-red-500 rounded-md">
            <p class="text-lg font-semibold text-center">
                This user is currently blocked. Some features may not be available.
            </p>
        </div>
    @endif
    <!-- Profile Section Container -->
    <div class="flex flex-col items-center justify-center p-6">
        <div class="grid w-full max-w-4xl grid-cols-3 gap-4">
            <!-- Profile Picture Section (larger, ensure circle) -->
            <div class="flex items-center justify-center">
                @if ($user->isdeleted)
                    <!-- Show a "Deleted" placeholder picture if the user is deleted -->
                    <div class="flex items-center justify-center bg-gray-300 rounded-full w-80">
                        <p class="text-2xl font-bold text-white">Deleted</p>
                    </div>
                @else
                    <img id="profile-picture-preview" src="{{ asset('images/profile-pictures/'. (Auth::user()->picture->path ?? App\Models\User::DEFAULT_PIC)) }}" alt="Profile Picture" class="h-auto border-2 rounded-full w-80 border-ultraViolet">
                @endif
            </div>


            <!-- User Info Section (left-aligned, closer) -->
            <div class="flex flex-col justify-center col-span-2 p-4 space-y-4">
                @if ($user->isdeleted)
                    <!-- Display only a placeholder username if the user is deleted -->
                    <p class="text-2xl font-montserrat">{{ $user->displayName }}</p>
                @else
                    <!-- Display user info if not deleted -->
                    <div>
                        <p class="leading-tight text-8xl font-montserrat text-darkSlateGrey whitespace-nowrap">{{ $user->name }}</p>
                        <p class="text-4xl leading-tight">{{ '@' . $user->displayName }}</p>
                    </div>
                @endif

                <!-- Edit Profile and Invitations Sent Icons -->
                @if((Auth::id() === $user->id && !$user->isdeleted && !$user->isblocked) || auth()->guard('admin')->check())
                    <div class="flex space-x-4">
                        <!-- Edit Profile Button with Icon -->
                        <a href="{{ auth()->guard('admin')->check() ? route('admin.edit.form', ['id' => $user->id]) : route('user.edit.form', ['id' => $user->id]) }}" 
                        class="flex items-center justify-center w-20 h-20 text-2xl font-bold transition-all duration-200 rounded-lg shadow-md bg-ultraViolet hover:bg-cambridgeBlue text-whiteSmoke hover:text-darkSlateGrey font-montserrat">
                            <i class="text-3xl fa-solid fa-user-edit"></i>
                        </a>
                        <!-- Invitations Sent Button with Icon -->
                        <a href="{{ route('user.invites.sent', ['id' => $user->id]) }}" 
                        class="flex items-center justify-center w-20 h-20 text-2xl font-bold transition-all duration-200 rounded-lg shadow-md bg-ultraViolet hover:bg-cambridgeBlue text-whiteSmoke hover:text-darkSlateGrey font-montserrat">
                            <i class="text-3xl fa-solid fa-envelope"></i>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>



    <!-- My Events -->
    <div class="mt-8">
        @if (!$user->isdeleted)
            <h3 class="inline-block mb-4 text-4xl font-semibold transition-all font-montserrat text-eerieBlack group-hover:font-bold">My Events</h3>
            @if ($myEvents->isEmpty())
                <p class="text-gray-500"> User hasn't created any events yet.</p>
            @elseif (Auth::id() == $user->id)
                <section id="events" class="grid w-full gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4">
                    @foreach ($myEvents as $event)
                        @include('partials.event', ['event' => $event])
                    @endforeach
                </section>
            @else
                <section id="events" class="grid w-full gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4">
                    @foreach ($myEvents->filter(fn($event) => $event->visibility !== 'private') as $event)
                        @include('partials.event', ['event' => $event])
                    @endforeach
                </section>
            @endif
        @endif
    </div>

    <!-- Ongoing Events Section -->
    <div class="mt-8">
        <h3 class="inline-block mb-4 text-4xl font-semibold transition-all font-montserrat text-eerieBlack group-hover:font-bold"> Ongoing Events </h3>
        @if ($ongoingEvents->isEmpty())
            <p class="text-gray-500"> User doesn't have any ongoing events. </p>
        @elseif (Auth::id() == $user->id)
            <section id="events" class="grid w-full gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4">
                @foreach ($ongoingEvents as $event)
                    @include('partials.event', ['event' => $event])
                @endforeach
            </section>
        @else
            <section id="events" class="grid w-full gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4">
                @foreach ($ongoingEvents->filter(fn($event) => $event->visibility !== 'private') as $event)
                    @include('partials.event', ['event' => $event])
                @endforeach
            </section>
        @endif
    </div>


    <!-- Participated Events Section -->
    <div class="mt-8">
        <h3 class="inline-block mb-4 text-4xl font-semibold transition-all font-montserrat text-eerieBlack group-hover:font-bold"> Past Events </h3>
        @if ($participatedEvents->isEmpty())
            <p class="text-gray-500"> User hasn't participated in any events. </p>
        @elseif (Auth::id() == $user->id)
            <section id="events" class="grid w-full gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4">
                @foreach ($participatedEvents as $event)
                    @include('partials.event', ['event' => $event])
                @endforeach
            </section>
        @else
            <section id="events" class="grid w-full gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4">
                @foreach ($participatedEvents->filter(fn($event) => $event->visibility !== 'private') as $event)
                    @include('partials.event', ['event' => $event])
                @endforeach
            </section>
        @endif
    </div>

    <!-- Not Yet Participated Events Section -->
    <div class="mt-8">
        <h3 class="inline-block mb-4 text-4xl font-semibold transition-all font-montserrat text-eerieBlack group-hover:font-bold"> Upcoming Events </h3>
        @if ($notYetParticipatedEvents->isEmpty())
            <p class="text-gray-500"> User doesn't have any upcoming events. </p>
        @elseif (Auth::id() == $user->id)
            <section id="events" class="grid w-full gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4">
                @foreach ($notYetParticipatedEvents as $event)
                    @include('partials.event', ['event' => $event])
                @endforeach
            </section>
        @else
            <section id="events" class="grid w-full gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4">
                @foreach ($notYetParticipatedEvents->filter(fn($event) => $event->visibility !== 'private') as $event)
                    @include('partials.event', ['event' => $event])
                @endforeach
            </section>
        @endif
    </div>
@endsection