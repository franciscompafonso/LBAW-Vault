<script src="{{ url('js/partials/poll.js') }}" defer></script>
<div class="p-6 mb-6 bg-white rounded-lg shadow-lg poll" 
     data-poll-id="{{ $poll->id }}" 
     data-poll-start="{{ $poll->start_time->format('Y-m-d\TH:i:sP') }}" 
     data-poll-end="{{ $poll->end_time->format('Y-m-d\TH:i:sP') }}">
    <!-- Poll Question -->
    <h3 class="mb-4 text-3xl font-bold text-ultraViolet font-montserrat" id="poll-question-{{ $poll->id }}">{{ $poll->name }}</h3>
    <p class="mb-4 text-xl text-darkSlateGrey font-montserrat">
        <span id="countdown-{{ $poll->id }}"></span>
    </p>

    @if(session('error') && session('poll_id') === $poll->id)
        <div class="p-2 mb-4 text-red-500 rounded-md alert alert-danger" role="alert" aria-live="assertive">
            {{ session('error') }}
        </div>
    @endif

    <!-- Poll Form -->
    @php
        Log::info('Poll Data:', ['poll' => $poll]);
        $userResponse = $poll->responses->firstWhere('user_id', auth()->id());
        $options = json_decode($poll->options, true);
        if (!is_array($options)) {
            $options = [];
        }
        $voteCounts = array_count_values($poll->responses->pluck('option')->toArray());
        $totalVotes = $poll->responses->count();
    @endphp

    <form action="{{ route('polls.vote', $poll->id) }}" method="POST" aria-labelledby="poll-question-{{ $poll->id }}" role="form">
        @csrf
        <div class="space-y-4">
            @foreach($options as $index => $option)
                @if(!empty($option))
                    @php
                        $votes = $voteCounts[$index] ?? 0;
                        $percentage = $totalVotes > 0 ? ($votes / $totalVotes) * 100 : 0;
                    @endphp
                    <div class="poll-option">
                        <div class="flex items-center justify-between" role="group" aria-labelledby="option-{{ $poll->id }}-{{ $index }}">
                            <label for="option_{{ $poll->id }}_{{ $index }}" class="text-2xl text-darkSlateGrey font-montserrat">
                                {{ $option }}
                            </label>
                            <span class="text-sm text-gray-500">
                                {{ round($percentage) }}%
                            </span>
                        </div>
                        <div class="relative h-4 mt-1 bg-gray-200 rounded-md">
                            <div class="absolute h-4 transition-all duration-300 rounded-md bg-ultraViolet" style="width: {{ $percentage }}%;"></div>
                        </div>
                        <input type="radio" name="option" value="{{ $index }}" id="option_{{ $poll->id }}_{{ $index }}" aria-labelledby="option_{{ $poll->id }}_{{ $index }}_label" @if($userResponse && (string) $userResponse->option === (string) $index) checked @endif @if($userResponse) disabled @endif class="hidden">
                    </div>
                @endif
            @endforeach
        </div>
        @if(now()->isBefore($poll->start_time))
            <div class="mt-4 text-red-500" role="alert" aria-live="assertive">
                The poll hasn't started yet.
            </div>
        @else
            @can('vote', $poll)
                <div class="mt-4">
                    <button type="submit" aria-label="Submit your vote for the poll" class="px-6 py-2 font-bold text-white transition-all duration-200 rounded-md bg-ultraViolet hover:bg-cambridgeBlue border-ultraViolet hover:border-cambridgeBlue">
                        Vote
                    </button>
                </div>
            @else
                <div class="mt-4 text-red-500" role="alert" aria-live="assertive">
                    @if(now()->isAfter($poll->end_time))
                        Poll is closed.
                    @else
                        You have already voted.
                    @endif
                </div>
            @endcan
        @endif
    </form>
</div>
