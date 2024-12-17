@extends('layouts.app')

@section('Title', 'Invites Sent')
@section('content')
    <div class="max-w-full p-6 mx-auto mt-6 bg-white rounded-lg shadow-md lg:max-w-8xl"> <!-- Increased width -->
        <div class="mb-6 text-center">
            <h2 class="text-3xl font-bold text-ultraViolet font-montserrat">User Information</h2>
        </div>
    
        <!-- Profile Picture Section -->
        <div class="flex justify-center mb-6">
            <img id="profile-picture-preview" src="{{ asset('images/profile-pictures/'. ($user->picture->path ?? App\Models\User::DEFAULT_PIC)) }}" alt="Profile Picture" class="w-32 h-32 border-2 rounded-full border-ultraViolet">
        </div>

        <!-- User Info Section -->
        <div class="text-center">
            @if ($user->isdeleted)
                <div class="flex items-center justify-center space-x-2">
                    <p class="text-2xl font-montserrat">{{ $user->displayName }}</p>
                </div>
            @else
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
        
        <!-- List of invitations sent -->
        <div class="mt-10">
            <div class="mb-6 text-center">
                <h2 class="text-3xl font-bold text-ultraViolet font-montserrat">Invitations Sent</h2>
            </div>
            @if($invites->isEmpty())
                <p class="text-xl text-center text-gray-500">No invitations sent yet.</p>
            @else
                <div class="overflow-x-auto">
                    <div class="space-y-4">
                        <!-- Column headers -->
                        <div class="grid grid-cols-6 gap-4 p-4 mb-4 text-sm font-semibold bg-gray-100 rounded-lg shadow-md text-darkSlateGrey font-montserrat">
                            <div class="text-center">Recipient</div>
                            <div class="text-center">Event</div>
                            <div class="text-center">Message</div>
                            <div class="text-center">Date</div>
                            <div class="text-center">Status</div>
                            <div class="text-center">Delete</div>
                        </div>

                        @foreach($invites as $invite)
                            <div class="grid grid-cols-6 gap-4 p-4 bg-white border border-gray-200 rounded-lg shadow-md">
                                <!-- Recipient -->
                                <div class="text-center font-montserrat">
                                    <a href="{{ route('user.profile', ['id' => $invite->invitee->id]) }}" class="text-ultraViolet hover:text-darkSlateGrey" aria-label="View profile of {{ $invite->invitee->username }}">
                                        {{ $invite->invitee->username }}
                                    </a>
                                </div>
                                                                                                
                                <!-- Event -->
                                <div class="text-center font-montserrat">
                                    <a href="{{ route('events.show', ['id' => $invite->event->id]) }}" class="text-ultraViolet hover:text-darkSlateGrey" aria-label="View details of event {{ $invite->event->name }}">
                                        {{ $invite->event->name }}
                                    </a>
                                </div>                                
                    
                                <!-- Message -->
                                <div class="text-center font-montserrat">{{ $invite->description ?? 'N/A' }}</div>
                    
                                <!-- Date -->
                                <div class="text-center font-montserrat">{{ $invite->date->format('F j, Y') }}</div>
                    
                                <!-- Status -->
                                <div class="text-center font-montserrat">
                                    @if($invite->accepted)
                                        <span class="font-semibold text-green-500">Accepted</span>
                                    @else
                                        <span class="font-semibold text-yellow-500">Pending</span>
                                    @endif
                                </div>
                    
                                <!-- Delete -->
                                <div class="flex items-center justify-center">
                                    <form action="{{ route('invite.delete', ['id' => $invite->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this invite?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="flex items-center px-4 py-2 space-x-2 border-white rounded-lg hover:border-white focus:border-white hover:bg-white focus:bg-white text-darkSlateGrey hover:text-red-600 focus:text-darkSlateGrey" aria-label="Delete invite for {{ $invite->event->name }} to {{ $invite->invitee->username }}">
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
    </div>
@endsection
