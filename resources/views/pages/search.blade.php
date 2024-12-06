@extends('layouts.app')

@section('Title', 'Search')

@section('content')
<!-- Events by Category Section -->
<section class="w-full px-6 py-16">

    <!-- Main Grid Layout -->
    <div class="grid grid-cols-7 gap-6">
        @include('partials.filters')

        <!-- Events Grid -->
        <div class="col-span-6">
            <div class="grid gap-10 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                @forelse ($events as $event)
                    @include('partials.event', ['event' => $event])
                @empty
                    <p class="text-eerieBlack font-montserrat col-span-full">No events available in this category.</p>
                @endforelse
            </div>
        </div>
    </div>
</section>

@endsection

