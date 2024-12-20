@extends('layouts.app')

@section('Title', 'FAQs')

@section('content')

<script type="text/javascript" src="{{ url('js/pages/faqs.js') }}" defer></script>
<div class="container px-6 py-10 mx-auto">
    <div class="mb-10 text-center">
        <h1 class="mb-6 text-4xl font-bold font-montserrat text-ultraViolet">Frequently Asked Questions (FAQs)</h1>
        <p class="text-lg text-gray-500">Have questions about Lend-A-Hand? Here are the answers to the most common ones!
        </p>
    </div>
    <!-- General Questions -->
    <section class="mb-10" aria-labelledby="general-questions">
        <h2 id="general-questions" class="mb-4 text-2xl font-semibold font-montserrat text-ultraViolet">General Questions</h2>
        <div class="space-y-4">
            <div class="border border-gray-200 rounded-lg shadow">
                <button
                    class="w-full p-4 font-semibold text-left transition duration-200 ease-in-out bg-gray-100 rounded-lg text-eerieBlack font-montserrat hover:bg-ultraViolet hover:text-white focus:outline-none focus:ring-2 focus:ring-ultraViolet"
                    onclick="toggleAccordion('general1')">
                        What is Lend-A-Hand?
                </button>
                <div id="general1" class="hidden p-4 text-gray-600">
                    Lend-A-Hand is a platform designed to help individuals and organizations manage and participate in
                    volunteering events. It enables users to create, coordinate, and join volunteer campaigns easily.
                </div>
            </div>
            <div class="border border-gray-200 rounded-lg shadow">
                <button
                    class="w-full p-4 font-semibold text-left transition duration-200 ease-in-out bg-gray-100 rounded-lg text-eerieBlack font-montserrat hover:bg-ultraViolet hover:text-white focus:outline-none focus:ring-2 focus:ring-ultraViolet"
                    onclick="toggleAccordion('general2')">
                    Do I need to register to use Lend-A-Hand?
                </button>
                <div id="general2" class="hidden p-4 text-gray-600">
                    Guests can browse public events without registering, but to participate in or create events, you
                    must sign up for an account.
                </div>
            </div>
        </div>
    </section>

    <!-- Using the Platform -->
    <section class="mb-10" aria-labelledby="platform-questions">
        <h2 id="platform-questions" class="mb-4 text-2xl font-semibold font-montserrat text-ultraViolet">Using the Platform</h2>
        <div class="space-y-4">
            <div class="border border-gray-200 rounded-lg shadow">
                <button
                    class="w-full p-4 font-semibold text-left transition duration-200 ease-in-out bg-gray-100 rounded-lg text-eerieBlack font-montserrat hover:bg-ultraViolet hover:text-white focus:outline-none focus:ring-2 focus:ring-ultraViolet"
                    onclick="toggleAccordion('platform1')">
                    How do I create an event?
                </button>
                <div id="platform1" class="hidden p-4 text-gray-600">
                    Registered members can create events by clicking the "Create Event" button (+) and filling in the
                    required details, such as name, description, and date.
                </div>
            </div>
            <div class="border border-gray-200 rounded-lg shadow">
                <button
                    class="w-full p-4 font-semibold text-left transition duration-200 ease-in-out bg-gray-100 rounded-lg text-eerieBlack font-montserrat hover:bg-ultraViolet hover:text-white focus:outline-none focus:ring-2 focus:ring-ultraViolet"
                    onclick="toggleAccordion('platform2')">
                    Can I delete my account?
                </button>
                <div id="platform2" class="hidden p-4 text-gray-600">
                    Yes, members can delete their accounts. Upon deletion, your shared data (e.g., comments, reviews)
                    will be anonymized.
                </div>
            </div>
            <div class="border border-gray-200 rounded-lg shadow">
                <button
                    class="w-full p-4 font-semibold text-left transition duration-200 ease-in-out bg-gray-100 rounded-lg text-eerieBlack font-montserrat hover:bg-ultraViolet hover:text-white focus:outline-none focus:ring-2 focus:ring-ultraViolet"
                    onclick="toggleAccordion('platform3')">
                    How does voting work for event details?
                </button>
                <div id="platform3" class="hidden p-4 text-gray-600">
                    Voting allows participants to have a say in event details. Members can vote once per decision poll,
                    and the event creator finalizes the results.
                </div>
            </div>
            <div class="border border-gray-200 rounded-lg shadow">
                <button
                    class="w-full p-4 font-semibold text-left transition duration-200 ease-in-out bg-gray-100 rounded-lg text-eerieBlack font-montserrat hover:bg-ultraViolet hover:text-white focus:outline-none focus:ring-2 focus:ring-ultraViolet"
                    onclick="toggleAccordion('platform4')">
                    What happens if an event is canceled?
                </button>
                <div id="platform4" class="hidden p-4 text-gray-600">
                    If an event is canceled, all registered participants will receive a notification with the
                    cancellation details and next steps.
                </div>
            </div>
        </div>
    </section>

    <!-- Event Roles and Responsibilities -->
    <section class="mb-10" aria-labelledby="roles-questions">
        <h2 id="roles-questions" class="mb-4 text-2xl font-semibold font-montserrat text-ultraViolet">Event Roles and Responsibilities</h2>
        <div class="space-y-4">
            <div class="border border-gray-200 rounded-lg shadow">
                <button
                    class="w-full p-4 font-semibold text-left transition duration-200 ease-in-out bg-gray-100 rounded-lg text-eerieBlack font-montserrat hover:bg-ultraViolet hover:text-white focus:outline-none focus:ring-2 focus:ring-ultraViolet"
                    onclick="toggleAccordion('roles1')">
                    What roles are available in an event?
                </button>
                <div id="roles" class="hidden p-4 text-gray-600">
                    Participants, Event Creators, and Administrators each have distinct roles in managing and
                    participating in events.
                </div>
            </div>
            <div class="border border-gray-200 rounded-lg shadow">
                <button
                    class="w-full p-4 font-semibold text-left transition duration-200 ease-in-out bg-gray-100 rounded-lg text-eerieBlack font-montserrat hover:bg-ultraViolet hover:text-white focus:outline-none focus:ring-2 focus:ring-ultraViolet"
                    onclick="toggleAccordion('roles2')">
                    How can event creators manage visibility?
                </button>
                <div id="roles2" class="hidden p-4 text-gray-600">
                    Creators can choose to make events public or private. Private events require approval for
                    participants to join.
                </div>
            </div>
            <div class="border border-gray-200 rounded-lg shadow">
                <button
                    class="w-full p-4 font-semibold text-left transition duration-200 ease-in-out bg-gray-100 rounded-lg text-eerieBlack font-montserrat hover:bg-ultraViolet hover:text-white focus:outline-none focus:ring-2 focus:ring-ultraViolet"
                    onclick="toggleAccordion('roles3')">
                    Can I report inappropriate events?
                </button>
                <div id="roles3" class="hidden p-4 text-gray-600">
                    Yes, members can report events. Administrators will review reports and take appropriate action.
                </div>
            </div>
        </div>
    </section>

    <!-- Technical Support -->
    <section class="mb-10" aria-labelledby="technical-questions">
        <h2 id="technical-questions" class="mb-4 text-2xl font-semibold font-montserrat text-ultraViolet">Technical Support</h2>
        <div class="space-y-4">
            <div class="border border-gray-200 rounded-lg shadow">
                <button
                    class="w-full p-4 font-semibold text-left transition duration-200 ease-in-out bg-gray-100 rounded-lg text-eerieBlack font-montserrat hover:bg-ultraViolet hover:text-white focus:outline-none focus:ring-2 focus:ring-ultraViolet"
                    onclick="toggleAccordion('support1')">
                    I forgot my password. How can I reset it?
                </button>
                <div id="support1" class="hidden p-4 text-gray-600">
                    Click the "Forgot Password?" link on the login page and follow the instructions to reset your
                    password.
                </div>
            </div>
        </div>
    </section>
</div>
@endsection