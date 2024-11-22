@extends('layouts.app')

@section('title', 'Products')

@section('content')
<section id="events" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-4">
    @forelse ($createdEvents as $event)
        @include('partials.event', ['event' => $event])
    @empty
        <p class="text-eerieBlack col-span-full">No events available in this category.</p> 
    @endforelse
</section>
@endsection
