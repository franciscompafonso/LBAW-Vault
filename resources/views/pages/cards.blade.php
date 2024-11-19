@extends('layouts.app')

@section('title', 'Cards')

@section('content')
<section id="cards" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-4">
    @each('partials.card', $cards, 'card')
    <article class="card bg-white shadow-md rounded-lg overflow-hidden max-w-sm m-4">
        <form class="new_card p-4">
            <input type="text" name="name" placeholder="new card" class="border border-gray-300 rounded p-2 w-full">
        </form>
    </article>
</section>
@endsection