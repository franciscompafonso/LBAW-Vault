<section id="{{ $sectionId }}" class="grid w-full gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4">
    @foreach ($events as $event)
        @include('partials.event', ['event' => $event])
    @endforeach
</section>
<div class="mt-6">
    {{ $events->appends(['section' => $sectionId])->links() }}
</div>
