@extends('layouts.app')

@section('content')

<div class="container mx-auto py-10 px-6">
    <div class="text-center mb-10">
        <h1 class="text-3xl font-bold text-ultraViolet mb-4">Frequently Asked Questions (FAQs)</h1>
        <p class="text-lg text-gray-500">Have questions about Lend-A-Hand? Here are the answers to the most common ones!</p>
    </div>
    <!-- General Questions -->
    <section class="mb-10">
        <h2 class="text-2xl font-semibold text-ultraViolet mb-4">General Questions</h2>
        <div class="space-y-4">
            <div class="border border-gray-200 rounded-lg shadow">
                <button class="w-full text-left p-4 font-semibold text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 focus:outline-none transition" onclick="toggleAccordion('general1')">
                    What is Lend-A-Hand?
                </button>
                <div id="general1" class="hidden p-4 text-gray-600">
                    Lend-A-Hand is a platform designed to help individuals and organizations manage and participate in volunteering events. It enables users to create, coordinate, and join volunteer campaigns easily.
                </div>
            </div>
            <div class="border border-gray-200 rounded-lg shadow">
                <button class="w-full text-left p-4 font-semibold text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 focus:outline-none transition" onclick="toggleAccordion('general2')">
                    Do I need to register to use Lend-A-Hand?
                </button>
                <div id="general2" class="hidden p-4 text-gray-600">
                    Guests can browse public events without registering, but to participate in or create events, you must sign up for an account.
                </div>
            </div>
        </div>
    </section>

    <!-- Using the Platform -->
    <section class="mb-10">
        <h2 class="text-2xl font-semibold text-ultraViolet mb-4">Using the Platform</h2>
        <div class="space-y-4">
            <div class="border border-gray-200 rounded-lg shadow">
                <button class="w-full text-left p-4 font-semibold text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 focus:outline-none transition" onclick="toggleAccordion('platform1')">
                    How do I create an event?
                </button>
                <div id="platform1" class="hidden p-4 text-gray-600">
                    Registered members can create events by clicking the "Create Event" button (+) and filling in the required details, such as name, description, and date.
                </div>
            </div>
            <div class="border border-gray-200 rounded-lg shadow">
                <button class="w-full text-left p-4 font-semibold text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 focus:outline-none transition" onclick="toggleAccordion('platform2')">
                    Can I delete my account?
                </button>
                <div id="platform2" class="hidden p-4 text-gray-600">
                    Yes, members can delete their accounts. Upon deletion, your shared data (e.g., comments, reviews) will be anonymized.
                </div>
            </div>
            <div class="border border-gray-200 rounded-lg shadow">
                <button class="w-full text-left p-4 font-semibold text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 focus:outline-none transition" onclick="toggleAccordion('platform3')">
                    How does voting work for event details?
                </button>
                <div id="platform3" class="hidden p-4 text-gray-600">
                    Voting allows participants to have a say in event details. Members can vote once per decision poll, and the event creator finalizes the results.
                </div>
            </div>
            <div class="border border-gray-200 rounded-lg shadow">
                <button class="w-full text-left p-4 font-semibold text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 focus:outline-none transition" onclick="toggleAccordion('platform4')">
                    What happens if an event is canceled?
                </button>
                <div id="platform4" class="hidden p-4 text-gray-600">
                    If an event is canceled, all registered participants will receive a notification with the cancellation details and next steps.
                </div>
            </div>
        </div>
    </section>

    <!-- Event Roles and Responsibilities -->
    <section class="mb-10">
        <h2 class="text-2xl font-semibold text-ultraViolet mb-4">Event Roles and Responsibilities</h2>
        <div class="space-y-4">
            <div class="border border-gray-200 rounded-lg shadow">
                <button class="w-full text-left p-4 font-semibold text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 focus:outline-none transition" onclick="toggleAccordion('roles1')">
                    What roles are available in an event?
                </button>
                <div id="roles" class="hidden p-4 text-gray-600">
                    Participants, Event Creators, and Administrators each have distinct roles in managing and participating in events.
                </div>
            </div>
            <div class="border border-gray-200 rounded-lg shadow">
                <button class="w-full text-left p-4 font-semibold text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 focus:outline-none transition" onclick="toggleAccordion('roles2')">
                    How can event creators manage visibility?
                </button>
                <div id="roles2" class="hidden p-4 text-gray-600">
                Creators can choose to make events public or private. Private events require approval for participants to join.
                </div>
            </div>
            <div class="border border-gray-200 rounded-lg shadow">
                <button class="w-full text-left p-4 font-semibold text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 focus:outline-none transition" onclick="toggleAccordion('roles3')">
                    Can I report inappropriate events?
                </button>
                <div id="roles3" class="hidden p-4 text-gray-600">
                    Yes, members can report events. Administrators will review reports and take appropriate action.
                </div>
            </div>
        </div>
    </section>

    <!-- Technical Support -->
    <section class="mb-10">
        <h2 class="text-2xl font-semibold text-ultraViolet mb-4">Technical Support</h2>
        <div class="space-y-4">
            <div class="border border-gray-200 rounded-lg shadow">
                <button class="w-full text-left p-4 font-semibold text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 focus:outline-none transition" onclick="toggleAccordion('support1')">
                    I forgot my password. How can I reset it?
                </button>
                <div id="support1" class="hidden p-4 text-gray-600">
                    Click the "Forgot Password?" link on the login page and follow the instructions to reset your password.
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

<script>
    function toggleAccordion(id) {
        const section = document.getElementById(id);
        if (section.classList.contains('hidden')) {
            section.classList.remove('hidden');
        } else {
            section.classList.add('hidden');
        }
    }
</script>
