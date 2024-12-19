@forelse ($events as $event)
    @include('partials.event', ['event' => $event])
@empty
    <p class="text-eerieBlack font-montserrat col-span-full" aria-live="polite">No events available in this category.</p>
@endforelse

<div class="col-span-full mt-6 flex justify-center" aria-label="Pagination navigation">
    {{ $events->appends(request()->query())->links() }}
</div>