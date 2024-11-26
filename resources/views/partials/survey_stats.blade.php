@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto p-8">
        <h1 class="text-3xl font-bold text-ultraViolet">{{ $event->name }} - Survey Results</h1>
        
        @if($event->end_date > now())
            <p class="text-red-600">Survey results are not available until the event has ended.</p>
        @else
            @if($answers->isEmpty())
                <p>No responses yet.</p>
            @else
                <table class="mt-6 w-full table-auto border-collapse">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left border-b">Question</th>
                            <th class="px-4 py-2 text-left border-b">Average Rating</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($answers as $answer)
                            <tr>
                                <td class="px-4 py-2 border-b">{{ $answer->question_text }}</td>
                                <td class="px-4 py-2 border-b">{{ number_format($answer->average_rating, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        @endif
    </div>
@endsection
