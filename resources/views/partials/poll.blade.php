<div class="poll bg-white p-6 rounded-lg shadow-lg mb-6">
    <!-- Poll Question -->
    <h3 class="text-xl font-bold text-ultraViolet font-montserrat mb-4" id="poll-question-{{ $poll->id }}>{{ $poll->name }}</h3>
    <p class="text-lg text-darkSlateGrey font-montserrat mb-4">
        <strong class="font-semibold">Poll Duration:</strong> 
        {{ $poll->start_time->format('d/m/Y H:i') }} - 
        {{ $poll->end_time->format('d/m/Y H:i') }}
    </p>

    @if(session('error') && session('poll_id') === $poll->id)
        <div class="alert alert-danger text-red-500 mb-4 p-2 rounded-md" role="alert" aria-live="assertive">
            {{ session('error') }}
        </div>
    @endif

    <!-- Poll Form -->
    @php
        $userResponse = $poll->responses->firstWhere('user_id', auth()->id());
        $options = explode(',', $poll->options);
        $voteCounts = array_count_values($poll->responses->pluck('option')->toArray());
        $totalVotes = $poll->responses->count();
    @endphp

    <form action="{{ route('polls.vote', $poll->id) }}" method="POST" aria-labelledby="poll-question-{{ $poll->id }}" role="form">
        @csrf
        <div class="space-y-4">
            @foreach($options as $index => $option)
                @php
                    $votes = $voteCounts[$index] ?? 0;
                    $percentage = $totalVotes > 0 ? ($votes / $totalVotes) * 100 : 0;
                @endphp
                <div class="poll-option">
                    <div class="flex justify-between items-center" role="group" aria-labelledby="option-{{ $poll->id }}-{{ $index }}">
                        <label for="option_{{ $poll->id }}_{{ $index }}" class="text-lg text-darkSlateGrey font-montserrat">
                            {{ $option }}
                        </label>
                        <span class="text-sm text-gray-500">
                            {{ round($percentage) }}%
                        </span>
                    </div>
                    <div class="relative h-4 bg-gray-200 rounded-md mt-1">
                        <div 
                            class="absolute h-4 bg-ultraViolet rounded-md transition-all duration-300"
                            style="width: {{ $percentage }}%;">
                        </div>
                    </div>
                    <input 
                        type="radio" 
                        name="option" 
                        value="{{ $index }}" 
                        id="option_{{ $poll->id }}_{{ $index }}"
                        aria-labelledby="option_{{ $poll->id }}_{{ $index }}_label"
                        @if($userResponse && (string) $userResponse->option === (string) $index) checked @endif
                        @if($userResponse) disabled @endif
                        class="hidden"
                    >
                </div>
            @endforeach
        </div>

        @if(!$userResponse && Auth::id() && now()->between($poll->start_time, $poll->end_time))
            <div class="mt-4">
                <button 
                    type="submit" aria-label="Submit your vote for the poll"
                    class="px-6 py-2 font-bold text-white bg-ultraViolet hover:bg-cambridgeBlue border-ultraViolet hover:border-cambridgeBlue rounded-md transition-all duration-200">
                    Vote
                </button>
            </div>
        @elseif(now()->isAfter($poll->end_time))
            <div class="mt-4 text-red-500" role="alert" aria-live="assertive">
                Voting has ended for this poll.
            </div>
        @endif
    </form>
</div>
