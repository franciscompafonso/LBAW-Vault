@extends('layouts.app')

@section('Title', $user->name . "'s Profile")
@section('content')
    @if ($user->isblocked)
        <div class="p-4 mb-6 text-white bg-red-500 rounded-md">
            <p class="text-lg font-semibold text-center">
                This user is currently blocked. Some features may not be available.
            </p>
        </div>
    @endif
    <!-- Profile Section Container -->
    <div class="relative bg-gradient-to-r from-whiteSmoke to-wisteria p-16 rounded-lg shadow-lg flex items-center justify-between">
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
        <a href="{{ route('user.edit.form', ['id' => $user->id]) }}" 
            aria-label="Edit profile of {{ $user->name }}"
            class="flex items-center justify-center px-6 py-2 font-bold text-white transition-all duration-200 rounded-md font-montserrat bg-ultraViolet hover:bg-cambridgeBlue hover:text-ultraViolet">
            <i class="text-xl fa-solid fa-user-edit mr-2"></i> Edit Profile
        </a>
        
        <!-- Invitations Sent Button -->
        <a href="{{ route('user.invites.sent', ['id' => $user->id]) }}" aria-label="View invitations sent by {{ $user->name }}"
           class="flex items-center justify-center px-6 py-2 font-bold text-white transition-all duration-200 rounded-md font-montserrat bg-ultraViolet hover:bg-cambridgeBlue hover:text-ultraViolet ">
            <i class="text-xl fa-solid fa-envelope mr-2"></i> Invitations
        </a>
    @endif
</div>
</div>
    <!-- My Events -->
    <div class="mt-8" id="my_events-container">
    <h3 class="mb-4 py-6 text-4xl font-semibold font-montserrat text-eerieBlack">My Events</h3>
    @include('partials.event_section', [
        'events' => $myEvents,
        'sectionId' => 'my_events',
    ])
    </div>


    <!-- ongoingEvents Section -->
    <div class="mt-8" id="ongoing_events-container">
    <h3 class="mb-4 py-6 text-4xl font-semibold font-montserrat text-eerieBlack">Ongoing Events</h3>
    @include('partials.event_section', [
        'events' => $ongoingEvents,
        'sectionId' => 'ongoing_events',
    ])
    </div>


    <!-- participatedEvents Section -->
    <div class="mt-8" id="participated_events-container">
    <h3 class="mb-4 py-6 text-4xl font-semibold font-montserrat text-eerieBlack">Past Events</h3>
    @include('partials.event_section', [
        'events' => $participatedEvents,
        'sectionId' => 'participated_events',
    ])
    </div>

    <!-- Not Yet Participated Events Section -->
    <div class="mt-8" id="not_yet_participated_events-container">
    <h3 class="mb-4 py-6 text-4xl font-semibold font-montserrat text-eerieBlack">Future Events</h3>
    @include('partials.event_section', [
        'events' => $notYetParticipatedEvents,
        'sectionId' => 'not_yet_participated_events',
    ])
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
    const handlePagination = (event) => {
        event.preventDefault();

        const url = event.target.closest('a').href; // Get the pagination URL
        const section = event.target.closest('div[id$="-container"]').id.replace('-container', ''); // Extract section ID

        console.log(`Fetching URL: ${url} for section: ${section}`); // Debugging

        fetch(`${url}&section=${section}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then((html) => {
                const container = document.getElementById(`${section}-container`);
                if (container) {
                    console.log(`Updating content for section: ${section}`);
                    container.innerHTML = html; // Replace HTML content
                    attachPaginationHandlers(); // Reattach pagination handlers
                } else {
                    console.error(`Container not found for section: ${section}`);
                }
            })
            .catch((error) => {
                console.error('Error fetching pagination:', error);
            });
    };

    const attachPaginationHandlers = () => {
        document.querySelectorAll('.pagination a').forEach((link) => {
            link.addEventListener('click', handlePagination);
        });
    };

    attachPaginationHandlers(); // Initial setup
});


</script>

@endsection






