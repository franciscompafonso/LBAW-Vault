@extends('layouts.app')

@section('title', 'Products')

@section('content')
<section id="events" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
    @forelse ($createdEvents as $event)
        @include('partials.event', ['event' => $event])
    @empty
        <p class="text-eerieBlack col-span-full">No events available in this category.</p> 
    @endforelse
</section>
@endsection
