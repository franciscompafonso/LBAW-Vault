<article class="event bg-white shadow-md rounded-lg overflow-hidden hover:scale-105 transition-transform duration-300 w-full max-w-sm mx-auto">
    <img src="<?php echo e($event->image_url ?? 'default-image.jpg'); ?>" alt="<?php echo e($event->name); ?>" class="w-full h-40 object-cover">

    <div class="p-4">
        <h3 class="text-2xl font-semibold font-montserrat text-ultraViolet"><?php echo e($event->name); ?></h3>
        <p class="text-darkSlateGrey font-montserrat mt-2"><?php echo e($event->description); ?></p>
        <p class="text-darkSlateGrey text-sm font-montserrat mt-4">
            Date: <?php echo e($event->start_date ? \Carbon\Carbon::parse($event->date)->format('d/m/Y') : 'Not available'); ?>

        </p>
        <p class="text-darkSlateGrey text-sm font-montserrat mt-4">
            Start Time: <?php echo e(\Carbon\Carbon::parse($event->start_date)->format('H:i')); ?> 
        </p>

        <div class="flex flex-col items-center py-4 px-2">
            <a href="<?php echo e(route('events.show', $event->id)); ?>"
               class="w-full text-cambridgeBlue text-2xl font-bold py-3 rounded-lg hover:text-darkSlateGrey text-center transition-all duration-200 font-montserrat">
                View Details
            </a>
        </div>       
    </div>
</article>
<?php /**PATH /home/franciscoafonso/Desktop/LBAW/lbaw2414/resources/views/partials/home_event.blade.php ENDPATH**/ ?>