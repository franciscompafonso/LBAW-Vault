@extends('layouts.app')

@section('Title', 'Invites')
@section('content')
    <!-- Profile Section Container -->
    <div class="relative flex items-center justify-between p-16 rounded-lg shadow-lg bg-gradient-to-r from-whiteSmoke to-wisteria">
        <!-- Left: Profile Picture and Info -->
        <div class="flex items-center space-x-6">
            <div class="w-32 h-32 overflow-hidden border-4 border-white rounded-full">
                <img src="{{ asset('images/profile-pictures/' . ($user->picture->path ?? App\Models\User::DEFAULT_PIC)) }}" 
                    alt="{{ $user->name }}" 
                    class="object-cover w-full h-full">
            </div>

            <div>
                <h1 class="text-6xl font-bold text-eerieBlack font-montserrat">{{ $user->name }}</h1>
                <p class="text-3xl text-eerieBlack">{{ "@" . $user->username }}</p>
            </div>
        </div>

        <div class="flex space-x-4">
            @if((Auth::id() === $user->id && !$user->isdeleted && !$user->isblocked) || auth()->guard('admin')->check())
                <a href="{{ route('user.edit.form', ['id' => $user->id]) }}" 
                   aria-label="Edit profile of {{ $user->name }}"
                   class="flex items-center justify-center px-6 py-2 font-bold text-white transition-all duration-200 rounded-md font-montserrat bg-ultraViolet hover:bg-cambridgeBlue hover:text-ultraViolet">
                    <i class="mr-2 text-xl fa-solid fa-user-edit"></i> Edit Profile
                </a>

                <a href="{{ route('user.profile', ['username' => $user->username]) }}" 
                   aria-label="View {{ $user->name }} profile"
                   class="flex items-center justify-center px-6 py-2 font-bold text-white transition-all duration-200 rounded-md font-montserrat bg-ultraViolet hover:bg-cambridgeBlue hover:text-ultraViolet ">
                    <i class="mr-2 text-xl fa-solid fa-user"></i> Profile
                </a>
            @endif
        </div>
    </div>

    <div class="mt-10">
        <!-- Tab Buttons -->
        <div class="flex space-x-4">
            <button 
                id="tab-sent" 
                class="text-4xl font-semibold font-montserrat text-eerieBlack border-0 border-b-4 focus:outline-none rounded-none"
                onclick="showTab('sent')"
            >
                <p class="">Invites Sent</p>
            </button>
            <button 
                id="tab-received" 
                class="text-4xl font-semibold font-montserrat text-eerieBlack border-0 border-b-4 focus:outline-none rounded-none"
                onclick="showTab('received')"
            >
                <p class="">Invites Received</p>
            </button>
        </div>

        <!-- Tab Contents -->
        <div class="mt-6">
            <!-- Sent Invitations Content -->
            <div id="content-sent" class="hidden">
                @if($sentInvites->isEmpty())
                    <p class="text-xl text-center text-gray-500">No invitations sent yet.</p>
                @else
                    <div class="overflow-x-auto">
                        <!-- Sent Invitations Table Content -->
                        <div class="space-y-4">
                            <div class="grid grid-cols-6 gap-4 p-4 mb-4 text-sm font-semibold bg-gray-100 rounded-lg shadow-md text-darkSlateGrey font-montserrat">
                                <div class="text-center">Recipient</div>
                                <div class="text-center">Event</div>
                                <div class="text-center">Message</div>
                                <div class="text-center">Date</div>
                                <div class="text-center">Status</div>
                                <div class="text-center">Delete</div>
                            </div>
                            @foreach($sentInvites as $invite)
                                <div class="grid grid-cols-6 gap-4 p-4 bg-white border border-gray-200 rounded-lg shadow-md">
                                    <div class="text-center font-montserrat">
                                        <a href="{{ route('user.profile', ['username' => $invite->receiver->username]) }}" class="text-ultraViolet hover:text-darkSlateGrey">
                                            {{ $invite->receiver->username }}
                                        </a>
                                    </div>
                                    <div class="text-center font-montserrat">
                                        <a href="{{ route('events.show', ['id' => $invite->event->id]) }}" class="text-ultraViolet hover:text-darkSlateGrey">
                                            {{ $invite->event->name }}
                                        </a>
                                    </div>
                                    <div class="text-center font-montserrat">{{ $invite->description ?? 'N/A' }}</div>
                                    <div class="text-center font-montserrat">{{ $invite->date->format('F j, Y') }}</div>
                                    <div class="text-center font-montserrat">
                                        @if($invite->accepted)
                                            <span class="font-semibold text-green-500">Accepted</span>
                                        @else
                                            <span class="font-semibold text-yellow-500">Pending</span>
                                        @endif
                                    </div>
                                    <div class="flex items-center justify-center">
                                        <form action="{{ route('invite.delete', ['id' => $invite->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this invite?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="flex items-center px-4 py-2 space-x-2 border-white rounded-lg hover:border-white focus:border-white hover:bg-white focus:bg-white text-darkSlateGrey hover:text-red-600 focus:text-darkSlateGrey">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Received Invitations Content -->
            <div id="content-received" class="hidden">
                @if($receivedInvites->isEmpty())
                    <p class="text-xl text-center text-gray-500">No invitations received yet.</p>
                @else
                    <div class="overflow-x-auto">
                        <!-- Received Invitations Table Content -->
                        <div class="space-y-4">
                            <div class="grid grid-cols-6 gap-4 p-4 mb-4 text-sm font-semibold bg-gray-100 rounded-lg shadow-md text-darkSlateGrey font-montserrat">
                                <div class="text-center">Sender</div>
                                <div class="text-center">Event</div>
                                <div class="text-center">Message</div>
                                <div class="text-center">Date</div>
                                <div class="text-center">Actions</div>
                            </div>
                            @foreach($receivedInvites as $invite)
                                <div class="grid grid-cols-6 gap-4 p-4 bg-white border border-gray-200 rounded-lg shadow-md">
                                    <div class="text-center font-montserrat">
                                        <a href="{{ route('user.profile', ['username' => $invite->sender->username]) }}" class="text-ultraViolet hover:text-darkSlateGrey">
                                            {{ $invite->sender->username }}
                                        </a>
                                    </div>
                                    <div class="text-center font-montserrat">
                                        <a href="{{ route('events.show', ['id' => $invite->event->id]) }}" class="text-ultraViolet hover:text-darkSlateGrey">
                                            {{ $invite->event->name }}
                                        </a>
                                    </div>
                                    <div class="text-center font-montserrat">{{ $invite->description ?? 'N/A' }}</div>
                                    <div class="text-center font-montserrat">{{ $invite->date->format('F j, Y') }}</div>
                                    <div class="flex space-x-4">
                                        <form action="{{ route('invites.accept', ['invitesId' => $invite->id]) }}" method="POST">
                                            @csrf
                                            <button 
                                                type="submit" 
                                                class="px-6 py-2 font-bold text-white transition-all duration-200 rounded-md font-montserrat bg-ultraViolet hover:bg-[#8D9D8D] hover:text-gray-100 border-[#A3C1AD] hover:border-[#8D9D8D]"
                                            >
                                                Accept
                                            </button>
                                        </form>
                                        <form action="{{ route('invites.decline', ['invitesId' => $invite->id]) }}" method="POST">
                                            @csrf
                                            <button 
                                                type="submit" 
                                                class="px-6 py-2 font-bold text-white transition-all duration-200 rounded-md font-montserrat bg-burgundy hover:bg-[#6B0010] hover:text-gray-100 border-[#800020] hover:border-[#6B0010]"
                                            >
                                                Decline
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function showTab(tab) {
            // Hide both contents
            document.getElementById('content-sent').classList.add('hidden');
            document.getElementById('content-received').classList.add('hidden');
            
            // Remove green border from both buttons
            document.getElementById('tab-sent').classList.remove('border-green-500');
            document.getElementById('tab-received').classList.remove('border-green-500');
            document.getElementById('tab-sent').classList.remove('border-green-500');
            document.getElementById('tab-received').classList.remove('border-green-500');
            
            // Show selected content and add green border to the active button
            document.getElementById(`content-${tab}`).classList.remove('hidden');
            document.getElementById(`tab-${tab}`).classList.add('border-green-500');
        }

        // Initialize to show "Sent Invites" tab
        showTab('sent');
    </script>
@endsection
