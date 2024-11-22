@extends('layouts.app')

@section('content')
    <div class="max-w-9xl mx-auto mt-6 p-6 bg-white rounded-lg shadow-md">
        <!-- Main Container -->
        <div class="flex space-x-8">
            <!-- Left Side: Profile Information -->
            <div class="w-1/3">
                <!-- Profile Picture Section -->
                <div class="flex justify-center mb-6">
                    @if ($user->profilepicture)
                        <img src="{{ asset('images/profile-pictures/' . $user->profilepicture) }}" alt="Profile Picture" class="w-32 h-32 rounded-full border-2 border-darkSlateGrey">
                    @else
                        <div class="w-32 h-32 bg-gray-300 rounded-full flex items-center justify-center">
                            <i class="fa-solid fa-user text-white text-3xl"></i>
                        </div>
                    @endif
                </div>

                <!-- User Info Section -->
                <div class="text-center mb-6">
                    <div class="flex justify-center items-center space-x-2">
                        <p class="text-2xl font-montserrat font-semibold">Name:</p>
                        <p class="text-2xl font-montserrat">{{ $user->name }}</p>
                    </div>
                    <div class="flex justify-center items-center space-x-2">
                        <p class="text-2xl font-montserrat font-semibold">Username:</p>
                        <p class="text-2xl font-montserrat">{{ $user->username }}</p>
                    </div>
                    <div class="flex justify-center items-center space-x-2">
                        <p class="text-2xl font-montserrat font-semibold">Email:</p>
                        <p class="text-2xl font-montserrat">{{ $user->email }}</p>
                    </div>
                </div>

                <!-- Edit Profile Link -->
                <div class="flex justify-center py-4">
                    <a href="{{ route('user.edit.form', ['id' => $user->id]) }}" 
                        class="bg-ultraViolet hover:bg-cambridgeBlue text-whiteSmoke text-xl font-semibold py-2 px-6 rounded-lg shadow-md hover:text-darkSlateGrey transition-all duration-200 font-montserrat">
                        Edit Profile
                    </a>
                </div>
            </div>

            <!-- Right Side: Events -->
            <div class="w-2/3">
                <!-- My Events Section -->
                <div class="mt-8">
                    <h3 class="text-2xl font-bold text-ultraViolet mb-4">My Events</h3>
                    @if ($myEvents->isEmpty())
                        <p class="text-gray-500">You have not created any events yet.</p>
                    @else
                        <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-4">
                            @foreach ($myEvents as $event)
                                @include('partials.event', ['event' => $event])
                            @endforeach
                        </section>
                    @endif
                </div>

                <!-- Participated Events Section -->
                <div class="mt-8">
                    <h3 class="text-2xl font-bold text-ultraViolet mb-4">Events I've Participated In</h3>
                    @if ($participatedEvents->isEmpty())
                        <p class="text-gray-500">You have not participated in any events yet.</p>
                    @else
                        <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-4">
                            @foreach ($participatedEvents as $event)
                                @include('partials.event', ['event' => $event])
                            @endforeach
                        </section>
                    @endif
                </div>

                <!-- Upcoming Events Section -->
                <div class="mt-8">
                    <h3 class="text-2xl font-bold text-ultraViolet mb-4">Upcoming Events</h3>
                    @if ($notYetParticipatedEvents->isEmpty())
                        <p class="text-gray-500">You have no upcoming events.</p>
                    @else
                        <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-4">
                            @foreach ($notYetParticipatedEvents as $event)
                                @include('partials.event', ['event' => $event])
                            @endforeach
                        </section>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
