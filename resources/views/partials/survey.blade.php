@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto p-8">
        <h1 class="text-3xl font-bold text-ultraViolet">{{ $event->name }} - Satisfaction Survey</h1>
        @if ($hasSubmitted)
            <p class="text-lg text-center text-green-500">You have already submitted your feedback for this event. Thank you!</p>
        @else
            <form action="{{ route('events.submitSurvey', $event->id) }}" method="POST">
                @csrf
                @foreach ($questions as $question)
                    <div class="mt-6">
                        <label class="text-lg text-darkSlateGrey font-semibold">{{ $question->question_text }}</label>
                        <select name="answers[{{ $question->id }}]" class="mt-2 p-2 border border-gray-300 rounded-lg w-full" required>
                            <option value="">Select a rating</option>
                            @for ($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                @endforeach

                <div class="mt-6 flex justify-end">
                    <button type="submit" class="px-6 py-2 text-white bg-burgundy rounded-lg">Submit</button>
                </div>
            </form>
        @endif
    </div>
@endsection
