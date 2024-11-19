@extends('layouts.app')

@section('title', $card->name)

<section id="content" class="p-6 bg-green-100 rounded-md grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
    @foreach ($cards as $card)
        @include('partials.card', ['card' => $card])
    @endforeach
</section>