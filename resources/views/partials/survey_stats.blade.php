@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto p-8">
        <h1 class="text-3xl font-bold text-ultraViolet">{{ $event->name }} - Survey Results</h1>

        @if (session('error'))
            <div class="bg-red-100 text-red-700 p-4 rounded-md mb-4" role="alert" aria-live="assertive">
                {{ session('error') }}
            </div>
        @endif

        @if(empty($chartData))
            <p>No responses yet.</p>
            @php
                $hasResponses = false;
            @endphp
        @else
            @php
                $hasResponses = true;
            @endphp
            @foreach ($chartData as $data)
                <div class="mt-6">
                    <h2 class="text-xl font-semibold">{{ $data['question_text'] }}</h2>
                    <canvas id="chart-{{ $loop->index }}" width="350" height="350" aria-label="Pie chart showing responses for {{ $data['question_text'] }}"></canvas>

                    <script>
                        new Chart(document.getElementById('chart-{{ $loop->index }}'), {
    type: 'pie', 
    data: {
        labels: ['1', '2', '3', '4', '5'],  
        datasets: [{
            data: [
                {{ $data['ratings'][1] }},
                {{ $data['ratings'][2] }},
                {{ $data['ratings'][3] }},
                {{ $data['ratings'][4] }},
                {{ $data['ratings'][5] }}
            ],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)', 
                'rgba(54, 162, 235, 0.2)', 
                'rgba(255, 206, 86, 0.2)', 
                'rgba(75, 192, 192, 0.2)', 
                'rgba(153, 102, 255, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)', 
                'rgba(54, 162, 235, 1)', 
                'rgba(255, 206, 86, 1)', 
                'rgba(75, 192, 192, 1)', 
                'rgba(153, 102, 255, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: true,  // Optionally, display the legend (showing rating labels)
            },
            tooltip: {
                callbacks: {
                    label: function(tooltipItem) {
                        // Customizing the tooltip to show percentage and count of responses
                        var rating = tooltipItem.label;
                        var count = tooltipItem.raw;
                        var total = tooltipItem.dataset.data.reduce((a, b) => a + b, 0);
                        var percentage = ((count / total) * 100).toFixed(2);
                        return `${rating}: ${count} responses (${percentage}%)`;
                    }
                }
            }
        }
    }
});

                    </script>
                </div>
            @endforeach
        @endif

    </div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
