@extends('layouts.app')

@section('title', 'Products')

@section('content')
<section id="events" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-4">
    @foreach($events as $event)
        @include('partials.event', ['event' => $event])
    @endforeach

    <!-- Form for adding a new product -->
    <article class="event bg-white shadow-md rounded-lg overflow-hidden max-w-sm m-4">
        <form class="new_event p-4">
            <input type="text" name="name" placeholder="New product" class="border border-gray-300 rounded p-2 w-full">
        </form>
    </article>
</section>
@endsection
