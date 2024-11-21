@extends('layouts.app')

@section('title', 'Products')

@section('content')
<section id="events" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-4">
    @foreach($events as $event)
        @include('partials.event', ['event' => $event])
    @endforeach
</section>
@endsection
