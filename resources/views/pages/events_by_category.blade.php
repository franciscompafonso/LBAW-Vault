@extends('layouts.app')

@section('content')
<!-- Events by Category Section -->
<section class="py-16 px-6">
    <h1 class="text-4xl font-bold text-ultraViolet mb-6">{{ $category->name }}</h1> 

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($events as $event)
            @include('partials.event', ['event' => $event])
        @empty
            <p class="text-eerieBlack col-span-full">No events available in this category.</p> 
        @endforelse
    </div>
</section>
@endsection
