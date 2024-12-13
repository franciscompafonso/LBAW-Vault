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
    <div class="relative bg-gradient-to-r from-wisteria to-whiteSmoke p-10 rounded-lg shadow-lg flex items-center justify-between">
    <!-- Left: Profile Picture and Info -->
    <div class="flex items-center space-x-6">
        <!-- Profile Picture -->
        <div class="w-32 h-32 rounded-full border-4 border-white overflow-hidden">
            <img src="{{ asset('images/profile-pictures/' . ($user->picture->path ?? App\Models\User::DEFAULT_PIC)) }}" 
                 alt="{{ $user->name }}" 
                 class="object-cover w-full h-full">
        </div>

        <!-- User Info -->
        <div>
            <h1 class="text-6xl font-bold text-eerieBlack font-montserrat">{{ $user->name }}</h1>
            <p class="text-3xl text-eerieBlack">{{ "@" . $user->displayName }}</p>
        </div>
    </div>

    <!-- Right: Action Buttons -->
    <div class="flex space-x-4">
    @if((Auth::id() === $user->id && !$user->isdeleted && !$user->isblocked) || auth()->guard('admin')->check())
        <!-- Edit Profile Button -->
        <a href="{{ auth()->guard('admin')->check() ? route('admin.edit.form', ['id' => $user->id]) : route('user.edit.form', ['id' => $user->id]) }}" 
           class="flex items-center justify-center px-6 py-2 font-bold text-white transition-all duration-200 rounded-md font-montserrat bg-ultraViolet hover:bg-cambridgeBlue hover:text-ultraViolet">
            <i class="text-xl fa-solid fa-user-edit mr-2"></i> Edit Profile
        </a>
        
        <!-- Invitations Sent Button -->
        <a href="{{ route('user.invites.sent', ['id' => $user->id]) }}" 
           class="flex items-center justify-center px-6 py-2 font-bold text-white transition-all duration-200 rounded-md font-montserrat bg-ultraViolet hover:bg-cambridgeBlue hover:text-ultraViolet ">
            <i class="text-xl fa-solid fa-envelope mr-2"></i> Invitations
        </a>
    @endif
</div>
</div>





    <!-- My Events -->
    <div class="mt-8">
        <h3 class="inline-block mb-4 text-4xl font-semibold transition-all font-montserrat text-eerieBlack group-hover:font-bold">My Events</h3>
        @if ($myEvents->isEmpty())
            <p class="text-gray-500">User hasn't created any events yet.</p>
        @else
            <section id="events" class="grid w-full gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4">
                @foreach ($myEvents as $event)
                    @include('partials.event', ['event' => $event])
                @endforeach
            </section>
            <!-- Pagination Links -->
            <div class="mt-6">
                {{ $myEvents->links() }}
            </div>
        @endif
    </div>

    <!-- ongoingEvents Section -->
    <div class="mt-8">
        <h3 class="inline-block mb-4 text-4xl font-semibold transition-all font-montserrat text-eerieBlack group-hover:font-bold">Ongoing Events</h3>
        @if ($ongoingEvents->isEmpty())
            <p class="text-gray-500">User does not have ongoing events.</p>
        @else
            <section id="events" class="grid w-full gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4">
                @foreach ($ongoingEvents as $event)
                    @include('partials.event', ['event' => $event])
                @endforeach
            </section>
            <!-- Pagination Links -->
            <div class="mt-6">
                {{ $ongoingEvents->links() }}
            </div>
        @endif
    </div>


    <!-- participatedEvents Section -->
    <div class="mt-8">
        <h3 class="inline-block mb-4 text-4xl font-semibold transition-all font-montserrat text-eerieBlack group-hover:font-bold">Past Events</h3>
        @if ($participatedEvents->isEmpty())
            <p class="text-gray-500">User has not participated in events yet.</p>
        @else
            <section id="events" class="grid w-full gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4">
                @foreach ($participatedEvents as $event)
                    @include('partials.event', ['event' => $event])
                @endforeach
            </section>
            <!-- Pagination Links -->
            <div class="mt-6">
                {{ $participatedEvents->links() }}
            </div>
        @endif
    </div>

    <!-- Not Yet Participated Events Section -->
    <div class="mt-8">
        <h3 class="inline-block mb-4 text-4xl font-semibold transition-all font-montserrat text-eerieBlack group-hover:font-bold">Future Events</h3>
        @if ($notYetParticipatedEvents->isEmpty())
            <p class="text-gray-500">User does not have upcoming events yet.</p>
        @else
            <section id="events" class="grid w-full gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4">
                @foreach ($notYetParticipatedEvents as $event)
                    @include('partials.event', ['event' => $event])
                @endforeach
            </section>
            <!-- Pagination Links -->
            <div class="mt-6">
                {{ $notYetParticipatedEvents->links() }}
            </div>
        @endif
    </div>
@endsection

