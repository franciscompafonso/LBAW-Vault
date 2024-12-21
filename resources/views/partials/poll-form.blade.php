<script src="{{ url('js/partials/poll-form.js') }}" defer></script>
<div id="poll-form" class="fixed inset-0 z-50 flex items-center justify-center {{ $errors->any() ? '' : 'hidden' }} bg-gray-500 bg-opacity-75">
    <div class="w-1/2 p-6 bg-white rounded-lg shadow-lg">
        <h2 class="mb-4 text-2xl font-bold">Create Poll</h2>
        @if(session('error'))
            <div class="mb-4 text-red-500">
                {{ session('error') }}
            </div>
        @endif
        <form id="pollForm" action="{{ route('polls.store', $event->id) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="poll_name" class="block mb-2 text-gray-700">Poll Name <span class="text-red-600">*</span></label>
                <input 
                    type="text" 
                    name="name"
                    maxlength="100" 
                    id="poll_name" 
                    aria-label="Poll Name"
                    class="w-full p-2 border border-gray-300 rounded-lg @error('name') border-red-500 @enderror" 
                    value="{{ old('name') }}" 
                    required>
                @error('name')
                    <span class="text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="poll-options" class="block mb-2 text-gray-700">Options <span class="text-red-600">*</span></label>
                <div id="poll-options-container" class="space-y-2">
                    @if(old('options'))
                        @foreach(old('options') as $option)
                            <div class="flex items-center">
                                <input type="text" maxlength="40" name="options[]" id="poll-options" class="w-full p-2 border border-gray-300 rounded-lg @error('options.*') border-red-500 @enderror" value="{{ $option }}" required>
                                <button type="button" class="ml-2 px-2 py-1 bg-red-500 text-white rounded-lg" onclick="removeOption(this)">X</button>
                            </div>
                        @endforeach
                    @else
                        <div class="flex items-center">
                            <input type="text" maxlength="40" name="options[]" id="poll-options" class="w-full p-2 border border-gray-300 rounded-lg" required>
                            <button type="button" class="ml-2 px-2 py-1 bg-red-500 text-white rounded-lg" onclick="removeOption(this)">X</button>
                        </div>
                    @endif
                </div>
                <button type="button" class="mt-2 px-4 py-2 bg-ultraViolet text-white rounded-lg" onclick="addOption()">+ Add Option</button>
                @error('options')
                    <span class="text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="poll_start_time" class="block mb-2 text-gray-700">Start Time <span class="text-red-600">*</span></label>
                <input 
                    type="datetime-local" 
                    name="start_time" 
                    id="poll_start_time" 
                    aria-label="Poll Start Time"
                    class="w-full p-2 border border-gray-300 rounded-lg @error('start_time') border-red-500 @enderror" 
                    value="{{ old('start_time') }}" 
                    required>
                @error('start_time')
                    <span class="text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="poll_end_time" class="block mb-2 text-gray-700">End Time <span class="text-red-600">*</span></label>
                <input 
                    type="datetime-local" 
                    name="end_time" 
                    id="poll_end_time" 
                    aria-label="Poll End Time"
                    class="w-full p-2 border border-gray-300 rounded-lg @error('end_time') border-red-500 @enderror" 
                    value="{{ old('end_time') }}" 
                    required>
                @error('end_time')
                    <span class="text-red-500">{{ $message }}</span>
                @enderror
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
