@extends('layouts.app')

@section('Title', 'Invites')
@section('content')
<script src="{{ url('js/user/invites.js') }}" defer></script>

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
            <a  href="{{ route('user.profile', ['username' => $user->username]) }}" 
                aria-label="View {{ $user->name }} profile"
                class="flex items-center justify-center px-8 py-4 w-full lg:w-auto text-2xl font-bold text-white transition-all duration-200 rounded-md font-montserrat bg-ultraViolet hover:bg-cambridgeBlue hover:text-ultraViolet">
                <i class="mr-2 text-lg lg:text-xl fa-solid fa-calendar"></i> Events
            </a>
        @endif
    </div>
</div>


<div class="mt-10">
    <!-- Tab Buttons -->
    <div class="flex gap-8 relative">
        <p 
            id="tab-invite-sent" 
            class="tab text-3xl lg:text-4xl font-semibold font-montserrat text-eerieBlack focus:outline-none cursor-pointer pb-2"
            onclick="showTab('invite-sent')"
            tabindex="0"
            role="button"
        >
            Invites Sent
        </p>

        <p 
            id="tab-invite-received" 
            class="tab text-3xl lg:text-4xl font-semibold font-montserrat text-eerieBlack focus:outline-none cursor-pointer pb-2"
            onclick="showTab('invite-received')"
            tabindex="0"
            role="button"
        >
            Invites Received
        </p>

        <p 
            id="tab-request-sent" 
            class="tab text-3xl lg:text-4xl font-semibold font-montserrat text-eerieBlack focus:outline-none cursor-pointer pb-2"
            onclick="showTab('request-sent')"
            tabindex="0"
            role="button"
        >
            Requests Sent
        </p>

        <p 
            id="tab-request-received" 
            class="tab text-3xl lg:text-4xl font-semibold font-montserrat text-eerieBlack focus:outline-none cursor-pointer pb-2"
            onclick="showTab('request-received')"
            tabindex="0"
            role="button"
        >
            Requests Received
        </p>

        <!-- Animated border -->
        <div 
            id="tab-indicator" 
            class="absolute bottom-0 h-1.5 bg-wisteria transition-all duration-300 ease-in-out"
        ></div>
    </div>


    <!-- Tab Contents -->
    <div class="mt-6">
        <!-- Sent Invitations Content -->
        

        <div id="content-invite-sent" class="hidden">
            @if($sentInvites->isEmpty())
                <p class="text-3xl text-center text-gray-500">No invitations sent yet.</p>
            @else
                <!-- Wrapper to enable horizontal scrolling on smaller screens -->
                <div class="overflow-x-auto">
                    <div class="min-w-[1024px]">
                        <!-- Table Header -->
                        <div class="grid grid-cols-5 gap-4 p-4 mb-4 text-3xl font-semibold bg-gray-100 rounded-lg text-darkSlateGrey font-montserrat">
                            <div class="text-center">Event</div>
                            <div class="text-center">Receiver</div>
                            <div class="text-center">Message</div>
                            <div class="text-center">Date</div>
                            <div class="text-center">Decision</div>
                        </div>
                        <!-- Table Rows -->
                        @foreach($sentInvites as $invite)
                            @include('partials.invite_sent', ['invite' => $invite])
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Received Invitations Content -->
        <div id="content-invite-received" class="hidden">
            @if($receivedInvites->isEmpty())
                <p class="text-3xl text-center text-gray-500">No invitations received yet.</p>
            @else
                <div class="overflow-x-auto">
                    <!-- Received Invitations Table Content -->
                    <div class="min-w-[1024px]">
                        <div class="grid grid-cols-5 gap-4 p-4 mb-4 text-3xl font-semibold bg-gray-100 rounded-lg text-darkSlateGrey font-montserrat">
                            <div class="text-center">Event</div>
                            <div class="text-center">Sender</div>
                            <div class="text-center">Message</div>
                            <div class="text-center">Date</div>
                            <div class="text-center">Decision</div>
                        </div>
                        @foreach($receivedInvites as $invite)
                            @include('partials.invite_received', ['invite' => $invite])
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Sent Requests Content -->
        <div id="content-request-sent" class="hidden">
            @if($sentRequests->isEmpty())
                <p class="text-3xl text-center text-gray-500">No invitations sent yet.</p>
            @else
                <div class="overflow-x-auto">
                    <!-- Sent Invitations Table Content -->
                    <div class="min-w-[1024px]">
                        <div class="grid grid-cols-5 gap-4 p-4 mb-4 text-3xl font-semibold bg-gray-100 rounded-lg text-darkSlateGrey font-montserrat">
                            <div class="text-center">Event</div>
                            <div class="text-center"></div>
                            <div class="text-center">Status</div>
                            <div class="text-center">Date</div>
                            <div class="text-center">Decision</div>
                        </div>
                        @foreach($sentRequests as $request)
                            @include('partials.request_sent', ['request' => $request])
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Received Requests Content -->
        <div id="content-request-received" class="hidden">
            @if($receivedRequests->isEmpty())
                <p class="text-3xl text-center text-gray-500">No invitations received yet.</p>
            @else
                <div class="overflow-x-auto">
                    <!-- Received Invitations Table Content -->
                    <div class="min-w-[1024px]">
                        <div class="grid grid-cols-5 gap-4 p-4 mb-4 text-3xl font-semibold bg-gray-100 rounded-lg text-darkSlateGrey font-montserrat">
                            <div class="text-center">Event</div>
                            <div class="text-center">Sender</div>
                            <div class="text-center"></div>
                            <div class="text-center">Date</div>
                            <div class="text-center">Decision</div>
                        </div>
                        @foreach($receivedRequests as $request)
                            @include('partials.request_received', ['request' => $request])
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
