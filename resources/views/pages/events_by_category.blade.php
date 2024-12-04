@extends('layouts.app')

@if($category)
    @section('Title', $category->name)
@else
    @section('Title', 'All Categories')
@endif

@section('content')
<!-- Events by Category Section -->
<section class="px-6 py-16">
<h1 class="mb-6 text-4xl font-bold text-ultraViolet font-montserrat">
        @if($category)
            {{ $category->name }}
        @else
            All Categories
        @endif
    </h1> 

    <div class="grid gap-10 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
        @forelse ($events as $event)
            @include('partials.event', ['event' => $event])
        @empty
            <p class="text-eerieBlack font-montserrat col-span-full">No events available in this category.</p> 
        @endforelse
    </div>
</section>
@endsection
