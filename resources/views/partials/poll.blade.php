<script src="{{ url('js/partials/poll.js') }}" defer></script>

<script>
    const pollData = {
        id: "{{ $poll->id }}",
        startTime: new Date("{{ $poll->start_time }}").getTime(),
        endTime: new Date("{{ $poll->end_time }}").getTime()
    };

    console.log("Poll Data:", pollData);

    document.addEventListener('DOMContentLoaded', function() {
        const countdownElement = document.getElementById('countdown-' + pollData.id);
        const now = new Date().getTime();

        console.log("Current Time:", now);
        console.log("Poll Start Time:", pollData.startTime);
        console.log("Poll End Time:", pollData.endTime);

        if (now < pollData.startTime) {
            console.log("Countdown to poll start");
            const countdownInterval = setInterval(function() {
                const now = new Date().getTime();
                const distance = pollData.startTime - now;

                console.log("Distance to start:", distance);

                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                countdownElement.innerHTML = days + "d " + hours + "h " + minutes + "m " + seconds + "s ";

                if (distance < 0) {
                    clearInterval(countdownInterval);
                    countdownElement.innerHTML = "Poll has started";
                    location.reload();
                }
            }, 1000);
        } else if (now < pollData.endTime) {
            console.log("Countdown to poll end");
            const countdownInterval = setInterval(function() {
                const now = new Date().getTime();
                const distance = pollData.endTime - now;

                console.log("Distance to end:", distance);

                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                countdownElement.innerHTML = days + "d " + hours + "h " + minutes + "m " + seconds + "s ";

                if (distance < 0) {
                    clearInterval(countdownInterval);
                    countdownElement.innerHTML = "Poll has ended";
                    location.reload();
                }
            }, 1000);
        } else {
            countdownElement.innerHTML = "Poll has ended";
        }
    });
</script>

<div class="poll bg-white p-6 rounded-lg shadow-lg mb-6">
    <!-- Poll Question -->
    <h3 class="text-xl font-bold text-ultraViolet font-montserrat mb-4" id="poll-question-{{ $poll->id }}">{{ $poll->name }}</h3>
    <p class="text-lg text-darkSlateGrey font-montserrat mb-4">
        @if(now()->isBefore($poll->start_time))
            Poll starts in: <span id="countdown-{{ $poll->id }}"></span>
        @elseif(now()->isBefore($poll->end_time))
            Poll ends in: <span id="countdown-{{ $poll->id }}"></span>
        @else
            Poll has ended.
        @endif
    </p>

    @if(session('error') && session('poll_id') === $poll->id)
        <div class="alert alert-danger text-red-500 mb-4 p-2 rounded-md" role="alert" aria-live="assertive">
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
                        <div class="flex justify-between items-center" role="group" aria-labelledby="option-{{ $poll->id }}-{{ $index }}">
                            <label for="option_{{ $poll->id }}_{{ $index }}" class="text-lg text-darkSlateGrey font-montserrat">
                                {{ $option }}
                            </label>
                            <span class="text-sm text-gray-500">
                                {{ round($percentage) }}%
                            </span>
                        </div>
                        <div class="relative h-4 bg-gray-200 rounded-md mt-1">
                            <div class="absolute h-4 bg-ultraViolet rounded-md transition-all duration-300" style="width: {{ $percentage }}%;"></div>
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
                    <button type="submit" aria-label="Submit your vote for the poll" class="px-6 py-2 font-bold text-white bg-ultraViolet hover:bg-cambridgeBlue border-ultraViolet hover:border-cambridgeBlue rounded-md transition-all duration-200">
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
