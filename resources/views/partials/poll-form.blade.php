<script type="text/javascript" src="{{ url('js/partials/poll-form.js') }}" defer></script>
<div id="poll-form" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-gray-500 bg-opacity-75">
    <div class="w-1/2 p-6 bg-white rounded-lg shadow-lg">
        <h2 class="mb-4 text-2xl font-bold">Create Poll</h2>
        <form action="{{ route('polls.store', $event->id) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="poll_name" class="block mb-2 text-gray-700">Poll Name <span class="text-red-600">*</span></label>
                <input 
                    type="text" 
                    name="name" 
                    id="poll_name" 
                    aria-label="Poll Name"
                    class="w-full p-2 border border-gray-300 rounded-lg" 
                    required>
            </div>

            <div class="mb-4">
                <label for="poll_options" class="block mb-2 text-gray-700">Options (comma-separated) <span class="text-red-600">*</span></label>
                <textarea 
                    name="options" 
                    id="poll_options" 
                    rows="3" 
                    aria-label="Poll Options"
                    class="w-full p-2 border border-gray-300 rounded-lg" 
                    required></textarea>
            </div>

            <div class="mb-4">
                <label for="poll_start_time" class="block mb-2 text-gray-700">Start Time <span class="text-red-600">*</span></label>
                <input 
                    type="datetime-local" 
                    name="start_time" 
                    id="poll_start_time" 
                    aria-label="Poll Start Time"
                    class="w-full p-2 border border-gray-300 rounded-lg" 
                    required>
            </div>

            <div class="mb-4">
                <label for="poll_end_time" class="block mb-2 text-gray-700">End Time <span class="text-red-600">*</span></label>
                <input 
                    type="datetime-local" 
                    name="end_time" 
                    id="poll_end_time" 
                    aria-label="Poll End Time"
                    class="w-full p-2 border border-gray-300 rounded-lg" 
                    required>
            </div>

            <div class="flex justify-end space-x-4">
                <button 
                    type="button" 
                    onclick="togglePollForm()" 
                    aria-label="Cancel Poll Creation"
                    class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">
                    Cancel
                </button>
                <button 
                    type="submit" 
                    aria-label="Submit Poll Form"
                    class="px-4 py-2 text-white bg-blue-500 rounded-lg hover:bg-blue-600">
                    Create Poll
                </button>
            </div>
        </form>
    </div>
</div>
